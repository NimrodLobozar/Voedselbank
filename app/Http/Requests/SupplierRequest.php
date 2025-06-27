<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Supplier;

/**
 * Form Request for storing/updating Supplier data.
 * 
 * Handles validation rules and authorization for supplier operations.
 * Provides centralized validation logic with proper error messages.
 *
 * @package App\Http\Requests
 * @author Voedselbank Development Team
 * @version 1.0
 */
class SupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Add proper authorization logic here if needed
        // For now, allow all authenticated users
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|min:2|max:255',
            'contact_person' => 'required|string|min:2|max:255',
            'phone' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^[0-9\+\-\s\(\)]+$/'
            ],
            'email' => app()->environment('testing') ? 'required|email|max:255' : 'required|email:rfc,dns|max:255',
            'address' => 'required|string|min:5|max:500',
            'supplier_type' => 'required|string|in:' . implode(',', Supplier::SUPPLIER_TYPES),
            'is_actief' => 'sometimes|boolean',
            'opmerking' => 'nullable|string|max:1000',
        ];

        // Add unique validation rules based on the HTTP method
        if ($this->isMethod('POST')) {
            // For creating new suppliers
            $rules['name'] .= '|unique:suppliers,name';
            $rules['email'] .= '|unique:suppliers,email';
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // For updating existing suppliers
            $supplierId = $this->route('supplier');
            $rules['name'] .= '|unique:suppliers,name,' . $supplierId;
            $rules['email'] .= '|unique:suppliers,email,' . $supplierId;
        }

        return $rules;
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Name field messages
            'name.required' => 'Bedrijfsnaam is verplicht.',
            'name.min' => 'Bedrijfsnaam moet minimaal 2 karakters bevatten.',
            'name.max' => 'Bedrijfsnaam mag maximaal 255 karakters bevatten.',
            'name.unique' => 'Een leverancier met deze naam bestaat al.',

            // Contact person messages
            'contact_person.required' => 'Contactpersoon is verplicht.',
            'contact_person.min' => 'Contactpersoon moet minimaal 2 karakters bevatten.',
            'contact_person.max' => 'Contactpersoon mag maximaal 255 karakters bevatten.',

            // Phone field messages
            'phone.required' => 'Telefoonnummer is verplicht.',
            'phone.min' => 'Telefoonnummer moet minimaal 10 karakters bevatten.',
            'phone.max' => 'Telefoonnummer mag maximaal 20 karakters bevatten.',
            'phone.regex' => 'Telefoonnummer bevat ongeldige karakters. Gebruik alleen cijfers, spaties, +, -, ( en ).',

            // Email field messages
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'E-mailadres moet een geldig e-mailadres zijn.',
            'email.unique' => 'Een leverancier met dit e-mailadres bestaat al.',

            // Address field messages
            'address.required' => 'Adres is verplicht.',
            'address.min' => 'Adres moet minimaal 5 karakters bevatten.',
            'address.max' => 'Adres mag maximaal 500 karakters bevatten.',

            // Supplier type messages
            'supplier_type.required' => 'Leverancier type is verplicht.',
            'supplier_type.in' => 'Geselecteerd leverancier type is ongeldig. Kies uit: ' . implode(', ', Supplier::SUPPLIER_TYPES) . '.',

            // Additional field messages
            'is_actief.boolean' => 'Actief status moet waar of onwaar zijn.',
            'opmerking.max' => 'Opmerking mag maximaal 1000 karakters bevatten.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'bedrijfsnaam',
            'contact_person' => 'contactpersoon',
            'phone' => 'telefoonnummer',
            'email' => 'e-mailadres',
            'address' => 'adres',
            'supplier_type' => 'leverancier type',
            'is_actief' => 'actief status',
            'opmerking' => 'opmerking',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Clean and normalize the phone number
        if ($this->has('phone')) {
            $this->merge([
                'phone' => $this->normalizePhoneNumber($this->phone)
            ]);
        }

        // Normalize email to lowercase
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email))
            ]);
        }

        // Trim string fields
        $stringFields = ['name', 'contact_person', 'address', 'opmerking'];
        foreach ($stringFields as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => trim($this->$field)
                ]);
            }
        }
    }

    /**
     * Normalize phone number format.
     *
     * @param string $phone
     * @return string
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // Remove excessive whitespace and normalize format
        return preg_replace('/\s+/', ' ', trim($phone));
    }

    /**
     * Get the validated data with additional processing.
     *
     * @param string|null $key
     * @param mixed $default
     * @return array|mixed
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if ($key !== null) {
            return $validated;
        }

        // Set default value for is_actief if not provided
        if (!isset($validated['is_actief'])) {
            $validated['is_actief'] = true; // Default to active
        }

        return $validated;
    }
}
