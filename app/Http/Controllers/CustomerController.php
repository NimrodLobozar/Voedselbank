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
            $customers = DB::select('CALL sp_GetCustomers()');
            
            // Convert to collection for easier filtering
            $customers = collect($customers);
            
            // Apply name search filter
            if ($request->filled('name_search')) {
                $search = strtolower(trim($request->name_search));
                $customers = $customers->filter(function ($customer) use ($search) {
                    return str_contains(strtolower($customer->full_name), $search) ||
                           str_contains(strtolower($customer->full_address ?? ''), $search);
                });
            }
            
            // Apply status filter
            if ($request->filled('status_filter') && $request->status_filter !== '') {
                $status = (bool) $request->status_filter;
                $customers = $customers->filter(function ($customer) use ($status) {
                    return (bool) $customer->is_actief === $status;
                });
            }
            
            // Apply household size filter
            if ($request->filled('household_filter') && $request->household_filter !== '') {
                $householdFilter = $request->household_filter;
                $customers = $customers->filter(function ($customer) use ($householdFilter) {
                    if ($householdFilter === '5+') {
                        return $customer->household_size >= 5;
                    }
                    return $customer->household_size == $householdFilter;
                });
            }

            // Apply dietary filter
            if ($request->filled('dietary_filter') && $request->dietary_filter !== '') {
                $dietaryFilter = $request->dietary_filter;
                $customers = $customers->filter(function ($customer) use ($dietaryFilter) {
                    switch ($dietaryFilter) {
                        case 'vegan':
                            return (bool) $customer->is_vegan;
                        case 'vegetarian':
                            return (bool) $customer->is_vegetarian;
                        case 'no_pork':
                            return (bool) $customer->no_pork;
                        case 'allergies':
                            // Check if customer has allergies - this would need to be added to the stored procedure
                            // For now, we'll assume the SP includes allergy information
                            return isset($customer->has_allergies) && (bool) $customer->has_allergies;
                        default:
                            return true;
                    }
                });
            }

            // Convert back to array for the view
            $customers = $customers->values()->all();

            return view('customers.index', compact('customers'));
        } catch (\Exception $e) {
            return view('customers.index', ['customers' => []])
                ->withErrors(['error' => 'Er is een fout opgetreden bij het ophalen van klanten: ' . $e->getMessage()]);
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
        $validated = $this->validateCustomerData($request);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Customer::create(array_merge(
                array_except($validated, ['name', 'email', 'password', 'password_confirmation']),
                ['user_id' => $user->id, 'email' => $validated['customer_email'], 'is_actief' => true]
            ));

            DB::commit();
            return redirect()->route('customers.index')->with('success', 'Klant is succesvol aangemaakt.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Er is een fout opgetreden bij het aanmaken van de klant.']);
        }
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        try {
            $customerData = DB::select('CALL sp_GetCustomerById(?)', [$customer->id]);
            $packageHistory = DB::select('CALL sp_GetCustomerPackageHistory(?)', [$customer->id]);
            
            if (empty($customerData)) {
                return redirect()->route('customers.index')->withErrors(['error' => 'Klant niet gevonden.']);
            }

            // Get customer allergies
            $allergies = DB::table('customer_allergy')
                ->join('allergy', 'customer_allergy.allergy_id', '=', 'allergy.id')
                ->where('customer_allergy.customer_id', $customer->id)
                ->where('customer_allergy.is_actief', true)
                ->select('allergy.allergy_name', 'customer_allergy.severity')
                ->get();

            // Get custom allergies
            $customAllergies = DB::table('custom_allergy')
                ->where('customer_id', $customer->id)
                ->where('is_actief', true)
                ->select('allergy_name', 'severity')
                ->get();

            return view('customers.show', compact('customer', 'customerData', 'packageHistory', 'allergies', 'customAllergies'));
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->withErrors(['error' => 'Er is een fout opgetreden bij het ophalen van klantgegevens.']);
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
        $validated = $this->validateCustomerData($request, $customer);

        try {
            $customer->update($validated);
            return redirect()->route('customers.show', $customer)->with('success', 'Klantgegevens zijn succesvol bijgewerkt.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Er is een fout opgetreden bij het bijwerken van de klantgegevens.']);
        }
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            $customer->update(['is_actief' => false]);
            return redirect()->route('customers.index')->with('success', 'Klant is succesvol gedeactiveerd.');
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
            return redirect()->route('customers.show', $customer)->with('success', 'Klant is succesvol geactiveerd.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er is een fout opgetreden bij het activeren van de klant.']);
        }
    }

    /**
     * Validate customer data.
     */
    private function validateCustomerData(Request $request, Customer $customer = null)
    {
        $rules = [
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
            'adults_count' => 'required|integer|min:0|max:20',
            'children_count' => 'required|integer|min:0|max:20',
            'babies_count' => 'required|integer|min:0|max:20',
            'income' => 'nullable|numeric|min:0|max:999999.99',
            'registration_date' => 'required|date',
            'no_pork' => 'boolean',
            'is_vegan' => 'boolean',
            'is_vegetarian' => 'boolean',
            'opmerking' => 'nullable|string|max:255',
        ];

        if (!$customer) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|string|email|max:255|unique:users';
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['email'] = ['required', 'string', 'email', 'max:100', Rule::unique('customer', 'email')->ignore($customer->id)];
            $rules['is_actief'] = 'boolean';
        }

        return $request->validate($rules);
    }
}
