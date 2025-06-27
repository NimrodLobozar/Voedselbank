{{-- filepath: resources/views/food_packages/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Voedselpakketten') }}
        </h2>
    </x-slot>
    <div class="flex justify-end mb-6">
    <a href="{{ route('food_packages.create') }}"
       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition">
        + Voeg nieuw pakket toe
    </a>
</div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        @if($packages->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-6" role="alert">
                Er zijn momenteel geen voedselpakketten beschikbaar. Probeer het later opnieuw.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b text-left font-semibold text-gray-700 dark:text-gray-200">Klantnaam</th>
                            <th class="px-4 py-2 border-b text-left font-semibold text-gray-700 dark:text-gray-200">Pakketnaam</th>
                            <th class="px-4 py-2 border-b text-left font-semibold text-gray-700 dark:text-gray-200">Samengesteld op</th>
                            <th class="px-4 py-2 border-b text-left font-semibold text-gray-700 dark:text-gray-200">Uitgiftedatum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $pakket)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="px-4 py-2 border-b text-gray-900 dark:text-gray-100">{{ $pakket->klantnaam }}</td>
                                <td class="px-4 py-2 border-b text-gray-900 dark:text-gray-100">{{ $pakket->package_name }}</td>
                                <td class="px-4 py-2 border-b text-gray-900 dark:text-gray-100">{{ $pakket->assembled_at }}</td>
                                <td class="px-4 py-2 border-b text-gray-900 dark:text-gray-100">{{ $pakket->distribution_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>