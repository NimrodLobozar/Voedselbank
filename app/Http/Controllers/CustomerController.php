<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {
        try {
            $customers = Customer::getAllCustomers();
            
            // Apply search filter if provided
            if ($request->has('search') && !empty($request->search)) {
                $search = strtolower($request->search);
                $customers = array_filter($customers, function ($customer) use ($search) {
                    return str_contains(strtolower($customer->full_name), $search) ||
                           str_contains(strtolower($customer->email), $search) ||
                           str_contains(strtolower($customer->full_address), $search);
                });
            }

            return view('customers.index', compact('customers'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er is een fout opgetreden bij het ophalen van klanten.']);
        }
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:20',
            'last_name' => 'required|string|max:50',
            'birth_date' => 'required|date|before:today',
            'street' => 'required|string|max:100',
            'house_number' => 'required|string|max:10',
            'addition' => 'nullable|string|max:10',
            'postal_code' => 'required|string|max:7',
            'city' => 'required|string|max:50',
            'mobile' => 'required|string|max:20',
            'customer_email' => 'required|string|email|max:100',
            'household_size' => 'required|integer|min:1|max:20',
            'income' => 'nullable|numeric|min:0|max:999999.99',
            'registration_date' => 'required|date',
            'opmerking' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Create customer record
            $customer = Customer::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'birth_date' => $validated['birth_date'],
                'street' => $validated['street'],
                'house_number' => $validated['house_number'],
                'addition' => $validated['addition'],
                'postal_code' => $validated['postal_code'],
                'city' => $validated['city'],
                'mobile' => $validated['mobile'],
                'email' => $validated['customer_email'],
                'household_size' => $validated['household_size'],
                'income' => $validated['income'],
                'registration_date' => $validated['registration_date'],
                'opmerking' => $validated['opmerking'],
                'is_actief' => true,
            ]);

            DB::commit();

            return redirect()->route('customers.index')
                           ->with('success', 'Klant is succesvol aangemaakt.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->withErrors(['error' => 'Er is een fout opgetreden bij het aanmaken van de klant.']);
        }
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        try {
            $customerData = Customer::getCustomerById($customer->id);
            
            if (!$customerData) {
                return redirect()->route('customers.index')
                               ->withErrors(['error' => 'Klant niet gevonden.']);
            }

            return view('customers.show', compact('customer', 'customerData'));
        } catch (\Exception $e) {
            return redirect()->route('customers.index')
                           ->withErrors(['error' => 'Er is een fout opgetreden bij het ophalen van klantgegevens.']);
        }
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:20',
            'last_name' => 'required|string|max:50',
            'birth_date' => 'required|date|before:today',
            'street' => 'required|string|max:100',
            'house_number' => 'required|string|max:10',
            'addition' => 'nullable|string|max:10',
            'postal_code' => 'required|string|max:7',
            'city' => 'required|string|max:50',
            'mobile' => 'required|string|max:20',
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique('customer', 'email')->ignore($customer->id),
            ],
            'household_size' => 'required|integer|min:1|max:20',
            'income' => 'nullable|numeric|min:0|max:999999.99',
            'registration_date' => 'required|date',
            'is_actief' => 'boolean',
            'opmerking' => 'nullable|string|max:255',
        ]);

        try {
            $customer->update($validated);

            return redirect()->route('customers.show', $customer)
                           ->with('success', 'Klantgegevens zijn succesvol bijgewerkt.');

        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Er is een fout opgetreden bij het bijwerken van de klantgegevens.']);
        }
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            // Soft delete by setting is_actief to false instead of actual deletion
            $customer->update(['is_actief' => false]);

            return redirect()->route('customers.index')
                           ->with('success', 'Klant is succesvol gedeactiveerd.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er is een fout opgetreden bij het deactiveren van de klant.']);
        }
    }

    /**
     * Restore a deactivated customer.
     */
    public function restore(Customer $customer)
    {
        try {
            $customer->update(['is_actief' => true]);

            return redirect()->route('customers.show', $customer)
                           ->with('success', 'Klant is succesvol geactiveerd.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er is een fout opgetreden bij het activeren van de klant.']);
        }
    }
}
