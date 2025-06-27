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

            <!-- Controls -->
            <div class="space-y-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1 lg:max-w-md">
                        <x-test.search />
                    </div>
                    <div class="flex justify-end lg:w-auto">
                        <a href="{{ route('customers.create') }}">
                            <x-test.button>Nieuwe Klant</x-test.button>
                        </a>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="flex flex-col lg:flex-row lg:justify-between gap-4">
                    <div id="filterSection" class="{{ request('status_filter') || request('household_filter') ? '' : 'hidden' }} bg-gray-50 p-4 rounded-lg lg:max-w-2xl flex-1">
                        <form method="GET" action="{{ route('customers.index') }}" class="space-y-4">
                            <input type="hidden" name="name_search" value="{{ request('name_search') }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Alle statussen</option>
                                        <option value="1" @selected(request('status_filter') == '1')>Actief</option>
                                        <option value="0" @selected(request('status_filter') == '0')>Inactief</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Huishoudgrootte</label>
                                    <select name="household_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Alle groottes</option>
                                        @foreach ([1, 2, 3, 4] as $size)
                                            <option value="{{ $size }}" @selected(request('household_filter') == $size)>{{ $size }} persoon{{ $size > 1 ? 'en' : '' }}</option>
                                        @endforeach
                                        <option value="5+" @selected(request('household_filter') == '5+')>5+ personen</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                    Filters toepassen
                                </button>
                                @if(request('name_search') || request('status_filter') || request('household_filter'))
                                    <a href="{{ route('customers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                        Reset alle filters
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="dataContainer">
            @if (count($customers))
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 justify-items-center">
                    @foreach ($customers as $customer)
                        <x-test.card :customer="$customer" />
                    @endforeach
                </div>
            @else
                <div class="bg-white shadow-lg rounded-lg overflow-hidden text-center py-12">
                    <p class="text-red-500 text-lg">
                        @if(request('name_search'))
                            Geen klanten gevonden met zoekterm "{{ request('name_search') }}".
                        @else
                            Geen klanten beschikbaar. Maak een nieuwe klant aan.
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Error Container (if needed dynamically) -->
        <div id="errorContainer" class="hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-12">
                <p class="text-red-500 text-lg">Er zijn momenteel geen klanten beschikbaar. Probeer het later opnieuw.</p>
            </div>
        </div>
    </div>

    <x-test.dev-toggle />
</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const filterIcon = document.getElementById('search-filter-icon');
    const filterSection = document.getElementById('filterSection');
    const searchInput = document.querySelector('#search-main .search-input');

    // Toggle filter section
    if (filterIcon) {
        filterIcon.addEventListener('click', () => {
            filterSection.classList.toggle('hidden');
        });
    }

    // Handle search form submission
    if (searchInput) {
        // Submit search on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitSearch(this.value);
            }
        });

        // Also submit when clicking outside or losing focus after typing
        searchInput.addEventListener('blur', function() {
            if (this.value !== '{{ request("name_search") }}') {
                submitSearch(this.value);
            }
        });
    }

    function submitSearch(searchValue) {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("customers.index") }}';

        // Add search input
        const searchInput = document.createElement('input');
        searchInput.type = 'hidden';
        searchInput.name = 'name_search';
        searchInput.value = searchValue;
        form.appendChild(searchInput);

        // Preserve existing filters
        const currentFilters = new URLSearchParams(window.location.search);
        ['status_filter', 'household_filter'].forEach(filter => {
            const value = currentFilters.get(filter);
            if (value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = filter;
                input.value = value;
                form.appendChild(input);
            }
        });

        document.body.appendChild(form);
        form.submit();
    }
});
</script>
@endpush

@push('styles')
<style>
.toggle-dot {
    transition: transform 0.2s ease-in-out;
}

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

/* Responsive card grid fallback */
@media (max-width: 640px) {
    .grid {
        grid-template-columns: 1fr;
    }
}
@media (min-width: 641px) and (max-width: 768px) {
    .grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (min-width: 769px) and (max-width: 1024px) {
    .grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
@endpush
