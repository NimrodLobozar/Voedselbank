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
        .svg-red {
            fill: #dc2626;
            /* red-600 */
            color: #dc2626;
        }

        .svg-red:hover {
            fill: #991b1b;
            /* red-800 */
            color: #991b1b;
            transition: all 0.3s ease;
            transform: scale(1.05);
        }

        /* Alternative method for external SVG files */
        .svg-filter-red {
            filter: invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);
            -webkit-filter: invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);
        }

        .svg-filter-red:hover {
            filter: invert(21%) sepia(77%) saturate(4398%) hue-rotate(346deg) brightness(89%) contrast(109%);
            -webkit-filter: invert(21%) sepia(77%) saturate(4398%) hue-rotate(346deg) brightness(89%) contrast(109%);
            transition: all 0.3s ease;
            transform: scale(1.05);
        }

        /* For SVG with paths */
        .svg-red svg,
        .svg-red svg path,
        .svg-red svg circle,
        .svg-red svg rect {
            fill: #dc2626 !important;
            stroke: #dc2626 !important;
        }

        .svg-red:hover svg,
        .svg-red:hover svg path,
        .svg-red:hover svg circle,
        .svg-red:hover svg rect {
            fill: #991b1b !important;
            stroke: #991b1b !important;
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
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Leveranciers Overzicht</h3>
                        @if ($suppliers->count() > 0)
                            <a href="{{ route('suppliers.create') }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Nieuwe Leverancier
                            </a>
                        @endif
                    </div>

                    @if ($suppliers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Naam
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Contactpersoon
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Telefoon
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
                                                {{ $supplier->contact_person }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $supplier->email }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $supplier->phone }}
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
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                        Bekijken
                                                    </a>
                                                    <a href="{{ route('suppliers.edit', $supplier) }}"
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                        Bewerken
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('suppliers.destroy', $supplier) }}"
                                                        onsubmit="return confirm('Weet je zeker dat je deze leverancier wilt verwijderen?')"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
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
                        <div class="text-center py-12">
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('svg/supplier.svg') }}" alt="Geen leveranciers"
                                    class="h-16 w-16 svg-filter-red opacity-70 transition-all duration-300">
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">Geen leveranciers
                                gevonden</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Er zijn momenteel geen leveranciers in het systeem. Voeg een nieuwe leverancier toe om
                                te beginnen.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('suppliers.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Voeg eerste leverancier toe
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
</x-app-layout>
