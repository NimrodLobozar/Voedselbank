<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Models\Produce;
use Exception;

/**
 * Controller for managing suppliers (leveranciers).
 * Handles CRUD operations and filtering/searching functionality.
 *
 * @package App\Http\Controllers
 * @author Voedselbank Development Team
 * @version 1.0
 */
class SupplierController extends Controller
{
    // Constants for supplier types and order statuses to improve maintainability
    const SUPPLIER_TYPES = ['Supermarket', 'Farmer', 'Wholesaler', 'Individual'];
    const ORDER_STATUSES = ['onderweg', 'in_behandeling', 'geleverd', 'actief'];
    const ACTIVE_ORDER_STATUSES = ['onderweg', 'in_behandeling'];

    /**
     * Display a listing of suppliers with optional filtering.
     *
     * @param Request $request The HTTP request containing filter parameters
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Validate search inputs with improved validation rules
        $validatedData = $request->validate([
            'search' => 'nullable|string|max:255|min:1',
            'supplier_type' => 'nullable|string|in:' . implode(',', self::SUPPLIER_TYPES),
            'order_status' => 'nullable|string|in:' . implode(',', self::ORDER_STATUSES),
        ]);

        try {
            $query = Supplier::query();

            // Apply filters in a more structured way
            $this->applyFilters($query, $validatedData);

            // Get suppliers with optimized ordering
            $suppliers = $query->orderBy('name', 'asc')->get();

            // Get all unique supplier types for the filter dropdown
            $supplierTypes = $this->getAvailableSupplierTypes();

            return view('suppliers.index', compact('suppliers', 'supplierTypes'));
        } catch (Exception $e) {
            Log::error('Error loading suppliers index: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Er is een fout opgetreden bij het laden van de leveranciers.');
        }
    }

    /**
     * Apply filters to the supplier query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     */
    private function applyFilters($query, array $filters): void
    {
        // Filter by supplier type
        if (!empty($filters['supplier_type'])) {
            $query->where('supplier_type', $filters['supplier_type']);
        }

        // Filter by search term
        if (!empty($filters['search'])) {
            $searchTerm = trim($filters['search']);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('contact_person', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Filter by order status
        if (!empty($filters['order_status'])) {
            $this->applyOrderStatusFilter($query, $filters['order_status']);
        }
    }

    /**
     * Apply order status filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $orderStatus
     * @return void
     */
    private function applyOrderStatusFilter($query, string $orderStatus): void
    {
        if ($orderStatus === 'actief') {
            // Show suppliers WITHOUT active orders
            $query->whereDoesntHave('foodStorages', function ($q) {
                $q->whereIn('status', self::ACTIVE_ORDER_STATUSES);
            });
        } else {
            // Show suppliers with specific order status
            $query->whereHas('foodStorages', function ($q) use ($orderStatus) {
                $q->where('status', $orderStatus);
            });
        }
    }

    /**
     * Get available supplier types from the database.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getAvailableSupplierTypes()
    {
        return Supplier::select('supplier_type')
            ->distinct()
            ->whereNotNull('supplier_type')
            ->orderBy('supplier_type')
            ->pluck('supplier_type');
    }

    /**
     * Show the form for creating a new supplier.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     *
     * @param SupplierRequest $request The validated HTTP request containing supplier data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SupplierRequest $request)
    {
        // Get validated data from the custom form request
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Add timestamps
            $validated['datum_aangemaakt'] = now();
            $validated['datum_gewijzigd'] = now();

            // Create the supplier
            $supplier = Supplier::create($validated);

            DB::commit();

            Log::info('Supplier created successfully', [
                'supplier_id' => $supplier->id,
                'name' => $supplier->name,
                'created_by' => Auth::id() ?? 'system'
            ]);

            return redirect()
                ->route('suppliers.show', $supplier)
                ->with('success', 'Leverancier "' . $supplier->name . '" succesvol aangemaakt.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error creating supplier: ' . $e->getMessage(), [
                'request_data' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het aanmaken van de leverancier. Probeer het opnieuw.');
        }
    }

    /**
     * Display the specified supplier.
     *
     * @param int $id The supplier ID
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $supplier = Supplier::with(['produce', 'foodStorages'])->findOrFail($id);

            return view('suppliers.show', compact('supplier'));
        } catch (Exception $e) {
            Log::error('Error loading supplier details: ' . $e->getMessage(), [
                'supplier_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('suppliers.index')
                ->with('error', 'Leverancier niet gevonden of er is een fout opgetreden.');
        }
    }

    /**
     * Show the form for editing the specified supplier.
     *
     * @param int $id The supplier ID
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            return view('suppliers.edit', compact('supplier'));
        } catch (Exception $e) {
            Log::error('Error loading supplier for edit: ' . $e->getMessage(), [
                'supplier_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('suppliers.index')
                ->with('error', 'Leverancier niet gevonden of er is een fout opgetreden.');
        }
    }

    /**
     * Update the specified supplier in storage.
     *
     * @param SupplierRequest $request The validated HTTP request containing updated supplier data
     * @param int $id The supplier ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SupplierRequest $request, $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Leverancier niet gevonden.');
        }

        // Get validated data from the custom form request
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Add timestamp for modification
            $validated['datum_gewijzigd'] = now();

            // Update the supplier
            $supplier->update($validated);

            DB::commit();

            Log::info('Supplier updated successfully', [
                'supplier_id' => $supplier->id,
                'name' => $supplier->name,
                'updated_by' => Auth::id() ?? 'system'
            ]);

            return redirect()
                ->route('suppliers.show', $supplier)
                ->with('success', 'Leverancier "' . $supplier->name . '" succesvol bijgewerkt.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error updating supplier: ' . $e->getMessage(), [
                'supplier_id' => $id,
                'request_data' => $request->except(['_token', '_method']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het bijwerken van de leverancier. Probeer het opnieuw.');
        }
    }

    /**
     * Remove the specified supplier from storage.
     *
     * @param int $id The supplier ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $supplier = Supplier::findOrFail($id);

            // Business rule: Check if supplier has any active orders
            if ($supplier->hasActiveOrders()) {
                return redirect()->route('suppliers.index')
                    ->with('error', 'Leverancier "' . $supplier->name . '" kan niet worden verwijderd. Er zijn nog actieve bestellingen (onderweg of in behandeling). Leveranciers met alleen geleverde bestellingen kunnen wel worden verwijderd.');
            }

            $supplierName = $supplier->name; // Store name before deletion
            $supplier->delete();

            DB::commit();

            Log::info('Supplier deleted successfully', [
                'supplier_id' => $id,
                'name' => $supplierName,
                'deleted_by' => Auth::id() ?? 'system'
            ]);

            return redirect()->route('suppliers.index')
                ->with('success', 'Leverancier "' . $supplierName . '" succesvol verwijderd.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error deleting supplier: ' . $e->getMessage(), [
                'supplier_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('suppliers.index')
                ->with('error', 'Er is een fout opgetreden bij het verwijderen van de leverancier. Probeer het opnieuw.');
        }
    }
}
