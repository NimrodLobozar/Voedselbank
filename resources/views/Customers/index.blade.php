<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Klanten') }}
            </h2>
            <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                {{ now()->format('d M Y') }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Header with Create Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Klantenoverzicht</h3>
                        <a href="{{ route('customers.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Nieuwe Klant
                        </a>
                    </div>

                    <!-- Search Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('customers.index') }}" class="flex gap-4">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Zoek op naam, email of adres..."
                                   class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <button type="submit" 
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Zoeken
                            </button>
                            @if(request('search'))
                                <a href="{{ route('customers.index') }}" 
                                   class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Customers Table -->
                    @if(count($customers) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto border-collapse border border-gray-300 dark:border-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">ID</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Naam</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Geboortedatum</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Adres</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Telefoon</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Email</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Huishoudgrootte</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Status</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $customer->id }}</td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $customer->full_name }}</td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                {{ $customer->birth_date ? \Carbon\Carbon::parse($customer->birth_date)->format('d-m-Y') : '-' }}
                                            </td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $customer->full_address }}</td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $customer->mobile }}</td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $customer->email }}</td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $customer->household_size }}</td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $customer->is_actief ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $customer->is_actief ? 'Actief' : 'Inactief' }}
                                                </span>
                                            </td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('customers.show', $customer->id) }}" 
                                                       class="bg-blue-500 hover:bg-blue-700 text-white text-xs px-2 py-1 rounded">
                                                        Bekijk
                                                    </a>
                                                    <a href="{{ route('customers.edit', $customer->id) }}" 
                                                       class="bg-yellow-500 hover:bg-yellow-700 text-white text-xs px-2 py-1 rounded">
                                                        Bewerk
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">Geen klanten gevonden.</p>
                            @if(request('search'))
                                <p class="text-sm text-gray-400 mt-2">Probeer een andere zoekterm.</p>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
