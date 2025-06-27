<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FoodStorageController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter op barcode
        if ($request->filled('barcode')) {
            $query->where('barcode', 'like', '%' . $request->barcode . '%');
        }

        // Filter op categorie
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        // Sorteren
        $sort = $request->get('sort', 'barcode');
        $direction = $request->get('direction', 'asc');
        if (in_array($sort, ['barcode', 'name', 'category', 'quantity'])) {
            $query->orderBy($sort, $direction);
        }

        $products = $query->get();

        // Wijzig de view van 'storage.index' naar 'foodstorage.index'
        return view('foodstorage.index', compact('products'));
    }

    public function create()
    {
        // Wijzig de view van 'storage.create' naar 'foodstorage.create'
        return view('foodstorage.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product toegevoegd!');
    }

    public function edit(Product $product)
    {
        // Wijzig de view van 'storage.edit' naar 'foodstorage.edit'
        return view('foodstorage.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $product->id,
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product bijgewerkt!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product verwijderd!');
    }
}
