{{-- filepath: resources/views/food_packages/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nieuw Voedselpakket Aanmaken') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 py-8 bg-white dark:bg-gray-800 rounded shadow">
        <form method="POST" action="{{ route('food_packages.store') }}">
            @csrf

            {{-- Klant --}}
            <div class="mb-4">
                <label for="customer_id" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Klant</label>
                <select name="customer_id" id="customer_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    <option value="">-- Kies een klant --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pakketnaam --}}
            <div class="mb-4">
                <label for="package_name" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Pakketnaam</label>
                <input type="text" name="package_name" id="package_name" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
            </div>

            {{-- Samengesteld op --}}
            <div class="mb-4">
                <label for="assembled_at" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Samengesteld op</label>
                <input type="date" name="assembled_at" id="assembled_at" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
            </div>

            {{-- Distributiedatum --}}
            <div class="mb-4">
                <label for="distribution_date" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Distributiedatum</label>
                <input type="date" name="distribution_date" id="distribution_date" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
            </div>

            {{-- Afhaaltijd --}}
            <div class="mb-4">
                <label for="pickup_time" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Afhaaltijd (optioneel)</label>
                <input type="time" name="pickup_time" id="pickup_time" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
            </div>

            {{-- Producten --}}
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-200 font-medium mb-2">Voeg producten toe</label>
                <div class="space-y-4">
                    @foreach ($produceItems as $produce)
                        <div class="flex items-center gap-4">
                            <input type="checkbox" name="produce[{{ $loop->index }}][id]" value="{{ $produce->id }}" id="produce_{{ $produce->id }}" class="form-checkbox text-blue-600">
                            <label for="produce_{{ $produce->id }}" class="text-gray-800 dark:text-gray-100">
                                {{ $produce->name }} ({{ $produce->expiry_date }})
                            </label>
                            <input type="number" name="produce[{{ $loop->index }}][quantity]" placeholder="Aantal" min="1" class="w-24 rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                Voedselpakket Aanmaken
            </button>
        </form>
    </div>
</x-app-layout>
