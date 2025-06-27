<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
           <h2 class="font-semibold text-xl leading-tight text-gray-900 dark:text-gray-100">
                {{ __('Klant Aanmaken') }}
            </h2>
            <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                {{ now()->format('d M Y') }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                         <x-test.button href="{{ route('customers.index') }}">
                            Terug naar Overzicht
                        </x-test.button>
                    </div>

                    <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">
                        @csrf

                        <!-- Account Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold mb-4">Account Informatie</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Gebruikersnaam *</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Login Email *</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('email')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Wachtwoord *</label>
                                    <input type="password" name="password" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('password')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Bevestig Wachtwoord *</label>
                                    <input type="password" name="password_confirmation" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold mb-4">Persoonlijke Informatie</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Voornaam *</label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('first_name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Tussenvoegsel</label>
                                    <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('middle_name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Achternaam *</label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('last_name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Geboortedatum *</label>
                                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('birth_date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold mb-4">Adres Informatie</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-1">Straat *</label>
                                    <input type="text" name="street" value="{{ old('street') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('street')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Huisnummer *</label>
                                    <input type="text" name="house_number" value="{{ old('house_number') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('house_number')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Toevoeging</label>
                                    <input type="text" name="addition" value="{{ old('addition') }}"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('addition')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Postcode *</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('postal_code')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Stad *</label>
                                    <input type="text" name="city" value="{{ old('city') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('city')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold mb-4">Contact Informatie</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Mobiel *</label>
                                    <input type="text" name="mobile" value="{{ old('mobile') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('mobile')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Contact Email *</label>
                                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('customer_email')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Household Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold mb-4">Huishouden Informatie</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Huishoudgrootte *</label>
                                    <input type="number" name="household_size" value="{{ old('household_size', 1) }}" min="1" max="20" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('household_size')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Inkomen (€)</label>
                                    <input type="number" name="income" value="{{ old('income') }}" step="0.01" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('income')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Registratiedatum *</label>
                                    <input type="date" name="registration_date" value="{{ old('registration_date', now()->format('Y-m-d')) }}" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">
                                    @error('registration_date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Opmerkingen</label>
                            <textarea name="opmerking" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800">{{ old('opmerking') }}</textarea>
                            @error('opmerking')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4">
                             <x-test.button href="{{ route('customers.index') }}">
                                Annuleren
                            </x-test.button>
                             <x-test.button type="submit">
                                Opslaan
                            </x-test.button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
