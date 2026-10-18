<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationOffer;
use App\Models\QuotationOfferItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminQuotationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('q'));

        $quotations = Quotation::query()
            ->with(['user', 'items.product', 'latestOffer'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('quotation_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search): void {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('company_name', 'like', "%{$search}%")
                            ->orWhere('country', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.quotations.index', [
            'quotations' => $quotations,
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function show(Quotation $quotation): View
    {
        $quotation->load(['user', 'items.product', 'offers.items.product', 'order']);

        return view('admin.quotations.show', [
            'quotation' => $quotation,
        ]);
    }

    public function sendOffer(Request $request, Quotation $quotation): RedirectResponse
    {
        $validated = $request->validate([
            'unit_price' => ['required', 'numeric', 'min:0.01'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', 'string', 'size:3'],
            'trade_terms' => ['required', 'string', 'max:50'],
            'payment_terms' => ['required', 'string', 'max:255'],
            'valid_until' => ['required', 'date', 'after:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($quotation, $validated): void {
            // Mark any previous sent offers as superseded
            $quotation->offers()->where('status', 'sent')->update(['status' => 'superseded']);

            $latestVersion = $quotation->offers()->max('version') ?? 0;
            $unitPrice = (float) $validated['unit_price'];
            $quantity = (float) $validated['quantity'];
            $totalAmount = round($unitPrice * $quantity, 2);

            $offer = QuotationOffer::query()->create([
                'quotation_id' => $quotation->id,
                'version' => $latestVersion + 1,
                'offered_by' => 'admin',
                'currency' => strtoupper($validated['currency']),
                'total_amount' => $totalAmount,
                'trade_terms' => $validated['trade_terms'],
                'payment_terms' => $validated['payment_terms'],
                'valid_until' => $validated['valid_until'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'sent',
            ]);

            $firstItem = $quotation->items->first();
            QuotationOfferItem::query()->create([
                'quotation_offer_id' => $offer->id,
                'product_id' => $firstItem ? $firstItem->product_id : null,
                'product_name' => $firstItem && $firstItem->product ? $firstItem->product->name : 'Palm Oil Product',
                'quantity' => $quantity,
                'unit' => $firstItem ? $firstItem->unit : 'MT',
                'unit_price' => $unitPrice,
                'total_price' => $totalAmount,
            ]);

            $quotation->update(['status' => 'quoted']);
        });

        return back()->with('success', 'Quotation offer sent to the buyer successfully.');
    }

    public function respondCounter(Request $request, Quotation $quotation): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:accept,reject'],
            'offer_id' => ['required', 'exists:quotation_offers,id'],
        ]);

        $offer = QuotationOffer::query()
            ->where('quotation_id', $quotation->id)
            ->where('id', $validated['offer_id'])
            ->firstOrFail();

        if ($validated['action'] === 'accept') {
            DB::transaction(function () use ($quotation, $offer): void {
                $offer->update(['status' => 'accepted']);
                $quotation->update(['status' => 'accepted']);

                // Create Order from buyer's counter-offer
                $order = \App\Models\Order::query()->create([
                    'user_id' => $quotation->user_id,
                    'quotation_id' => $quotation->id,
                    'quotation_offer_id' => $offer->id,
                    'total_amount' => $offer->total_amount,
                    'currency' => $offer->currency,
                    'trade_terms' => $offer->trade_terms,
                    'payment_terms' => $offer->payment_terms,
                    'status' => 'awaiting_payment',
                ]);

                foreach ($offer->items as $item) {
                    \App\Models\OrderItem::query()->create([
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

            return back()->with('success', 'Counter-offer accepted! Order has been created.');
        }

        if ($validated['action'] === 'reject') {
            $offer->update(['status' => 'rejected']);
            $quotation->update(['status' => 'under_review']);

            return back()->with('success', 'Counter-offer rejected. You can create a revised offer.');
        }

        return back();
    }
}
