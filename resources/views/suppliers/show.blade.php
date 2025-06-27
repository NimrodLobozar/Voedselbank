<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Leverancier Details') }}
        </h2>
        <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
            {{ now()->format('d M Y') }}
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bedrijfsinformatie -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $supplier->name }}
                            </h3>

                            <div class="space-y-3">
                                <div>
                                    <span
                                        class="text-sm font-medium text-gray-500 dark:text-gray-400">Contactpersoon:</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $supplier->contact_person }}
                                    </p>
                                </div>

                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                        <a href="mailto:{{ $supplier->email }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            {{ $supplier->email }}
                                        </a>
                                    </p>
                                </div>

                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefoon:</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                        <a href="tel:{{ $supplier->phone }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $supplier->phone }}
                                        </a>
                                    </p>
                                </div>

                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Adres:</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $supplier->address }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Aanvullende informatie -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Aanvullende informatie
                            </h4>

                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Type:</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full 
                                            @if ($supplier->supplier_type == 'Supermarket') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($supplier->supplier_type == 'Farmer') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($supplier->supplier_type == 'Wholesaler') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                            @if ($supplier->supplier_type == 'Supermarket')
                                                Supermarkt
                                            @elseif($supplier->supplier_type == 'Farmer')
                                                Boer
                                            @elseif($supplier->supplier_type == 'Wholesaler')
                                                Groothandel
                                            @elseif($supplier->supplier_type == 'Individual')
                                                Particulier
                                            @else
                                                {{ $supplier->supplier_type }}
                                            @endif
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status:</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full 
                                            @if ($supplier->is_actief) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                            {{ $supplier->is_actief ? 'Actief' : 'Inactief' }}
                                        </span>
                                    </p>
                                </div>

                                @if ($supplier->opmerking)
                                    <div>
                                        <span
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400">Opmerking:</span>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $supplier->opmerking }}
                                        </p>
                                    </div>
                                @endif

                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Aangemaakt
                                        op:</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $supplier->datum_aangemaakt ? \Carbon\Carbon::parse($supplier->datum_aangemaakt)->format('d-m-Y H:i') : 'Onbekend' }}
                                    </p>
                                </div>

                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Laatst
                                        gewijzigd:</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $supplier->datum_gewijzigd ? \Carbon\Carbon::parse($supplier->datum_gewijzigd)->format('d-m-Y H:i') : 'Onbekend' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('suppliers.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Terug naar overzicht
                        </a>
                        <a href="{{ route('suppliers.edit', $supplier) }}"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Bewerken
                        </a>
                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                            onsubmit="return confirm('Weet je zeker dat je deze leverancier wilt verwijderen?')"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Verwijderen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
