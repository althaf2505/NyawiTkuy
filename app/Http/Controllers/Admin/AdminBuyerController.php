<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminBuyerController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $buyers = User::query()
            ->where('role', 'buyer')
            ->withCount(['quotations', 'orders'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.buyers.index', [
            'buyers' => $buyers,
            'search' => $search,
        ]);
    }

    public function show(User $buyer): View
    {
        abort_unless($buyer->isBuyer(), 404);

        $buyer->load([
            'quotations' => fn ($q) => $q->latest()->take(5),
            'orders' => fn ($q) => $q->with('items')->latest()->take(5),
        ]);

        return view('admin.buyers.show', [
            'buyer' => $buyer,
        ]);
    }
}
