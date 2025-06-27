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
        // Validate search inputs
        $request->validate([
            'search' => 'nullable|string|max:255',
            'supplier_type' => 'nullable|string|in:Supermarket,Farmer,Wholesaler,Individual',
            'order_status' => 'nullable|string|in:onderweg,in_behandeling,geleverd,actief',
        ]);

        $query = Supplier::query();

        // Search by supplier type
        if ($request->filled('supplier_type')) {
            $query->where('supplier_type', $request->supplier_type);
        }

        // Search by name
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        // Filter by order status
        if ($request->filled('order_status')) {
            $orderStatus = $request->order_status;

            if ($orderStatus === 'actief') {
                // Show suppliers WITHOUT active orders (no onderweg or in_behandeling status)
                $query->whereDoesntHave('foodStorages', function ($q) {
                    $q->whereIn('status', ['onderweg', 'in_behandeling']);
                });
            } else {
                // Show suppliers with specific order status
                $query->whereHas('foodStorages', function ($q) use ($orderStatus) {
                    $q->where('status', $orderStatus);
                });
            }
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
            'name' => 'required|string|min:2|max:255|unique:suppliers,name',
            'contact_person' => 'required|string|min:2|max:255',
            'phone' => 'required|string|min:10|max:20|regex:/^[0-9\+\-\s\(\)]+$/',
            'email' => 'required|email|max:255|unique:suppliers,email',
            'address' => 'required|string|min:5|max:500',
            'supplier_type' => 'required|string|in:Supermarket,Farmer,Wholesaler,Individual',
            'is_actief' => 'sometimes|boolean',
            'opmerking' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Bedrijfsnaam is verplicht.',
            'name.min' => 'Bedrijfsnaam moet minimaal 2 karakters bevatten.',
            'name.max' => 'Bedrijfsnaam mag maximaal 255 karakters bevatten.',
            'name.unique' => 'Een leverancier met deze naam bestaat al.',
            'contact_person.required' => 'Contactpersoon is verplicht.',
            'contact_person.min' => 'Contactpersoon moet minimaal 2 karakters bevatten.',
            'contact_person.max' => 'Contactpersoon mag maximaal 255 karakters bevatten.',
            'phone.required' => 'Telefoonnummer is verplicht.',
            'phone.min' => 'Telefoonnummer moet minimaal 10 karakters bevatten.',
            'phone.max' => 'Telefoonnummer mag maximaal 20 karakters bevatten.',
            'phone.regex' => 'Telefoonnummer bevat ongeldige karakters.',
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'E-mailadres moet geldig zijn.',
            'email.unique' => 'Een leverancier met dit e-mailadres bestaat al.',
            'address.required' => 'Adres is verplicht.',
            'address.min' => 'Adres moet minimaal 5 karakters bevatten.',
            'address.max' => 'Adres mag maximaal 500 karakters bevatten.',
            'supplier_type.required' => 'Leverancier type is verplicht.',
            'supplier_type.in' => 'Geselecteerd leverancier type is ongeldig.',
            'opmerking.max' => 'Opmerking mag maximaal 1000 karakters bevatten.',
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
            'name' => 'required|string|min:2|max:255|unique:suppliers,name,' . $id,
            'contact_person' => 'required|string|min:2|max:255',
            'phone' => 'required|string|min:10|max:20|regex:/^[0-9\+\-\s\(\)]+$/',
            'email' => 'required|email|max:255|unique:suppliers,email,' . $id,
            'address' => 'required|string|min:5|max:500',
            'supplier_type' => 'required|string|in:Supermarket,Farmer,Wholesaler,Individual',
            'is_actief' => 'sometimes|boolean',
            'opmerking' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Bedrijfsnaam is verplicht.',
            'name.min' => 'Bedrijfsnaam moet minimaal 2 karakters bevatten.',
            'name.max' => 'Bedrijfsnaam mag maximaal 255 karakters bevatten.',
            'name.unique' => 'Een leverancier met deze naam bestaat al.',
            'contact_person.required' => 'Contactpersoon is verplicht.',
            'contact_person.min' => 'Contactpersoon moet minimaal 2 karakters bevatten.',
            'contact_person.max' => 'Contactpersoon mag maximaal 255 karakters bevatten.',
            'phone.required' => 'Telefoonnummer is verplicht.',
            'phone.min' => 'Telefoonnummer moet minimaal 10 karakters bevatten.',
            'phone.max' => 'Telefoonnummer mag maximaal 20 karakters bevatten.',
            'phone.regex' => 'Telefoonnummer bevat ongeldige karakters.',
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'E-mailadres moet geldig zijn.',
            'email.unique' => 'Een leverancier met dit e-mailadres bestaat al.',
            'address.required' => 'Adres is verplicht.',
            'address.min' => 'Adres moet minimaal 5 karakters bevatten.',
            'address.max' => 'Adres mag maximaal 500 karakters bevatten.',
            'supplier_type.required' => 'Leverancier type is verplicht.',
            'supplier_type.in' => 'Geselecteerd leverancier type is ongeldig.',
            'opmerking.max' => 'Opmerking mag maximaal 1000 karakters bevatten.',
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

            // Check if supplier has any active orders (onderweg or in_behandeling)
            if ($supplier->hasActiveOrders()) {
                return redirect()->route('suppliers.index')
                    ->with('error', 'Leverancier kan niet worden verwijderd. Er zijn nog actieve bestellingen (onderweg of in behandeling) van deze leverancier. Leveranciers met alleen geleverde bestellingen kunnen wel worden verwijderd.');
            }

            // If no active orders, proceed with deletion
            $supplier->delete();

            return redirect()->route('suppliers.index')->with('success', 'Leverancier succesvol verwijderd.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Er is een fout opgetreden bij het verwijderen van de leverancier.');
        }
    }
}
