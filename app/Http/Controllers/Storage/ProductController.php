<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = auth()->user()->products ?? collect();
        return view('storage.products.index', compact('products'));
    }

    public function create()
    {
        return view('storage.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        auth()->user()->products()->create($request->only(['name', 'category', 'specs']));
        return redirect()->route('storage.products.index')->with('success', 'Product created.');
    }

    public function show(Product $product)
    {
        return view('storage.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('storage.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        $product->update($request->only(['name', 'category', 'specs']));
        return redirect()->route('storage.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('storage.products.index')->with('success', 'Product deleted.');
    }
}
