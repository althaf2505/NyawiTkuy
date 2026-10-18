<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $payments = Payment::query()
            ->with(['order.user', 'verifier'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.payments.index', [
            'payments' => $payments,
            'status' => $status,
        ]);
    }

    public function show(Payment $payment): View
    {
        $payment->load(['order.user', 'order.items', 'verifier']);

        return view('admin.payments.show', [
            'payment' => $payment,
        ]);
    }

    public function verify(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:verify,reject'],
            'rejection_reason' => ['required_if:action,reject', 'nullable', 'string', 'max:500'],
        ]);

        if ($validated['action'] === 'verify') {
            $payment->update([
                'status' => 'verified',
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);

            // Auto-advance order status to processing
            if ($payment->order && $payment->order->status === 'awaiting_payment') {
                $payment->order->update(['status' => 'processing']);
            }

            return back()->with('success', 'Payment verified successfully. Order moved to processing.');
        }

        if ($validated['action'] === 'reject') {
            $payment->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);

            return back()->with('success', 'Payment rejected with stated reason.');
        }

        return back();
    }
}
