{{-- resources/views/foodstorage/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Voorraad Beheer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Productvoorraad Overzicht</h1>

                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('foodstorage.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <input type="text" name="barcode" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" 
                                   placeholder="Zoek op streepjescode (ID)" value="{{ request('barcode') }}">
                            
                            <input type="text" name="name" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" 
                                   placeholder="Zoek op productnaam" value="{{ request('name') }}">
                            
                            <select name="category" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                <option value="">Alle categorieën</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Zoeken
                            </button>
                        </div>
                    </form>

                    <!-- Products Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">
                                        <a href="{{ route('foodstorage.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                                           class="hover:underline">Streepjescode (ID)</a>
                                    </th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">
                                        <a href="{{ route('foodstorage.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                                           class="hover:underline">Productnaam</a>
                                    </th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">
                                        <a href="{{ route('foodstorage.index', array_merge(request()->all(), ['sort' => 'category', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                                           class="hover:underline">Categorie</a>
                                    </th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">
                                        <a href="{{ route('foodstorage.index', array_merge(request()->all(), ['sort' => 'amount', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                                           class="hover:underline">Aantal</a>
                                    </th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Brand</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">
                                        <a href="{{ route('foodstorage.index', array_merge(request()->all(), ['sort' => 'expiry_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                                           class="hover:underline">Vervaldatum</a>
                                    </th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Locatie</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produces as $produce)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $produce->isExpired() ? 'bg-red-50' : ($produce->isExpiringSoon() ? 'bg-yellow-50' : '') }}">
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $produce->id }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-medium">{{ $produce->name }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $produce->category }}</span>
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $produce->amount }} {{ $produce->unit }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $produce->brand ?? 'N/A' }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        <span class="{{ $produce->isExpired() ? 'text-red-600 font-bold' : ($produce->isExpiringSoon() ? 'text-yellow-600 font-bold' : '') }}">
                                            {{ $produce->expiry_date->format('d-m-Y') }}
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm">{{ $produce->foodStorage->name ?? 'N/A' }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        <div class="flex gap-2">
                                            <!-- Info button for additional details -->
                                            <button type="button" onclick="showProductInfo({{ $produce->id }})" 
                                                class="px-2 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600" title="Meer info">
                                                ℹ️
                                            </button>
                                            <a href="{{ route('foodstorage.edit', $produce) }}" class="px-2 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Wijzig</a>
                                            <form action="{{ route('foodstorage.destroy', $produce) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600" type="submit" 
                                                        onclick="return confirm('Zeker weten? Product verwijderen uit voorraad?')">Verwijder</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="border border-gray-300 dark:border-gray-600 px-4 py-8 text-center text-gray-500">Geen producten in voorraad gevonden.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('foodstorage.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Nieuw Product Toevoegen</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for product details -->
    <div id="productModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Product Details</h3>
                    <button onclick="closeProductModal()" class="text-gray-500 hover:text-gray-700">✕</button>
                </div>
                <div id="productDetails" class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <!-- Details will be populated here -->
                </div>
            </div>
        </div>
    </div>

    <script>
    function showProductInfo(productId) {
        // Find the product data from the table row
        const productData = @json($produces->keyBy('id'));
        const product = productData[productId];
        
        if (product) {
            const details = `
                <div><strong>Leverancier:</strong> ${product.supplier?.name || 'N/A'}</div>
                <div><strong>Ontvangstdatum:</strong> ${product.received_date || 'N/A'}</div>
                <div><strong>Gewicht per eenheid:</strong> ${product.weight_per_unit || 'N/A'} kg</div>
                <div><strong>Opslagtype:</strong> ${product.foodStorage?.storage_type || 'N/A'}</div>
                <div><strong>Opmerking:</strong> ${product.opmerking || 'Geen opmerking'}</div>
            `;
            
            document.getElementById('productDetails').innerHTML = details;
            document.getElementById('productModal').classList.remove('hidden');
        }
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeProductModal();
        }
    });
    </script>
</x-app-layout>