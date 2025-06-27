<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('suppliers') }}
        </h2>
        <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
            {{ now()->format('d M Y') }}
        </span>
    </x-slot>

    <!-- Custom styles for red SVG -->
    <style>
        /* SVG color filters - not possible with Tailwind CSS */
        .svg-filter-red {
            filter: invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);
        }

        .svg-filter-red:hover {
            filter: invert(21%) sepia(77%) saturate(4398%) hue-rotate(346deg) brightness(89%) contrast(109%);
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Header with Add Button -->
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
                        <h3 class="text-lg font-medium">Leveranciers Overzicht</h3>
                        @if ($suppliers->count() > 0)
                            <a href="{{ route('suppliers.create') }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Nieuwe Leverancier
                            </a>
                        @endif
                    </div>

                    <!-- Search and Filter Section -->
                    @if ($suppliers->count() > 0)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                            <form method="GET" action="{{ route('suppliers.index') }}"
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                                <!-- Search by name -->
                                <div class="lg:col-span-2">
                                    <label for="search"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Zoek leverancier
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="text" name="search" id="search"
                                            value="{{ request('search') }}" placeholder="Zoek op naam..."
                                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>

                                <!-- Filter by supplier type -->
                                <div>
                                    <label for="supplier_type"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Filter op type
                                    </label>
                                    <select name="supplier_type" id="supplier_type"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Alle types</option>
                                        @foreach ($supplierTypes as $type)
                                            <option value="{{ $type }}"
                                                {{ request('supplier_type') == $type ? 'selected' : '' }}>
                                                @if ($type == 'Supermarket')
                                                    Supermarkt
                                                @elseif($type == 'Farmer')
                                                    Boer
                                                @elseif($type == 'Wholesaler')
                                                    Groothandel
                                                @elseif($type == 'Individual')
                                                    Particulier
                                                @else
                                                    {{ $type }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter by order status -->
                                <div>
                                    <label for="order_status"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Bestelstatus
                                    </label>
                                    <select name="order_status" id="order_status"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Alle statussen</option>
                                        <option value="actief"
                                            {{ request('order_status') == 'actief' ? 'selected' : '' }}>
                                            Actief (geen bestellingen)
                                        </option>
                                        <option value="onderweg"
                                            {{ request('order_status') == 'onderweg' ? 'selected' : '' }}>
                                            Onderweg
                                        </option>
                                        <option value="in_behandeling"
                                            {{ request('order_status') == 'in_behandeling' ? 'selected' : '' }}>
                                            In behandeling
                                        </option>
                                        <option value="geleverd"
                                            {{ request('order_status') == 'geleverd' ? 'selected' : '' }}>
                                            Geleverd
                                        </option>
                                    </select>
                                </div>

                                <!-- Action buttons -->
                                <div class="md:col-span-2 lg:col-span-1 flex gap-2">
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center flex-1 justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Zoeken
                                    </button>
                                    @if (request('search') || request('supplier_type') || request('order_status'))
                                        <a href="{{ route('suppliers.index') }}"
                                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center flex-1 justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    @endif

                    @if ($suppliers->count() > 0)
                        <!-- Responsive table container -->
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Naam
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Acties
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($suppliers as $supplier)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $supplier->name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
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
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full 
                                                    @if ($supplier->is_actief) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                    {{ $supplier->is_actief ? 'Actief' : 'Inactief' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('suppliers.show', $supplier) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                                                        Bekijken
                                                    </a>
                                                    <a href="{{ route('suppliers.edit', $supplier) }}"
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200">
                                                        Bewerken
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('suppliers.destroy', $supplier) }}"
                                                        onsubmit="return confirm('Weet je zeker dat je deze leverancier wilt verwijderen?')"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200">
                                                            Verwijderen
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- No suppliers found -->
                        <div class="text-center py-12">
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('svg/supplier.svg') }}" alt="Geen leveranciers"
                                    class="h-16 w-16 svg-filter-red opacity-70 transition-all duration-300 hover:scale-105">
                            </div>
                            @if (request('search') || request('supplier_type'))
                                <h3 class="mt-2 text-sm font-medium text-orange-600 dark:text-orange-400">Geen
                                    resultaten gevonden</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Er zijn geen leveranciers gevonden die voldoen aan je zoekcriteria.
                                    <br>Probeer je zoekopdracht aan te passen.
                                </p>
                                <div class="mt-4 space-x-2">
                                    <a href="{{ route('suppliers.index') }}"
                                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Alle leveranciers tonen
                                    </a>
                                    <a href="{{ route('suppliers.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nieuwe leverancier toevoegen
                                    </a>
                                </div>
                            @else
                                <h3 class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">Geen leveranciers
                                    gevonden</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Er zijn momenteel geen leveranciers in het systeem. Voeg een nieuwe leverancier toe
                                    om te beginnen.
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('suppliers.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm hover:shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Voeg eerste leverancier toe
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
</x-app-layout>
