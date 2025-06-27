<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Voedselpakketten') }}
        </h2>
        <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
            {{ now()->format('d M Y') }}
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 text-red-600 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="mb-4 text-green-600 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif
            <div class="flex justify-between items-center mb-6">
                <form method="GET" action="{{ route('food_packages.index') }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Zoek klant of pakketnaam"
                        class="rounded border-gray-300 dark:bg-gray-700 dark:text-white px-3 py-2">
                    <select name="status" class="rounded border-gray-300 dark:bg-gray-700 dark:text-white px-3 py-2">
                        <option value="">-- Status --</option>
                        <option value="Assembled" @if(request('status')=='Assembled') selected @endif>Samengesteld</option>
                        <option value="Ready" @if(request('status')=='Ready') selected @endif>Klaar</option>
                        <option value="Distributed" @if(request('status')=='Distributed') selected @endif>Uitgeleverd</option>
                        <option value="Cancelled" @if(request('status')=='Cancelled') selected @endif>Geannuleerd</option>
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                        Filter
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('food_packages.index') }}" class="ml-2 text-sm text-gray-500 hover:underline">Reset</a>
                    @endif
                </form>
                <a href="{{ route('food_packages.create') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition">
                    + Voeg nieuw pakket toe
                </a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($packages->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Klantnaam
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Pakketnaam
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Samengesteld op
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Uitgiftedatum
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Acties
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($packages as $pakket)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $pakket->klantnaam }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $pakket->package_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $pakket->assembled_at }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $pakket->distribution_date }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @php
                                                    $statusColors = [
                                                        'Assembled' => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                                        'Ready' => 'bg-blue-200 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                        'Distributed' => 'bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                        'Cancelled' => 'bg-red-200 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                    ];
                                                    $status = $pakket->status ?? 'Assembled';
                                                    $color = $statusColors[$status] ?? 'bg-gray-200 text-gray-800';
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                                    {{ __($status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('food_packages.show', $pakket->id) }}"
                                                    class="inline-block text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                    Bekijken
                                                </a>
                                                <a href="{{ route('food_packages.edit', $pakket->id) }}"
                                                    class="inline-block text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 mr-3">
                                                    Bewerken
                                                </a>
                                                <button
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-foodpackage-deletion-{{ $pakket->id }}')"
                                                    class="inline-block text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 mr-3 bg-transparent border-none p-0 underline cursor-pointer"
                                                    type="button"
                                                >
                                                    Verwijderen
                                                </button>
                                                <x-modal name="confirm-foodpackage-deletion-{{ $pakket->id }}" focusable>
                                                    <form method="POST" action="{{ route('food_packages.destroy', $pakket->id) }}" class="p-6">
                                                        @csrf
                                                        @method('DELETE')
                                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                            Weet je zeker dat je dit pakket wilt verwijderen?
                                                        </h2>
                                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                            Pakket: <strong>{{ $pakket->package_name }}</strong>
                                                        </p>
                                                        <div class="mt-6 flex justify-end">
                                                            <x-secondary-button x-on:click="$dispatch('close')" type="button">
                                                                Annuleren
                                                            </x-secondary-button>
                                                            <x-danger-button class="ml-3">
                                                                Ja, verwijderen
                                                            </x-danger-button>
                                                        </div>
                                                    </form>
                                                </x-modal>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Add pagination links --}}
                        <div class="mt-6">
                            {{ $packages->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v4.01" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Geen voedselpakketten gevonden</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Geen voedselpakketten gevonden. Probeer later opnieuw of voeg voedselpakketten toe.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
