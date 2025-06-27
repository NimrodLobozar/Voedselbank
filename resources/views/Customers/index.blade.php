<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight text-gray-900 dark:text-gray-100">
                {{ __('Klanten') }}
            </h2>
            <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                {{ now()->format('d M Y') }}
            </span>
        </div>
    </x-slot>
    
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Controls Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <!-- Search Filters -->
                <div class="flex-1">
                    <button id="toggleFilters" class="flex items-center text-blue-600 mb-2 font-medium hover:text-blue-800 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filters {{ request('search') ? '(Actief)' : '' }}
                    </button>
                    
                    <div id="filterSection" class="{{ request('search') ? '' : 'hidden' }} bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1">
                                <input type="text" name="search" placeholder="Zoek op naam, email of adres..." value="{{ request('search') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                                    Zoeken
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('customers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Controls -->
                <div class="flex items-center gap-4">
                    <label class="flex items-center">
                        <span class="text-white mr-2">Toon Data</span>
                        <div class="relative">
                            <input type="checkbox" id="dataToggle" class="sr-only" checked>
                            <div class="toggle-bg w-10 h-6 bg-gray-300 rounded-full shadow-inner cursor-pointer"></div>
                            <div class="toggle-dot absolute w-4 h-4 bg-white rounded-full shadow inset-y-1 left-1 cursor-pointer transition-transform"></div>
                        </div>
                    </label>
                    <a href="{{ route('customers.create') }}" 
                       class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-green-700 transition-all transform hover:scale-105">
                        Nieuwe Klant
                    </a>
                </div>
            </div>
        </div>

        <!-- Data Container -->
        <div id="dataContainer">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    @if (count($customers) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-100">
                                    <tr class="text-gray-800 text-sm font-medium uppercase tracking-wider">
                                        <th class="py-4 px-6 text-left">Naam</th>
                                        <th class="py-4 px-6 text-left hidden sm:table-cell">Geboortedatum</th>
                                        <th class="py-4 px-6 text-left">Adres</th>
                                        <th class="py-4 px-6 text-left hidden md:table-cell">Telefoon</th>
                                        <th class="py-4 px-6 text-left hidden lg:table-cell">Email</th>
                                        <th class="py-4 px-6 text-left">Huishoudgrootte</th>
                                        <th class="py-4 px-6 text-left">Status</th>
                                        <th class="py-4 px-6 text-center">Acties</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($customers as $customer)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $customer->full_name }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-600 hidden sm:table-cell">
                                                {{ $customer->birth_date ? \Carbon\Carbon::parse($customer->birth_date)->format('d-m-Y') : 'Onbekend' }}
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-600">{{ $customer->full_address }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-600 hidden md:table-cell">{{ $customer->mobile }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-600 hidden lg:table-cell">{{ $customer->email }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-900">{{ $customer->household_size }}</td>
                                            <td class="py-4 px-6">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $customer->is_actief ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $customer->is_actief ? 'Actief' : 'Inactief' }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('customers.show', $customer->id) }}" 
                                                       class="text-blue-600 hover:text-blue-900 text-lg" title="Bekijk">ⓘ</a>
                                                    <a href="{{ route('customers.edit', $customer->id) }}" 
                                                       class="text-yellow-600 hover:text-yellow-900 text-lg" title="Bewerk">✎</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-red-500 text-lg">
                                @if(request('search'))
                                    Geen klanten gevonden met zoekterm "{{ request('search') }}".
                                @else
                                    Geen klanten beschikbaar. Maak een nieuwe klant aan.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Error Container -->
        <div id="errorContainer" class="hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-12">
                <p class="text-red-500 text-lg">Geen klanten gevonden. Maak een nieuwe klant aan.</p>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataToggle = document.getElementById('dataToggle');
    const dataContainer = document.getElementById('dataContainer');
    const errorContainer = document.getElementById('errorContainer');
    const toggleFilters = document.getElementById('toggleFilters');
    const filterSection = document.getElementById('filterSection');

    // Data toggle functionality
    dataToggle.addEventListener('change', function() {
        if (this.checked) {
            dataContainer.classList.remove('hidden');
            errorContainer.classList.add('hidden');
        } else {
            dataContainer.classList.add('hidden');
            errorContainer.classList.remove('hidden');
        }
    });

    // Filter toggle functionality
    toggleFilters.addEventListener('click', function() {
        filterSection.classList.toggle('hidden');
    });
});
</script>

<style>
/* Toggle Switch Styles */
#dataToggle:checked + .toggle-bg {
    background-color: #38A169;
}

#dataToggle:checked + .toggle-bg .toggle-dot {
    transform: translateX(1rem);
}

.toggle-dot {
    transition: transform 0.2s ease-in-out;
}

/* Responsive table adjustments */
@media (max-width: 640px) {
    table {
        font-size: 0.875rem;
    }
    
    .py-4 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    
    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
