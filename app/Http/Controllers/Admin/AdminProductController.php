<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $category = $request->query('category');
        $status = $request->query('status');

        $products = Product::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($status !== null && $status !== '', fn ($q) => $q->where('is_active', (bool) $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Product::query()->select('category')->distinct()->pluck('category');

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'category' => $category,
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'indicative_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'unit' => ['required', 'string', 'max:50'],
            'moq' => ['required', 'numeric', 'min:1'],
            'stock_capacity' => ['nullable', 'numeric', 'min:0'],
            'origin' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
            'spec_keys' => ['nullable', 'array'],
            'spec_values' => ['nullable', 'array'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $specifications = [];
        if (!empty($validated['spec_keys']) && !empty($validated['spec_values'])) {
            foreach ($validated['spec_keys'] as $index => $key) {
                if (!empty($key) && isset($validated['spec_values'][$index])) {
                    $specifications[trim($key)] = trim($validated['spec_values'][$index]);
                }
            }
        }

        Product::query()->create([
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'indicative_price' => $validated['indicative_price'] ?? null,
            'currency' => strtoupper($validated['currency']),
            'unit' => $validated['unit'],
            'moq' => $validated['moq'],
            'stock_capacity' => $validated['stock_capacity'] ?? null,
            'origin' => $validated['origin'],
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
            'specifications' => $specifications,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', "unique:products,sku,{$product->id}"],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'indicative_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'unit' => ['required', 'string', 'max:50'],
            'moq' => ['required', 'numeric', 'min:1'],
            'stock_capacity' => ['nullable', 'numeric', 'min:0'],
            'origin' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
            'spec_keys' => ['nullable', 'array'],
            'spec_values' => ['nullable', 'array'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $specifications = [];
        if (!empty($validated['spec_keys']) && !empty($validated['spec_values'])) {
            foreach ($validated['spec_keys'] as $index => $key) {
                if (!empty($key) && isset($validated['spec_values'][$index])) {
                    $specifications[trim($key)] = trim($validated['spec_values'][$index]);
                }
            }
        }

        $product->update([
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'indicative_price' => $validated['indicative_price'] ?? null,
            'currency' => strtoupper($validated['currency']),
            'unit' => $validated['unit'],
            'moq' => $validated['moq'],
            'stock_capacity' => $validated['stock_capacity'] ?? null,
            'origin' => $validated['origin'],
            'image' => $validated['image'] ?? $product->image,
            'is_active' => $request->boolean('is_active'),
            'specifications' => $specifications,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product archived successfully.');
    }
}
