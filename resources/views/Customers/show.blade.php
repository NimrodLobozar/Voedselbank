<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight text-gray-900 dark:text-gray-100">
                {{ __('Klant') }} #{{ $customer->id }} - {{ $customer->first_name }} {{ $customer->last_name }}
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
                    
                    <!-- Header with Action Buttons -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold">Klantgegevens</h3>
                            <span class="px-2 py-1 text-xs rounded-full {{ $customer->is_actief ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $customer->is_actief ? 'Actief' : 'Inactief' }}
                            </span>
                        </div>
                        <div class="flex space-x-2">
                             <x-test.button href="{{ route('customers.index') }}">
                            Terug naar Overzicht
                            </x-test.button>
                             <x-test.button href="{{ route('customers.edit', $customer) }}" >
                            Wijzig
                            </x-test.button>
                            @if($customer->is_actief)
                                <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Weet je zeker dat je deze klant wilt deactiveren?')">
                                        <x-test.button>Verwijder</x-test.button>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('customers.restore', $customer) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Activeren
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Personal Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold mb-4 text-blue-600 dark:text-blue-400">Persoonlijke Informatie</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="font-medium">Volledige naam:</span>
                                    <span>{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Geboortedatum:</span>
                                    <span>{{ $customer->birth_date ? \Carbon\Carbon::parse($customer->birth_date)->format('d-m-Y') : '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Leeftijd:</span>
                                    <span>{{ $customer->birth_date ? \Carbon\Carbon::parse($customer->birth_date)->age . ' jaar' : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold mb-4 text-green-600 dark:text-green-400">Adres Informatie</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="font-medium">Straat:</span>
                                    <span>{{ $customer->street }} {{ $customer->house_number }}{{ $customer->addition }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Postcode:</span>
                                    <span>{{ $customer->postal_code }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Stad:</span>
                                    <span>{{ $customer->city }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold mb-4 text-purple-600 dark:text-purple-400">Contact Informatie</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="font-medium">Mobiel:</span>
                                    <span>{{ $customer->mobile }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Email:</span>
                                    <span class="break-all">{{ $customer->email }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Household Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold mb-4 text-orange-600 dark:text-orange-400">Huishouden Informatie</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="font-medium">Huishoudgrootte:</span>
                                    <span>{{ $customer->household_size }} {{ $customer->household_size == 1 ? 'persoon' : 'personen' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Inkomen:</span>
                                    <span>{{ $customer->income ? '€ ' . number_format($customer->income, 2, ',', '.') : 'Niet opgegeven' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Registratiedatum:</span>
                                    <span>{{ $customer->registration_date ? \Carbon\Carbon::parse($customer->registration_date)->format('d-m-Y') : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- System Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg md:col-span-2">
                            <h4 class="font-semibold mb-4 text-gray-600 dark:text-gray-400">Systeem Informatie</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex justify-between">
                                    <span class="font-medium">Klant ID:</span>
                                    <span>{{ $customer->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Aangemaakt:</span>
                                    <span>{{ $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('d-m-Y H:i') : '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Laatst gewijzigd:</span>
                                    <span>{{ $customer->updated_at ? \Carbon\Carbon::parse($customer->updated_at)->format('d-m-Y H:i') : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        @if($customer->opmerking)
                            <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg md:col-span-2">
                                <h4 class="font-semibold mb-2 text-yellow-700 dark:text-yellow-300">Opmerkingen</h4>
                                <p class="text-gray-700 dark:text-gray-300">{{ $customer->opmerking }}</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
