<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;


class SupplierController extends Controller
{
    public function index()
    {
        // Logic to list all suppliers
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        // Logic to show form for creating a new supplier
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        // Logic to store a new supplier
        // Validate and save the supplier data
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
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
        return view('suppliers.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific supplier
        // Validate and update the supplier data
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        // Logic to delete a specific supplier
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
