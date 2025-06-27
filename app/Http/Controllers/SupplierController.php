<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\Produce;


class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search by supplier type
        if ($request->filled('supplier_type')) {
            $query->where('supplier_type', $request->supplier_type);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $suppliers = $query->orderBy('id', 'desc')->get();

        // Get all unique supplier types for the filter dropdown
        $supplierTypes = Supplier::select('supplier_type')->distinct()->pluck('supplier_type');

        return view('suppliers.index', compact('suppliers', 'supplierTypes'));
    }

    public function create()
    {
        // Logic to show form for creating a new supplier
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:suppliers,email',
            'address' => 'required|string|max:500',
            'supplier_type' => 'required|string|in:Supermarket,Farmer,Wholesaler,Individual',
            'is_actief' => 'sometimes|boolean',
            'opmerking' => 'nullable|string|max:1000',
        ]);

        try {
            // Set default values
            $validated['is_actief'] = $request->has('is_actief') ? true : false;
            $validated['datum_aangemaakt'] = now();
            $validated['datum_gewijzigd'] = now();

            // Create the supplier
            Supplier::create($validated);

            return redirect()->route('suppliers.index')->with('success', 'Leverancier succesvol aangemaakt.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het aanmaken van de leverancier.');
        }
    }

    public function show($id)
    {
        // Logic to show a specific supplier
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.show', compact('id', 'supplier'));
    }

    public function edit($id)
    {
        // Logic to show form for editing a specific supplier
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $id,
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:suppliers,email,' . $id,
            'address' => 'required|string|max:500',
            'supplier_type' => 'required|string|in:Supermarket,Farmer,Wholesaler,Individual',
            'is_actief' => 'sometimes|boolean',
            'opmerking' => 'nullable|string|max:1000',
        ]);

        try {
            // Set default values
            $validated['is_actief'] = $request->has('is_actief') ? true : false;
            $validated['datum_gewijzigd'] = now();

            // Update the supplier
            $supplier->update($validated);

            return redirect()->route('suppliers.index')->with('success', 'Leverancier succesvol bijgewerkt.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het bijwerken van de leverancier.');
        }
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // Check if supplier has any related produce/products using relationship
            if ($supplier->produce()->exists()) {
                return redirect()->route('suppliers.index')
                    ->with('error', 'Leverancier verwijderen is mislukt, er zijn nog bestellingen ingepland voor deze leverancier.');
            }

            // If no related records, proceed with deletion
            $supplier->delete();

            return redirect()->route('suppliers.index')->with('success', 'Leverancier succesvol verwijderd.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Er is een fout opgetreden bij het verwijderen van de leverancier.');
        }
    }
}
