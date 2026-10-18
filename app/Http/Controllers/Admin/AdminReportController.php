<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Quotation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    public function index(): View
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::query()->whereIn('status', ['processing', 'shipped', 'completed'])->sum('total_amount');
        $totalRfqs = Quotation::count();

        $ordersByStatus = Order::query()
            ->selectRaw('status, count(*) as count, sum(total_amount) as total')
            ->groupBy('status')
            ->get();

        $recentTransactions = Order::query()
            ->with(['user', 'items'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.index', [
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalRfqs' => $totalRfqs,
            'ordersByStatus' => $ordersByStatus,
            'recentTransactions' => $recentTransactions,
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        $fileName = 'palmtrade_orders_report_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        return response()->stream(function (): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order Number', 'Buyer Name', 'Company', 'Country', 'Total Amount', 'Currency', 'Trade Terms', 'Status', 'Date']);

            Order::query()->with('user')->chunk(100, function ($orders) use ($handle): void {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->order_number,
                        $order->user ? $order->user->name : 'N/A',
                        $order->user ? $order->user->company_name : 'N/A',
                        $order->user ? $order->user->country : 'N/A',
                        $order->total_amount,
                        $order->currency,
                        $order->trade_terms,
                        $order->status,
                        $order->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
