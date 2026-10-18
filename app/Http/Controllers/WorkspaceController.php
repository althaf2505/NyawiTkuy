<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Shipment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function buyerDashboard(Request $request): View
    {
        $user = $request->user();

        return view('workspace.dashboard', [
            'workspace' => 'buyer',
            'title' => 'Buyer dashboard',
            'metrics' => [
                ['label' => 'Active RFQs', 'value' => Quotation::query()->where('user_id', $user->id)->whereIn('status', ['submitted', 'under_review', 'quoted'])->count()],
                ['label' => 'Orders in progress', 'value' => Order::query()->where('user_id', $user->id)->whereIn('status', ['awaiting_payment', 'processing', 'shipped'])->count()],
                ['label' => 'Shipments', 'value' => Shipment::query()->whereHas('order', fn ($query) => $query->where('user_id', $user->id))->count()],
            ],
            'items' => Quotation::query()->where('user_id', $user->id)->latest()->take(5)->get(),
        ]);
    }

    public function adminDashboard(): View
    {
        return view('workspace.dashboard', [
            'workspace' => 'admin',
            'title' => 'Seller dashboard',
            'metrics' => [
                ['label' => 'Active products', 'value' => Product::query()->active()->count()],
                ['label' => 'New RFQs', 'value' => Quotation::query()->where('status', 'submitted')->count()],
                ['label' => 'Orders in progress', 'value' => Order::query()->whereIn('status', ['awaiting_payment', 'processing', 'shipped'])->count()],
            ],
            'items' => Quotation::query()->with('user')->latest()->take(5)->get(),
        ]);
    }
}
