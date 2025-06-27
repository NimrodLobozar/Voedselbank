{{-- resources/views/foodstorage/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Food Storage Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Food Storage Overzicht</h1>

                    <form method="GET" action="{{ route('foodstorage.index') }}" class="mb-6">
                        <div class="flex gap-4 mb-4">
                            <input type="text" name="name" class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" placeholder="Zoek op naam" value="{{ request('name') }}">
                            <input type="text" name="location" class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" placeholder="Zoek op locatie" value="{{ request('location') }}">
                            <button class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600" type="submit">Zoeken</button>
                        </div>
                    </form>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('foodstorage.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                        Naam
                                        @if(request('sort') === 'name')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width={2} d="M16 12H8m8 4H8m8-8H8" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('foodstorage.index', array_merge(request()->all(), ['sort' => 'location', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                        Locatie
                                        @if(request('sort') === 'location')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width={2} d="M16 12H8m8 4H8m8-8H8" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('foodstorage.index', array_merge(request()->all(), ['sort' => 'capacity', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                        Capaciteit
                                        @if(request('sort') === 'capacity')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width={2} d="M16 12H8m8 4H8m8-8H8" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('foodstorage.index', array_merge(request()->all(), ['sort' => 'storage_type', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                        Type
                                        @if(request('sort') === 'storage_type')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width={2} d="M16 12H8m8 4H8m8-8H8" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Temperatuur
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acties
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($storages as $storage)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $storage->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $storage->location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $storage->capacity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                        @if($storage->storage_type === 'Frozen') bg-blue-100 text-blue-800
                                        @elseif($storage->storage_type === 'Refrigerated') bg-green-100 text-green-800
                                        @elseif($storage->storage_type === 'Fresh') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $storage->storage_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($storage->temperature_min || $storage->temperature_max)
                                        {{ $storage->temperature_min ?? 'N/A' }}°C - {{ $storage->temperature_max ?? 'N/A' }}°C
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('foodstorage.edit', $storage) }}" class="inline-flex items-center px-3 py-1 text-sm font-semibold bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200">
                                        Wijzigen
                                    </a>
                                    <form action="{{ route('foodstorage.destroy', $storage) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex items-center px-3 py-1 text-sm font-semibold bg-red-100 text-red-800 rounded-md hover:bg-red-200" type="submit" onclick="return confirm('Weet je zeker dat je deze storage wilt verwijderen?')">
                                            Verwijderen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center">Geen food storage gevonden.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        <a href="{{ route('foodstorage.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Nieuwe Food Storage Toevoegen</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>