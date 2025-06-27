<?php
namespace App\Http\Controllers;

use App\Models\FoodStorage;
use App\Models\Produce;
use App\Models\Supplier;
use Illuminate\Http\Request;

class FoodStorageController extends Controller
{
    public function index(Request $request)
    {
        $query = FoodStorage::with(['produces', 'expiringSoonProduces']);

        // Filter op name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter op location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filter op storage type
        if ($request->filled('storage_type')) {
            $query->where('storage_type', $request->storage_type);
        }

        // Sorteren
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        if (in_array($sort, ['name', 'location', 'capacity', 'storage_type'])) {
            $query->orderBy($sort, $direction);
        }

        $storages = $query->get();

        // Stats voor dashboard
        $stats = [
            'total_storages' => FoodStorage::where('is_actief', true)->count(),
            'total_products' => Produce::where('is_actief', true)->count(),
            'expiring_soon' => Produce::where('expiry_date', '<=', now()->addDays(7))->where('is_actief', true)->count(),
            'nearly_full_storages' => $storages->filter(function($storage) {
                return $storage->occupancy_percentage > 80;
            })->count()
        ];

        return view('foodstorage.index', compact('storages', 'stats'));
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

    public function show(FoodStorage $foodstorage)
    {
        $foodstorage->load(['produces.supplier']);
        
        // Producten in deze storage
        $produces = $foodstorage->produces()
            ->with('supplier')
            ->where('is_actief', true)
            ->orderBy('expiry_date', 'asc')
            ->get();

        return view('foodstorage.show', compact('foodstorage', 'produces'));
    }
}
