<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuyerOrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with(['items', 'payments', 'shipments'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('buyer.orders.index', [
            'orders' => $orders,
            'status' => $status,
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load(['items.product', 'payments.verifier', 'shipments.updates', 'quotation']);

        return view('buyer.orders.show', [
            'order' => $order,
        ]);
    }

    public function submitPayment(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if ($order->status !== 'awaiting_payment') {
            return back()->withErrors(['payment' => 'This order is not awaiting payment.']);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3'],
            'method' => ['required', 'string', 'max:50'],
            'reference' => ['required', 'string', 'max:255'],
            'proof_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $filePath = $request->file('proof_file')->store('payments', 'public');

        Payment::query()->create([
            'order_id' => $order->id,
            'amount' => $validated['amount'],
            'currency' => strtoupper($validated['currency']),
            'method' => $validated['method'],
            'reference' => $validated['reference'],
            'proof_file' => $filePath,
            'notes' => $validated['notes'] ?? null,
            'status' => 'submitted',
        ]);

        return back()->with('success', 'Payment proof submitted. Seller will verify your payment shortly.');
    }
}
