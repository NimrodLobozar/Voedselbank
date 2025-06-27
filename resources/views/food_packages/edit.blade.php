{{-- filepath: c:\laragon\www\Voedselbank\resources\views\food_packages\edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Voedselpakket Bewerken
            </h2>
            <a href="{{ route('food_packages.index') }}"
               class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100 font-semibold py-2 px-4 rounded shadow transition">
                Terug
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('food_packages.update', $package->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="customer_id" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Klant</label>
                            <select name="customer_id" id="customer_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                                <option value="">-- Kies een klant --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @if($customer->id == $package->customer_id) selected @endif>
                                        {{ $customer->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="package_name" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Pakketnaam</label>
                            <input type="text" name="package_name" id="package_name" value="{{ $package->package_name }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>

                        <div class="mb-4">
                            <label for="assembled_at" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Samengesteld op</label>
                            <input type="date" name="assembled_at" id="assembled_at" value="{{ $package->assembled_at }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>

                        <div class="mb-4">
                            <label for="distribution_date" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Distributiedatum</label>
                            <input type="date" name="distribution_date" id="distribution_date" value="{{ $package->distribution_date }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>

                        <div class="mb-4">
                            <label for="pickup_time" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Afhaaltijd (optioneel)</label>
                            <input type="time" name="pickup_time" id="pickup_time" value="{{ $package->pickup_time }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Status</label>
                            <select name="status" id="status" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                                @php
                                    $statuses = ['Assembled' => 'Samengesteld', 'Ready' => 'Klaar', 'Distributed' => 'Uitgeleverd', 'Cancelled' => 'Geannuleerd'];
                                @endphp
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" @if($package->status === $value) selected @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-200 font-medium mb-2">Producten</label>
                            <div class="space-y-4">
                                @foreach ($produceItems as $produce)
                                    @php
                                        $checked = isset($selectedProduce[$produce->id]);
                                        $quantity = $checked ? $selectedProduce[$produce->id] : '';
                                    @endphp
                                    <div class="flex items-center gap-4">
                                        <input type="checkbox" name="produce[{{ $loop->index }}][id]" value="{{ $produce->id }}" id="produce_{{ $produce->id }}" class="form-checkbox text-blue-600" {{ $checked ? 'checked' : '' }}>
                                        <label for="produce_{{ $produce->id }}" class="text-gray-800 dark:text-gray-100">
                                            {{ $produce->name }}
                                            <span class="text-xs font-semibold px-2 py-1 rounded bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 ml-1">
                                                THT: {{ $produce->expiry_date }}
                                            </span>
                                        </label>
                                        <input type="number" name="produce[{{ $loop->index }}][quantity]" placeholder="Aantal" min="1" value="{{ $quantity }}" class="w-24 rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                                        <span class="ml-2 text-gray-600 dark:text-gray-300">{{ $produce->unit }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow">
                                Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>