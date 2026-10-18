<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShipmentUpdate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminShipmentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $shipments = Shipment::query()
            ->with(['order.user', 'updates', 'latestUpdate'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.shipments.index', [
            'shipments' => $shipments,
            'status' => $status,
        ]);
    }

    public function create(Request $request): View
    {
        $orderId = $request->query('order_id');
        $orders = Order::query()
            ->whereIn('status', ['processing', 'awaiting_payment'])
            ->with('user')
            ->latest()
            ->get();

        return view('admin.shipments.create', [
            'orders' => $orders,
            'selectedOrderId' => $orderId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'carrier' => ['nullable', 'string', 'max:255'],
            'tracking_reference' => ['nullable', 'string', 'max:255'],
            'estimated_arrival' => ['nullable', 'date'],
            'initial_status' => ['required', 'in:preparing,ready_to_ship,in_transit'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($validated): void {
            $shipment = Shipment::query()->create([
                'order_id' => $validated['order_id'],
                'origin' => $validated['origin'],
                'destination' => $validated['destination'],
                'carrier' => $validated['carrier'] ?? null,
                'tracking_reference' => $validated['tracking_reference'] ?? null,
                'estimated_arrival' => $validated['estimated_arrival'] ?? null,
                'status' => $validated['initial_status'],
            ]);

            ShipmentUpdate::query()->create([
                'shipment_id' => $shipment->id,
                'title' => 'Shipment Created',
                'description' => $validated['note'] ?? 'Shipment record initialized by export team.',
                'location' => $validated['origin'],
                'status' => $validated['initial_status'],
                'event_date' => now(),
            ]);

            $order = Order::query()->find($validated['order_id']);
            if ($order && in_array($validated['initial_status'], ['ready_to_ship', 'in_transit'])) {
                $order->update(['status' => 'shipped']);
            }
        });

        return redirect()->route('admin.shipments.index')
            ->with('success', 'Shipment created successfully.');
    }

    public function show(Shipment $shipment): View
    {
        $shipment->load(['order.user', 'order.items', 'updates' => fn ($q) => $q->orderBy('event_date', 'desc')]);

        return view('admin.shipments.show', [
            'shipment' => $shipment,
        ]);
    }

    public function updateStatus(Request $request, Shipment $shipment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:preparing,ready_to_ship,in_transit,arrived,delivered'],
            'location' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($shipment, $validated): void {
            $shipment->update(['status' => $validated['status']]);

            ShipmentUpdate::query()->create([
                'shipment_id' => $shipment->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'location' => $validated['location'] ?? null,
                'status' => $validated['status'],
                'event_date' => now(),
            ]);

            if ($validated['status'] === 'delivered' && $shipment->order) {
                $shipment->order->update(['status' => 'completed']);
            } elseif (in_array($validated['status'], ['ready_to_ship', 'in_transit']) && $shipment->order) {
                $shipment->order->update(['status' => 'shipped']);
            }
        });

        return back()->with('success', 'Shipment milestone updated successfully.');
    }
}
