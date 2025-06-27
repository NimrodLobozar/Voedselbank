{{-- resources/views/foodstorage/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Voorraad Beheer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Developer Tools (Only visible in development) -->
            @if(config('app.debug'))
                <div class="mb-8 bg-yellow-50 p-5 rounded-lg shadow-sm border border-yellow-200">
                    <h3 class="text-lg font-medium text-yellow-700 mb-4 pb-2 border-b-2 border-yellow-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        Developer Tools - Voorraad Beheer
                    </h3>
                    
                    <div class="mb-3">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="extraDataToggle" class="hidden" checked>
                            <span class="relative inline-block w-10 h-5 rounded-full bg-green-500 transition-colors ease-in-out duration-200 mr-3" id="extraDataToggleSwitch">
                                <span class="extra-data-toggle-slider absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform translate-x-5"></span>
                            </span>
                            <span class="text-sm font-medium text-yellow-700">Toon Extra Data</span>
                        </label>
                        <p class="text-xs text-yellow-600 mt-1">Toggle om extra kolommen (Opslaglocatie) te tonen of te verbergen.</p>
                    </div>
                </div>
            @endif

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <strong class="font-bold">Gelukt!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Fout!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filter Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('foodstorage.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Streepjescode</label>
                            <input type="text" name="barcode" value="{{ request('barcode') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Productnaam</label>
                            <input type="text" name="name" value="{{ request('name') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categorie</label>
                            <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Alle categorieën</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Alle statussen</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filteren
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="{{ route('foodstorage.create') }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Nieuw Product Toevoegen
                        </a>
                    </div>

                    <!-- Error Container (Hidden by default, shown when toggle is off) -->
                    <div id="errorContainer" class="hidden">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                                <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-red-900 mb-2">Voorraad Niet Beschikbaar</h3>
                            <p class="text-red-700 mb-4">Er is geen voorraad op het systeem</p>
                            <p class="text-sm text-red-600">
                                Het voorraadsysteem is momenteel niet toegankelijk. Neem contact op met de systeembeheerder of probeer later opnieuw.
                            </p>
                            @if(config('app.debug'))
                                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Developer Note:</strong> Schakel "Toon Extra Data" in om de voorraad weer te geven.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Data Container (Products Table - Visible by default) -->
                    <div id="dataContainer" class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Streepjescode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Naam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categorie</th>
                                    <th class="extra-data-column px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Opslaglocatie</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Voorraad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vervaldatum</th>
                                    <!-- Acties kolom blijft ALTIJD zichtbaar - geen extra-data-column class -->
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($produces as $produce)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <div class="font-mono text-lg">{{ $produce->formatted_barcode }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $produce->name }}
                                            @if($produce->brand)
                                                <br><small class="text-gray-500">{{ $produce->brand }}</small>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $produce->category }}
                                        </td>
                                        <td class="extra-data-column px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $produce->foodStorage->name ?? 'Onbekend' }}
                                            @if($produce->foodStorage)
                                                <br><small class="text-gray-500">{{ $produce->foodStorage->storage_type }}</small>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $produce->amount }} {{ $produce->unit }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($produce->foodStorage)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $produce->foodStorage->status_color }}">
                                                    {{ $produce->foodStorage->status_label }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Geen status
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @php
                                                $expiryDate = \Carbon\Carbon::parse($produce->expiry_date);
                                                $today = \Carbon\Carbon::now()->startOfDay();
                                                $expiryStartOfDay = $expiryDate->startOfDay();
                                                $isExpired = $expiryStartOfDay->isPast();
                                                $daysUntilExpiry = (int) $today->diffInDays($expiryStartOfDay, false); // Cast to integer
                                            @endphp
                                            <span class="
                                                @if($isExpired) text-red-600 font-semibold
                                                @elseif($daysUntilExpiry <= 7 && $daysUntilExpiry >= 0) text-orange-600 font-medium
                                                @endif
                                            ">
                                                {{ $expiryDate->format('d-m-Y') }}
                                            </span>
                                            @if($isExpired)
                                                <br><small class="text-red-600">Verlopen</small>
                                            @elseif($daysUntilExpiry <= 7 && $daysUntilExpiry >= 0)
                                                <br><small class="text-orange-600">{{ $daysUntilExpiry }} dagen</small>
                                            @endif
                                        </td>
                                        <!-- Acties kolom blijft ALTIJD zichtbaar - geen extra-data-column class -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('foodstorage.show', $produce) }}" 
                                                   class="text-green-600 hover:text-green-900">Details</a>
                                                <a href="{{ route('foodstorage.edit', $produce) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">Bewerken</a>
                                                
                                                @php
                                                    $canDelete = !$produce->foodStorage || $produce->foodStorage->status !== 'onderweg';
                                                @endphp
                                                
                                                @if($canDelete)
                                                    <form method="POST" action="{{ route('foodstorage.destroy', $produce) }}" 
                                                          class="inline-block"
                                                          onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            Verwijderen
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" 
                                                            class="text-gray-400 cursor-not-allowed" 
                                                            disabled
                                                            title="Product kan niet verwijderd worden: status is 'Onderweg'">
                                                        Verwijderen
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Geen producten gevonden.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle functionality for extra data visibility
        document.addEventListener('DOMContentLoaded', function() {
            const extraDataToggle = document.getElementById('extraDataToggle');
            const extraDataToggleSwitch = document.getElementById('extraDataToggleSwitch');
            const extraDataColumns = document.querySelectorAll('.extra-data-column');
            const dataContainer = document.getElementById('dataContainer');
            const errorContainer = document.getElementById('errorContainer');
            
            if (extraDataToggle && extraDataToggleSwitch && extraDataColumns.length > 0 && dataContainer && errorContainer) {
                extraDataToggle.addEventListener('change', function() {
                    if (this.checked) {
                        // Show extra data and hide error
                        extraDataColumns.forEach(column => {
                            column.classList.remove('hidden');
                        });
                        dataContainer.classList.remove('hidden');
                        errorContainer.classList.add('hidden');
                        extraDataToggleSwitch.classList.remove('bg-red-500');
                        extraDataToggleSwitch.classList.add('bg-green-500');
                        document.querySelector('.extra-data-toggle-slider').classList.add('translate-x-5');
                    } else {
                        // Hide data and show error (Unhappy Scenario)
                        extraDataColumns.forEach(column => {
                            column.classList.add('hidden');
                        });
                        dataContainer.classList.add('hidden');
                        errorContainer.classList.remove('hidden');
                        extraDataToggleSwitch.classList.remove('bg-green-500');
                        extraDataToggleSwitch.classList.add('bg-red-500');
                        document.querySelector('.extra-data-toggle-slider').classList.remove('translate-x-5');
                    }
                });
            }
        });
    </script>
</x-app-layout>