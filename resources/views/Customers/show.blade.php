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

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    
                    <!-- Developer Toggle Component -->
                    <x-test.1-dev-toggle />
                    
                    <!-- Error Container (hidden by default) -->
                    <div id="errorContainer" class="hidden mb-8">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                            <div class="flex justify-center mb-4">
                                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-red-800 mb-2">Gegevens niet beschikbaar</h3>
                            <p class="text-red-600">De klantgegevens zijn momenteel niet beschikbaar. Probeer het later opnieuw.</p>
                        </div>
                    </div>
                    
                    <!-- Data Container -->
                    <div id="dataContainer">
                    
                    <!-- Header with Status and Action Buttons -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-8 gap-4">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-gray-100">
                                {{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full {{ $customer->is_actief ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    <div class="w-2 h-2 mr-2 rounded-full {{ $customer->is_actief ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                    {{ $customer->is_actief ? 'Actief' : 'Inactief' }}
                                </span>
                                @if($customer->is_vegetarian)
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                        🥬 Vegetarisch
                                    </span>
                                @endif
                                @if($customer->is_vegan)
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200 rounded-full">
                                        🌱 Veganistisch
                                    </span>
                                @endif
                                @if($customer->no_pork)
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 rounded-full">
                                        🚫 Geen Varkensvlees
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <x-test.button href="{{ route('customers.index') }}">
                                ← Terug naar Overzicht
                            </x-test.button>
                            <x-test.button href="{{ route('customers.edit', $customer) }}">
                                ✏️ Wijzig
                            </x-test.button>
                            @if($customer->is_actief)
                                <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-test.button type="submit" 
                                            onclick="return confirm('Weet je zeker dat je deze klant wilt deactiveren?')">
                                        🗑️ Deactiveer
                                    </x-test.button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('customers.restore', $customer) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition-colors">
                                        ✅ Activeer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Information Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        
                        <!-- Personal Information -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-6 rounded-xl shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Persoonlijke Informatie</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-blue-200 dark:border-blue-700">
                                    <span class="font-medium text-blue-800 dark:text-blue-200">Volledige naam:</span>
                                    <span class="text-blue-900 dark:text-blue-100 font-semibold">{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-blue-200 dark:border-blue-700">
                                    <span class="font-medium text-blue-800 dark:text-blue-200">Geboortedatum:</span>
                                    <span class="text-blue-900 dark:text-blue-100">{{ $customer->birth_date ? \Carbon\Carbon::parse($customer->birth_date)->format('d-m-Y') : '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="font-medium text-blue-800 dark:text-blue-200">Leeftijd:</span>
                                    <span class="text-blue-900 dark:text-blue-100 font-semibold">{{ $customer->birth_date ? \Carbon\Carbon::parse($customer->birth_date)->age . ' jaar' : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 p-6 rounded-xl shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-green-500 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-green-900 dark:text-green-100">Adres Informatie</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-green-200 dark:border-green-700">
                                    <span class="font-medium text-green-800 dark:text-green-200">Straat:</span>
                                    <span class="text-green-900 dark:text-green-100 font-semibold">{{ $customer->street }} {{ $customer->house_number }}{{ $customer->addition }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-green-200 dark:border-green-700">
                                    <span class="font-medium text-green-800 dark:text-green-200">Postcode:</span>
                                    <span class="text-green-900 dark:text-green-100">{{ $customer->postal_code }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="font-medium text-green-800 dark:text-green-200">Stad:</span>
                                    <span class="text-green-900 dark:text-green-100 font-semibold">{{ $customer->city }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 p-6 rounded-xl shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-purple-500 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-purple-900 dark:text-purple-100">Contact Informatie</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-purple-200 dark:border-purple-700">
                                    <span class="font-medium text-purple-800 dark:text-purple-200">Mobiel:</span>
                                    <span class="text-purple-900 dark:text-purple-100 font-semibold">{{ $customer->mobile }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="font-medium text-purple-800 dark:text-purple-200">Email:</span>
                                    <span class="text-purple-900 dark:text-purple-100 break-all">{{ $customer->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Household and Dietary Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        
                        <!-- Household Information -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 p-6 rounded-xl shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-orange-500 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-orange-900 dark:text-orange-100">Huishouden Informatie</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-orange-200 dark:border-orange-700">
                                    <span class="font-medium text-orange-800 dark:text-orange-200">Huishoudgrootte:</span>
                                    <span class="text-orange-900 dark:text-orange-100 font-bold text-lg">{{ $customer->household_size }} {{ $customer->household_size == 1 ? 'persoon' : 'personen' }}</span>
                                </div>
                                @if(isset($customer->adults_count) || isset($customer->children_count) || isset($customer->babies_count))
                                    <div class="bg-white dark:bg-orange-800 p-4 rounded-lg">
                                        <div class="text-sm font-semibold mb-3 text-orange-800 dark:text-orange-200">Samenstelling huishouden:</div>
                                        <div class="grid grid-cols-3 gap-3">
                                            <div class="text-center p-3 bg-blue-100 dark:bg-blue-800 rounded-lg">
                                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-200">{{ $customer->adults_count ?? 0 }}</div>
                                                <div class="text-xs text-blue-700 dark:text-blue-300 font-medium">Volwassenen</div>
                                            </div>
                                            <div class="text-center p-3 bg-green-100 dark:bg-green-800 rounded-lg">
                                                <div class="text-2xl font-bold text-green-600 dark:text-green-200">{{ $customer->children_count ?? 0 }}</div>
                                                <div class="text-xs text-green-700 dark:text-green-300 font-medium">Kinderen</div>
                                            </div>
                                            <div class="text-center p-3 bg-pink-100 dark:bg-pink-800 rounded-lg">
                                                <div class="text-2xl font-bold text-pink-600 dark:text-pink-200">{{ $customer->babies_count ?? 0 }}</div>
                                                <div class="text-xs text-pink-700 dark:text-pink-300 font-medium">Baby's</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex justify-between items-center py-2 border-b border-orange-200 dark:border-orange-700">
                                    <span class="font-medium text-orange-800 dark:text-orange-200">Inkomen:</span>
                                    <span class="text-orange-900 dark:text-orange-100 font-semibold">{{ $customer->income ? '€ ' . number_format($customer->income, 2, ',', '.') : 'Niet opgegeven' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="font-medium text-orange-800 dark:text-orange-200">Registratiedatum:</span>
                                    <span class="text-orange-900 dark:text-orange-100">{{ $customer->registration_date ? \Carbon\Carbon::parse($customer->registration_date)->format('d-m-Y') : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Dietary Preferences -->
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 p-6 rounded-xl shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-indigo-900 dark:text-indigo-100">Voedingsvoorkeuren</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-white dark:bg-indigo-800 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 rounded-full {{ $customer->is_vegetarian ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                        <span class="font-medium text-indigo-900 dark:text-indigo-100">🥬 Vegetarisch</span>
                                    </div>
                                    <span class="text-sm font-semibold px-2 py-1 rounded {{ $customer->is_vegetarian ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $customer->is_vegetarian ? 'Ja' : 'Nee' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white dark:bg-indigo-800 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 rounded-full {{ $customer->is_vegan ? 'bg-emerald-500' : 'bg-gray-300' }}"></div>
                                        <span class="font-medium text-indigo-900 dark:text-indigo-100">🌱 Veganistisch</span>
                                    </div>
                                    <span class="text-sm font-semibold px-2 py-1 rounded {{ $customer->is_vegan ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $customer->is_vegan ? 'Ja' : 'Nee' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white dark:bg-indigo-800 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 rounded-full {{ $customer->no_pork ? 'bg-orange-500' : 'bg-gray-300' }}"></div>
                                        <span class="font-medium text-indigo-900 dark:text-indigo-100">🚫 Geen varkensvlees</span>
                                    </div>
                                    <span class="text-sm font-semibold px-2 py-1 rounded {{ $customer->no_pork ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $customer->no_pork ? 'Ja' : 'Nee' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 p-6 rounded-xl shadow-sm mb-6">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-gray-500 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Systeem Informatie</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-white dark:bg-gray-600 rounded-lg">
                                <div class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $customer->id }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">Klant ID</div>
                            </div>
                            <div class="text-center p-4 bg-white dark:bg-gray-600 rounded-lg">
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('d-m-Y H:i') : '-' }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">Aangemaakt</div>
                            </div>
                            <div class="text-center p-4 bg-white dark:bg-gray-600 rounded-lg">
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $customer->updated_at ? \Carbon\Carbon::parse($customer->updated_at)->format('d-m-Y H:i') : '-' }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">Laatst gewijzigd</div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($customer->opmerking)
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 p-6 rounded-xl shadow-sm border-l-4 border-yellow-400">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-yellow-500 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </div>
                                <h4 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200">Opmerkingen</h4>
                            </div>
                            <p class="text-yellow-900 dark:text-yellow-100 leading-relaxed">{{ $customer->opmerking }}</p>
                        </div>
                    @endif

                    </div> <!-- End Data Container -->

                </div>
            </div>
        </div>
    </div>
</x-app-layout>