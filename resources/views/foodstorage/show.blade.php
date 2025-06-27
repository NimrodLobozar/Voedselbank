<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Header met product naam en barcode -->
                    <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $foodstorage->name }}
                                    @if($foodstorage->brand)
                                        <span class="text-gray-500 font-normal">- {{ $foodstorage->brand }}</span>
                                    @endif
                                </h3>
                                <div class="mt-2">
                                    <span class="text-sm text-gray-500">Streepjescode:</span>
                                    <span class="font-mono text-lg font-bold">{{ $foodstorage->formatted_barcode }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($foodstorage->foodStorage)
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $foodstorage->foodStorage->status_color }}">
                                        {{ $foodstorage->foodStorage->status_label }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Product Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Leverancier -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Leverancier</h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $foodstorage->supplier->name ?? 'Onbekend' }}</p>
                            @if($foodstorage->supplier)
                                <p class="text-sm text-gray-500 mt-1">{{ $foodstorage->supplier->contact_person }}</p>
                                <p class="text-sm text-gray-500">{{ $foodstorage->supplier->phone }}</p>
                            @endif
                        </div>

                        <!-- Opslaglocatie -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Opslaglocatie</h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $foodstorage->foodStorage->name ?? 'Onbekend' }}</p>
                            @if($foodstorage->foodStorage)
                                <p class="text-sm text-gray-500 mt-1">{{ $foodstorage->foodStorage->location }}</p>
                                <p class="text-sm text-gray-500">Type: {{ $foodstorage->foodStorage->storage_type }}</p>
                            @endif
                        </div>

                        <!-- Categorie -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Categorie</h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $foodstorage->category }}</p>
                        </div>

                        <!-- Voorraad -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Voorraad</h4>
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-semibold">
                                {{ $foodstorage->amount }} {{ $foodstorage->unit }}
                            </p>
                            @if($foodstorage->weight_per_unit)
                                <p class="text-sm text-gray-500 mt-1">
                                    Gewicht per eenheid: {{ $foodstorage->weight_per_unit }} kg
                                </p>
                                <p class="text-sm text-gray-500">
                                    Totaal gewicht: {{ number_format($foodstorage->amount * $foodstorage->weight_per_unit, 2) }} kg
                                </p>
                            @endif
                        </div>

                        <!-- Datums -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Belangrijke Datums</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm text-gray-500">Ontvangen:</span>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $foodstorage->received_date->format('d-m-Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Vervaldatum:</span>
                                    @php
                                        $expiryDate = $foodstorage->expiry_date;
                                        $today = \Carbon\Carbon::now()->startOfDay();
                                        $expiryStartOfDay = $expiryDate->startOfDay();
                                        $isExpired = $expiryStartOfDay->isPast();
                                        $daysUntilExpiry = (int) $today->diffInDays($expiryStartOfDay, false); // Cast to integer
                                    @endphp
                                    <p class="text-gray-700 dark:text-gray-300 
                                        @if($isExpired) text-red-600 
                                        @elseif($daysUntilExpiry <= 7 && $daysUntilExpiry >= 0) text-orange-600 
                                        @endif">
                                        {{ $expiryDate->format('d-m-Y') }}
                                        @if($isExpired)
                                            <span class="text-red-600 font-semibold">(Verlopen)</span>
                                        @elseif($daysUntilExpiry <= 7 && $daysUntilExpiry >= 0)
                                            <span class="text-orange-600">({{ $daysUntilExpiry }} dagen)</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- System Info -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Systeem Informatie</h4>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="text-gray-500">Aangemaakt:</span>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $foodstorage->datum_aangemaakt?->format('d-m-Y H:i') ?? 'Onbekend' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Laatst gewijzigd:</span>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $foodstorage->datum_gewijzigd?->format('d-m-Y H:i') ?? 'Onbekend' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opmerking (volledige breedte) -->
                    @if($foodstorage->opmerking)
                        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Opmerking</h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $foodstorage->opmerking }}</p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-8 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-6">
                        <a href="{{ route('foodstorage.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Terug naar Overzicht
                        </a>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('foodstorage.edit', $foodstorage) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Bewerken
                            </a>
                            
                            @php
                                $canDelete = !$foodstorage->foodStorage || $foodstorage->foodStorage->status !== 'onderweg';
                            @endphp
                            
                            @if($canDelete)
                                <form method="POST" action="{{ route('foodstorage.destroy', $foodstorage) }}" 
                                      class="inline-flex"
                                      onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Verwijderen
                                    </button>
                                </form>
                            @else
                                <button type="button" 
                                        class="bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed" 
                                        disabled
                                        title="Product kan niet verwijderd worden: status is 'Onderweg'">
                                    Verwijderen
                                </button>
                                <div class="text-xs text-red-600 mt-1">
                                    Status: "Onderweg" - kan niet verwijderd worden
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
