<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'featuredProducts' => Product::query()->active()->latest()->take(3)->get(),
        ]);
    }

    public function products(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $category = trim((string) $request->query('category'));
        $availability = $request->query('availability');
        $sort = $request->query('sort', 'newest');

        $products = Product::query()
            ->active()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($availability === 'available', fn ($query) => $query->whereNotNull('stock_capacity')->where('stock_capacity', '>', 0))
            ->when($availability === 'on-request', fn ($query) => $query->where(function ($query): void {
                $query->whereNull('stock_capacity')->orWhere('stock_capacity', '<=', 0);
            }))
            ->when($sort === 'name', fn ($query) => $query->orderBy('name'))
            ->when($sort === 'price_asc', fn ($query) => $query->orderBy('indicative_price'))
            ->when($sort === 'price_desc', fn ($query) => $query->orderByDesc('indicative_price'))
            ->when($sort === 'newest', fn ($query) => $query->latest())
            ->paginate(9)
            ->withQueryString();

        return view('public.products', [
            'products' => $products,
            'categories' => Product::query()->active()->select('category')->distinct()->orderBy('category')->pluck('category'),
            'filters' => compact('search', 'category', 'availability', 'sort'),
        ]);
    }

    public function product(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('public.product', [
            'product' => $product,
            'relatedProducts' => Product::query()
                ->active()
                ->where('category', $product->category)
                ->whereKeyNot($product->getKey())
                ->take(3)
                ->get(),
        ]);
    }

    public function page(string $page): View
    {
        abort_unless(in_array($page, ['about', 'contact', 'export-process'], true), 404);

        return view("public.{$page}");
    }
}
