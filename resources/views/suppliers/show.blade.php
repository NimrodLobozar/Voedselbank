<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Leverancier Details') }}
        </h2>
        <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
            {{ now()->format('d M Y') }}
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $supplier->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Email: {{ $supplier->email }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Telefoon: {{ $supplier->phone }}</p>
                    <a href="{{ route('suppliers.index') }}"
                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                        Terug naar leverancierslijst
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
