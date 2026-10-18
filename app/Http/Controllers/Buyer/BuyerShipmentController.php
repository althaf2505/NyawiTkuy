<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BuyerShipmentController extends Controller
{
    public function index(Request $request): View
    {
        $shipments = Shipment::query()
            ->whereHas('order', fn ($q) => $q->where('user_id', $request->user()->id))
            ->with(['order', 'updates', 'latestUpdate'])
            ->latest()
            ->paginate(10);

        return view('buyer.shipments.index', [
            'shipments' => $shipments,
        ]);
    }

    public function show(Request $request, Shipment $shipment): View
    {
        abort_unless($shipment->order && $shipment->order->user_id === $request->user()->id, 403);

        $shipment->load(['order.items', 'updates' => fn ($q) => $q->orderBy('event_date', 'desc')]);

        return view('buyer.shipments.show', [
            'shipment' => $shipment,
        ]);
    }
}
