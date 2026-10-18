<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\QuotationOffer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuyerQuotationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('q'));

        $quotations = Quotation::query()
            ->where('user_id', $request->user()->id)
            ->with(['items.product', 'latestOffer'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('quotation_number', 'like', "%{$search}%")
                    ->orWhere('destination_country', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('buyer.quotations.index', [
            'quotations' => $quotations,
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function create(Request $request): View
    {
        $selectedProductId = $request->query('product_id');
        $products = Product::query()->active()->orderBy('name')->get();

        return view('buyer.quotations.create', [
            'products' => $products,
            'selectedProductId' => $selectedProductId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'unit' => ['required', 'string', 'max:50'],
            'destination_country' => ['required', 'string', 'max:100'],
            'destination_port' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $product = Product::query()->findOrFail($validated['product_id']);

        if ($validated['quantity'] < $product->moq) {
            return back()
                ->withErrors(['quantity' => "Minimum order quantity for {$product->name} is {$product->moq} {$product->unit}."])
                ->withInput();
        }

        DB::transaction(function () use ($request, $validated, $product): void {
            $quotation = Quotation::query()->create([
                'user_id' => $request->user()->id,
                'destination_country' => $validated['destination_country'],
                'destination_port' => $validated['destination_port'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'submitted',
            ]);

            QuotationItem::query()->create([
                'quotation_id' => $quotation->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'unit' => $validated['unit'],
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()->route('buyer.quotations.index')
            ->with('success', 'Your Request for Quotation (RFQ) has been submitted successfully.');
    }

    public function show(Request $request, Quotation $quotation): View
    {
        abort_unless($quotation->user_id === $request->user()->id, 403);

        $quotation->load(['items.product', 'offers.items.product', 'order']);

        return view('buyer.quotations.show', [
            'quotation' => $quotation,
        ]);
    }

    public function respond(Request $request, Quotation $quotation): RedirectResponse
    {
        abort_unless($quotation->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'action' => ['required', 'in:accept,reject,counter'],
            'offer_id' => ['required', 'exists:quotation_offers,id'],
            'counter_price' => ['required_if:action,counter', 'nullable', 'numeric', 'min:0.01'],
            'counter_quantity' => ['required_if:action,counter', 'nullable', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        /** @var QuotationOffer $offer */
        $offer = QuotationOffer::query()
            ->where('quotation_id', $quotation->id)
            ->where('id', $validated['offer_id'])
            ->firstOrFail();

        if ($offer->status !== 'sent') {
            return back()->withErrors(['offer' => 'This offer can no longer be responded to.']);
        }

        if ($offer->valid_until && $offer->valid_until->isPast()) {
            $offer->update(['status' => 'expired']);
            return back()->withErrors(['offer' => 'This offer has expired.']);
        }

        if ($validated['action'] === 'accept') {
            DB::transaction(function () use ($quotation, $offer, $request): void {
                $offer->update(['status' => 'accepted']);
                $quotation->update(['status' => 'accepted']);

                // Create Order atomically
                $order = Order::query()->create([
                    'user_id' => $request->user()->id,
                    'quotation_id' => $quotation->id,
                    'quotation_offer_id' => $offer->id,
                    'total_amount' => $offer->total_amount,
                    'currency' => $offer->currency,
                    'trade_terms' => $offer->trade_terms,
                    'payment_terms' => $offer->payment_terms,
                    'status' => 'awaiting_payment',
                ]);

                foreach ($offer->items as $item) {
                    OrderItem::query()->create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'product_sku' => $item->product ? $item->product->sku : 'N/A',
                        'quantity' => $item->quantity,
                        'unit' => $item->unit,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                    ]);
                }
            });

            return redirect()->route('buyer.orders.index')
                ->with('success', 'Offer accepted! Order has been created and is awaiting payment.');
        }

        if ($validated['action'] === 'reject') {
            $offer->update(['status' => 'rejected']);
            $quotation->update(['status' => 'rejected']);

            return back()->with('success', 'You have rejected this quotation offer.');
        }

        if ($validated['action'] === 'counter') {
            DB::transaction(function () use ($quotation, $offer, $validated): void {
                $offer->update(['status' => 'countered']);

                $counterPrice = (float) $validated['counter_price'];
                $counterQty = (float) $validated['counter_quantity'];
                $totalAmount = round($counterPrice * $counterQty, 2);

                $newOffer = QuotationOffer::query()->create([
                    'quotation_id' => $quotation->id,
                    'version' => $offer->version + 1,
                    'offered_by' => 'buyer',
                    'currency' => $offer->currency,
                    'total_amount' => $totalAmount,
                    'trade_terms' => $offer->trade_terms,
                    'payment_terms' => $offer->payment_terms,
                    'notes' => $validated['notes'] ?? 'Counter offer from buyer',
                    'valid_until' => now()->addDays(7),
                    'status' => 'sent',
                ]);

                $firstItem = $offer->items->first();
                if ($firstItem) {
                    $newOffer->items()->create([
                        'product_id' => $firstItem->product_id,
                        'product_name' => $firstItem->product_name,
                        'quantity' => $counterQty,
                        'unit' => $firstItem->unit,
                        'unit_price' => $counterPrice,
                        'total_price' => $totalAmount,
                    ]);
                }

                $quotation->update(['status' => 'under_review']);
            });

            return back()->with('success', 'Your counter-offer has been submitted to the seller for review.');
        }

        return back();
    }
}
