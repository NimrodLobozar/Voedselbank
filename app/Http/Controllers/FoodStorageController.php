<?php
namespace App\Http\Controllers;

use App\Models\FoodStorage;
use App\Models\Produce;
use App\Models\Supplier;
use Illuminate\Http\Request;

/**
 * FoodStorageController
 * 
 * Handles all CRUD operations for food storage management including:
 * - Viewing product inventory
 * - Adding new products to storage
 * - Updating product information
 * - Removing products from storage
 * - Advanced filtering and search functionality
 */
class FoodStorageController extends Controller
{
    /**
     * Display a listing of products in storage with filtering options
     * 
     * This method handles the main inventory overview page with support for:
     * - Barcode searching (both formatted and plain ID)
     * - Product name filtering (partial matches)
     * - Category filtering
     * - Status filtering
     * - Sorting by various fields
     * 
     * @param Request $request - Contains filter parameters
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start building query with eager loading for performance
        // Load relationships to avoid N+1 query problems
        $query = Produce::with(['foodStorage', 'supplier']);

        // Advanced barcode filtering with multiple input formats support
        if ($request->filled('barcode')) {
            $barcodeInput = $request->barcode;
            
            // Check if input is a simple numeric ID (backwards compatibility)
            if (is_numeric($barcodeInput)) {
                $query->where('id', $barcodeInput);
            } else {
                // Try to extract ID from formatted barcode (e.g., "01 000 001 8")
                $extractedId = Produce::getIdFromBarcode($barcodeInput);
                
                if ($extractedId) {
                    // Successfully parsed barcode, search by extracted ID
                    $query->where('id', $extractedId);
                } else {
                    // Fallback: search for barcode pattern in generated barcodes
                    // This complex query generates barcodes on-the-fly and searches for matches
                    $query->whereRaw("REPLACE(CONCAT(
                        CASE category 
                            WHEN 'Groente' THEN '01'
                            WHEN 'Fruit' THEN '02'
                            WHEN 'Vlees' THEN '03'
                            WHEN 'Zuivel' THEN '04'
                            WHEN 'Granen' THEN '05'
                            WHEN 'Conserven' THEN '06'
                            WHEN 'Diepvries' THEN '07'
                            WHEN 'Brood' THEN '08'
                            WHEN 'Overig' THEN '09'
                            ELSE '00'
                        END,
                        ' ',
                        LPAD(id, 6, '0')
                    ), ' ', '') LIKE ?", ['%' . preg_replace('/\s+/', '', $barcodeInput) . '%']);
                }
            }
        }

        // Product name filtering with partial matching (case-insensitive)
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Exact category filtering
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status filtering through food storage relationship
        if ($request->filled('status')) {
            $query->whereHas('foodStorage', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Sorting functionality with security check for allowed columns
        $sort = $request->get('sort', 'id'); // Default sort by ID
        $direction = $request->get('direction', 'asc'); // Default ascending order
        
        // Whitelist of allowed sort columns to prevent SQL injection
        if (in_array($sort, ['id', 'name', 'category', 'amount', 'expiry_date'])) {
            $query->orderBy($sort, $direction);
        }

        // Only show active products (soft delete functionality)
        $query->where('is_actief', true);

        // Execute query and get results
        $produces = $query->get();

        // Prepare data for filter dropdowns
        $categories = Produce::select('category')->distinct()->pluck('category');
        $statuses = [
            'onderweg' => 'Onderweg',
            'in_behandeling' => 'In Behandeling', 
            'geleverd' => 'Geleverd'
        ];

        return view('foodstorage.index', compact('produces', 'categories', 'statuses'));
    }

    /**
     * Show the form for creating a new product
     * 
     * Loads all necessary data for the create form:
     * - Available storage locations
     * - Active suppliers
     * - Product categories
     * - Status options
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Load only active storage locations and suppliers for dropdown options
        $storages = FoodStorage::where('is_actief', true)->get();
        $suppliers = Supplier::where('is_actief', true)->get();
        
        // Define available product categories (could be moved to config in future)
        $categories = ['Groente', 'Fruit', 'Vlees', 'Zuivel', 'Granen', 'Conserven', 'Diepvries', 'Brood', 'Overig'];
        
        // Define available status options
        $statuses = [
            'onderweg' => 'Onderweg',
            'in_behandeling' => 'In Behandeling',
            'geleverd' => 'Geleverd'
        ];
        
        return view('foodstorage.create', compact('storages', 'suppliers', 'categories', 'statuses'));
    }

    /**
     * Store a newly created product in storage
     * 
     * Validates input data and creates new product with:
     * - Duplicate checking per storage location
     * - Business rule validation (dates, amounts, etc.)
     * - Automatic timestamp management
     * - Storage status updating
     * 
     * @param Request $request - Form data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Comprehensive validation with custom duplicate checking
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'food_storage_id' => 'required|exists:food_storage,id',
            'name' => [
                'required',
                'string',
                'max:100',
                // Custom validation: check for duplicate product names per location
                function ($attribute, $value, $fail) use ($request) {
                    $existing = Produce::where('name', $value)
                        ->where('food_storage_id', $request->food_storage_id)
                        ->where('is_actief', true)
                        ->first();
                    
                    if ($existing) {
                        // Get location name for better error message
                        $locationName = FoodStorage::find($request->food_storage_id)?->name ?? 'deze locatie';
                        $fail('Er bestaat al een product met deze naam op ' . $locationName . '.');
                    }
                }
            ],
            'brand' => 'nullable|string|max:100',
            'category' => 'required|in:Groente,Fruit,Vlees,Zuivel,Granen,Conserven,Diepvries,Brood,Overig',
            'expiry_date' => 'required|date|after:today', // Must be in future
            'received_date' => 'required|date|before_or_equal:today', // Cannot be in future
            'amount' => 'required|integer|min:1', // Must have at least 1 item
            'unit' => 'required|string|max:20',
            'weight_per_unit' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:onderweg,in_behandeling,geleverd',
        ]);

        // Add required system timestamps
        $validated['datum_aangemaakt'] = now();
        $validated['datum_gewijzigd'] = now();
        
        // Update storage location status if provided
        if (isset($validated['status'])) {
            $foodStorage = FoodStorage::find($validated['food_storage_id']);
            if ($foodStorage) {
                $foodStorage->update([
                    'status' => $validated['status'],
                    'datum_gewijzigd' => now()
                ]);
            }
            // Remove status from product data (it belongs to storage, not product)
            unset($validated['status']);
        }
        
        // Create the new product record
        Produce::create($validated);

        // Redirect with success message
        return redirect()->route('foodstorage.index')
            ->with('success', 'Product succesvol toegevoegd aan voorraad!');
    }

    /**
     * Display the specified product details
     * 
     * Shows comprehensive product information including:
     * - All product details
     * - Supplier information
     * - Storage location details
     * - Calculated fields (total weight, days until expiry)
     * 
     * @param Produce $foodstorage - The product to display
     * @return \Illuminate\View\View
     */
    public function show(Produce $foodstorage)
    {
        // Eager load relationships to avoid additional queries
        $foodstorage->load(['supplier', 'foodStorage']);
        
        return view('foodstorage.show', compact('foodstorage'));
    }

    /**
     * Show the form for editing the specified product
     * 
     * Pre-populates form with current product data and loads:
     * - Available storage locations
     * - Active suppliers
     * - Category options
     * - Status options
     * 
     * @param Produce $foodstorage - The product to edit
     * @return \Illuminate\View\View
     */
    public function edit(Produce $foodstorage)
    {
        // Load reference data for form dropdowns
        $storages = FoodStorage::where('is_actief', true)->get();
        $suppliers = Supplier::where('is_actief', true)->get();
        $categories = ['Groente', 'Fruit', 'Vlees', 'Zuivel', 'Granen', 'Conserven', 'Diepvries', 'Brood', 'Overig'];
        $statuses = [
            'onderweg' => 'Onderweg',
            'in_behandeling' => 'In Behandeling',
            'geleverd' => 'Geleverd'
        ];
        
        return view('foodstorage.edit', compact('foodstorage', 'storages', 'suppliers', 'categories', 'statuses'));
    }

    /**
     * Update the specified product in storage
     * 
     * Validates and updates product with:
     * - Duplicate checking (excluding current product)
     * - Business rule validation
     * - Timestamp management
     * - Storage status synchronization
     * 
     * @param Request $request - Updated form data
     * @param Produce $foodstorage - The product to update
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Produce $foodstorage)
    {
        // Validation with duplicate checking that excludes current product
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'food_storage_id' => 'required|exists:food_storage,id',
            'name' => [
                'required',
                'string',
                'max:100',
                // Custom validation: allow keeping same name, but prevent duplicates with other products
                function ($attribute, $value, $fail) use ($request, $foodstorage) {
                    $existing = Produce::where('name', $value)
                        ->where('food_storage_id', $request->food_storage_id)
                        ->where('id', '!=', $foodstorage->id) // Exclude current product from check
                        ->where('is_actief', true)
                        ->first();
                    
                    if ($existing) {
                        $locationName = FoodStorage::find($request->food_storage_id)?->name ?? 'deze locatie';
                        $fail('Er bestaat al een product met deze naam op ' . $locationName . '.');
                    }
                }
            ],
            'brand' => 'nullable|string|max:100',
            'category' => 'required|in:Groente,Fruit,Vlees,Zuivel,Granen,Conserven,Diepvries,Brood,Overig',
            'expiry_date' => 'required|date', // More lenient for updates (can be past)
            'received_date' => 'required|date',
            'amount' => 'required|integer|min:0', // Allow 0 for updates (out of stock)
            'unit' => 'required|string|max:20',
            'weight_per_unit' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:onderweg,in_behandeling,geleverd',
        ]);

        // Update modification timestamp
        $validated['datum_gewijzigd'] = now();
        
        // Sync storage location status if provided
        if (isset($validated['status'])) {
            $foodStorage = FoodStorage::find($validated['food_storage_id']);
            if ($foodStorage) {
                $foodStorage->update([
                    'status' => $validated['status'],
                    'datum_gewijzigd' => now()
                ]);
            }
            // Remove status from product data
            unset($validated['status']);
        }
        
        // Update the product record
        $foodstorage->update($validated);

        // Redirect with success message
        return redirect()->route('foodstorage.index')
            ->with('success', 'Product bijgewerkt!');
    }

    /**
     * Remove the specified product from storage (soft delete)
     * 
     * Performs multiple business rule checks before deletion:
     * 1. Cannot delete products with "Onderweg" status
     * 2. Cannot delete products already included in food packages
     * 3. Sets is_actief to false instead of hard delete
     * 
     * @param Produce $foodstorage - The product to remove
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Produce $foodstorage)
    {
        // Business Rule 1: Cannot delete products that are "onderweg" (in transit)
        if ($foodstorage->foodStorage && $foodstorage->foodStorage->status === 'onderweg') {
            return redirect()->route('foodstorage.index')
                ->with('error', 'Product kan niet verwijderd worden: status is "Onderweg".');
        }
        
        // Business Rule 2: Cannot delete products already included in food packages
        // This maintains data integrity and prevents orphaned references
        $inFoodPackage = \DB::table('food_package_produce')
            ->where('produce_id', $foodstorage->id)
            ->exists();

        if ($inFoodPackage) {
            return redirect()->route('foodstorage.index')
                ->with('error', 'Product kan niet verwijderd worden: al opgenomen in voedselpakket.');
        }

        // Perform soft delete by setting is_actief to false
        // This preserves data for historical/audit purposes
        $foodstorage->update(['is_actief' => false]);

        // Redirect with success message
        return redirect()->route('foodstorage.index')
            ->with('success', 'Product verwijderd uit voorraad!');
    }
}
