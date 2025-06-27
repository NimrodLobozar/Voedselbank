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
        // We tonen producten (produce) voor voorraad beheer, niet storage
        $query = Produce::with(['foodStorage', 'supplier']);

        // Filter op streepjescode (ID als barcode)
        if ($request->filled('barcode')) {
            $query->where('id', 'like', '%' . $request->barcode . '%');
        }

        // Filter op productnaam
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter op categorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter op status
        if ($request->filled('status')) {
            $query->whereHas('foodStorage', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Sorteren op alle eigenschappen - standaard op ID vanaf 1
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');
        if (in_array($sort, ['id', 'name', 'category', 'amount', 'expiry_date'])) {
            $query->orderBy($sort, $direction);
        }

        // Alleen actieve producten
        $query->where('is_actief', true);

        $produces = $query->get();

        // Categories voor filter dropdown
        $categories = Produce::select('category')->distinct()->pluck('category');
        $statuses = ['onderweg' => 'Onderweg', 'in_behandeling' => 'In Behandeling', 'geleverd' => 'Geleverd'];

        return view('foodstorage.index', compact('produces', 'categories', 'statuses'));
    }

    public function create()
    {
        $storages = FoodStorage::where('is_actief', true)->get();
        $suppliers = Supplier::where('is_actief', true)->get();
        $categories = ['Groente', 'Fruit', 'Vlees', 'Zuivel', 'Granen', 'Conserven', 'Diepvries', 'Brood', 'Overig'];
        $statuses = ['onderweg' => 'Onderweg', 'in_behandeling' => 'In Behandeling', 'geleverd' => 'Geleverd'];
        
        return view('foodstorage.create', compact('storages', 'suppliers', 'categories', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'food_storage_id' => 'required|exists:food_storage,id',
            'name' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'category' => 'required|in:Groente,Fruit,Vlees,Zuivel,Granen,Conserven,Diepvries,Brood,Overig',
            'expiry_date' => 'required|date|after:today',
            'received_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'weight_per_unit' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:onderweg,in_behandeling,geleverd',
        ]);

        // Add required datetime fields
        $validated['datum_aangemaakt'] = now();
        $validated['datum_gewijzigd'] = now();
        
        // If creating food storage record, set default status
        if (isset($validated['status'])) {
            $foodStorage = FoodStorage::find($validated['food_storage_id']);
            if ($foodStorage) {
                $foodStorage->update([
                    'status' => $validated['status'],
                    'datum_gewijzigd' => now()
                ]);
            }
            unset($validated['status']); // Remove from produce data
        }
        
        Produce::create($validated);

        return redirect()->route('foodstorage.index')->with('success', 'Product toegevoegd aan voorraad!');
    }

    public function edit(Produce $foodstorage)
    {
        $storages = FoodStorage::where('is_actief', true)->get();
        $suppliers = Supplier::where('is_actief', true)->get();
        $categories = ['Groente', 'Fruit', 'Vlees', 'Zuivel', 'Granen', 'Conserven', 'Diepvries', 'Brood', 'Overig'];
        $statuses = ['onderweg' => 'Onderweg', 'in_behandeling' => 'In Behandeling', 'geleverd' => 'Geleverd'];
        
        return view('foodstorage.edit', compact('foodstorage', 'storages', 'suppliers', 'categories', 'statuses'));
    }

    public function update(Request $request, Produce $foodstorage)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'food_storage_id' => 'required|exists:food_storage,id',
            'name' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'category' => 'required|in:Groente,Fruit,Vlees,Zuivel,Granen,Conserven,Diepvries,Brood,Overig',
            'expiry_date' => 'required|date',
            'received_date' => 'required|date',
            'amount' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'weight_per_unit' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:onderweg,in_behandeling,geleverd',
        ]);

        // Add required datetime field for updates
        $validated['datum_gewijzigd'] = now();
        
        // Update food storage status if provided
        if (isset($validated['status'])) {
            $foodStorage = FoodStorage::find($validated['food_storage_id']);
            if ($foodStorage) {
                $foodStorage->update([
                    'status' => $validated['status'],
                    'datum_gewijzigd' => now()
                ]);
            }
            unset($validated['status']); // Remove from produce data
        }
        
        $foodstorage->update($validated);

        return redirect()->route('foodstorage.index')->with('success', 'Product bijgewerkt!');
    }

    public function destroy(Produce $foodstorage)
    {
        // Check of product al in voedselpakket zit
        $inFoodPackage = \DB::table('food_package_produce')
            ->where('produce_id', $foodstorage->id)
            ->exists();

        if ($inFoodPackage) {
            return redirect()->route('foodstorage.index')
                ->with('error', 'Product kan niet verwijderd worden: al opgenomen in voedselpakket.');
        }

        $foodstorage->update(['is_actief' => false]);

        return redirect()->route('foodstorage.index')->with('success', 'Product verwijderd uit voorraad!');
    }
}
