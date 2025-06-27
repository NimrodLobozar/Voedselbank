<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Voedselpakket Details
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
                    <h3 class="text-lg font-bold mb-4">Pakket: {{ $package->package_name }}</h3>
                    <p><strong>Klant:</strong> {{ $package->klantnaam ?? $package->customer_name ?? '-' }}</p>
                    <p><strong>Samengesteld op:</strong> {{ $package->assembled_at }}</p>
                    <p><strong>Distributiedatum:</strong> {{ $package->distribution_date }}</p>
                    <p><strong>Afhaaltijd:</strong> {{ $package->pickup_time ?? '-' }}</p>
                    <hr class="my-4">
                    <h4 class="font-semibold mb-2">Producten in dit pakket:</h4>
                    @if(isset($package->produce_items) && count($package->produce_items))
                        <ul class="list-disc pl-6">
                            @foreach($package->produce_items as $item)
                                <li>{{ $item->produce_name }} (x{{ $item->quantity }})</li>
                            @endforeach
                        </ul>
                    @else
                        <p>Geen producten toegevoegd aan dit pakket.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>