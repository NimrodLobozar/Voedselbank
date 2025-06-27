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
            <div class="space-y-6 mb-6">
                <!-- Top Row - Search and Button -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Search Component - Left Side -->
                    <div class="flex-1 lg:max-w-md">
                        <x-test.search />
                    </div>
                    
                    <!-- Right Controls - Nieuwe Klant Button -->
                    <div class="flex justify-end lg:w-auto lg:flex-shrink-0">
                        <a href="{{ route('customers.create') }}" class="inline-block">
                            <x-test.button>
                                Nieuwe Klant
                            </x-test.button>
                        </a>
                    </div>
                </div>
                
                <!-- Additional Filters - Left Side -->
                <div class="flex flex-col lg:flex-row lg:justify-between gap-4">
                    <div id="filterSection" class="{{ request('status_filter') || request('household_filter') ? '' : 'hidden' }} bg-gray-50 p-4 rounded-lg lg:max-w-2xl flex-1">
                        <form method="GET" action="{{ route('customers.index') }}" class="space-y-4">
                            <input type="hidden" name="name_search" value="{{ request('name_search') }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Status Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Alle statussen</option>
                                        <option value="1" {{ request('status_filter') == '1' ? 'selected' : '' }}>Actief</option>
                                        <option value="0" {{ request('status_filter') == '0' ? 'selected' : '' }}>Inactief</option>
                                    </select>
                                </div>
                                
                                <!-- Household Size Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Huishoudgrootte</label>
                                    <select name="household_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Alle groottes</option>
                                        <option value="1" {{ request('household_filter') == '1' ? 'selected' : '' }}>1 persoon</option>
                                        <option value="2" {{ request('household_filter') == '2' ? 'selected' : '' }}>2 personen</option>
                                        <option value="3" {{ request('household_filter') == '3' ? 'selected' : '' }}>3 personen</option>
                                        <option value="4" {{ request('household_filter') == '4' ? 'selected' : '' }}>4 personen</option>
                                        <option value="5+" {{ request('household_filter') == '5+' ? 'selected' : '' }}>5+ personen</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                                    Filters toepassen
                                </button>
                                @if(request('name_search') || request('status_filter') || request('household_filter'))
                                    <a href="{{ route('customers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                                        Reset alle filters
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    
                    <!-- Empty right space to maintain button position -->
                    <div class="hidden lg:block lg:w-auto lg:flex-shrink-0"></div>
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
                <p class="text-red-500 text-lg">Er zijn momenteel geen klanten beschikbaar. Probeer het later opnieuw.</p>
            </div>
        </div>
    </div>
    <x-test.dev-toggle />
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterIcon = document.getElementById('search-filter-icon');
    const filterSection = document.getElementById('filterSection');
    const searchInput = document.querySelector('#search-main .search-input');

    // Filter toggle functionality
    if (filterIcon) {
        filterIcon.addEventListener('click', function() {
            filterSection.classList.toggle('hidden');
        });
    }

    // Handle search input
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route("customers.index") }}';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'name_search';
                input.value = this.value;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});
</script>

<style>
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
