<?php
namespace App\Http\Controllers;

use App\Models\FoodStorage;
use Illuminate\Http\Request;

class FoodStorageController extends Controller
{
    public function index(Request $request)
    {
        $query = FoodStorage::query();

        // Filter op name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter op location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Sorteren
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        if (in_array($sort, ['name', 'location', 'capacity', 'storage_type'])) {
            $query->orderBy($sort, $direction);
        }

        $storages = $query->get();

        return view('foodstorage.index', compact('storages'));
    }

    public function create()
    {
        return view('foodstorage.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:200',
            'capacity' => 'required|integer|min:1',
            'temperature_min' => 'nullable|numeric',
            'temperature_max' => 'nullable|numeric',
            'storage_type' => 'required|in:Refrigerated,Frozen,Dry,Fresh',
        ]);

        FoodStorage::create($validated);

        return redirect()->route('foodstorage.index')->with('success', 'Food storage toegevoegd!');
    }

    public function edit(FoodStorage $foodstorage)
    {
        return view('foodstorage.edit', compact('foodstorage'));
    }

    public function update(Request $request, FoodStorage $foodstorage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:200',
            'capacity' => 'required|integer|min:1',
            'temperature_min' => 'nullable|numeric',
            'temperature_max' => 'nullable|numeric',
            'storage_type' => 'required|in:Refrigerated,Frozen,Dry,Fresh',
        ]);

        $foodstorage->update($validated);

        return redirect()->route('foodstorage.index')->with('success', 'Food storage bijgewerkt!');
    }

    public function destroy(FoodStorage $foodstorage)
    {
        $foodstorage->delete();

        return redirect()->route('foodstorage.index')->with('success', 'Food storage verwijderd!');
    }
}
