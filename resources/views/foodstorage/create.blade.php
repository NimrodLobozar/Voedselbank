<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nieuw Product Toevoegen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Nieuw Product aan Voorraad Toevoegen</h1>

                    <form method="POST" action="{{ route('foodstorage.store') }}" class="space-y-6">
                        @csrf

                        <!-- Product Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Productnaam *
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Bijv. Appels Elstar">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Brand -->
                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Merk/Brand
                                </label>
                                <input type="text" id="brand" name="brand" value="{{ old('brand') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Bijv. Albert Heijn, Campina">
                                @error('brand')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Categorie *
                                </label>
                                <select id="category" name="category" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecteer categorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Amount and Unit -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Aantal *
                                    </label>
                                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}" min="1" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('amount')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Eenheid *
                                    </label>
                                    <select id="unit" name="unit" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Selecteer</option>
                                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                                        <option value="stuks" {{ old('unit') == 'stuks' ? 'selected' : '' }}>stuks</option>
                                        <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>liter</option>
                                        <option value="pakken" {{ old('unit') == 'pakken' ? 'selected' : '' }}>pakken</option>
                                        <option value="blikken" {{ old('unit') == 'blikken' ? 'selected' : '' }}>blikken</option>
                                        <option value="zakken" {{ old('unit') == 'zakken' ? 'selected' : '' }}>zakken</option>
                                    </select>
                                    @error('unit')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Weight per unit -->
                            <div>
                                <label for="weight_per_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Gewicht per eenheid (kg)
                                </label>
                                <input type="number" id="weight_per_unit" name="weight_per_unit" value="{{ old('weight_per_unit', '1.0') }}" step="0.001" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('weight_per_unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Expiry Date -->
                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Vervaldatum *
                                </label>
                                <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('expiry_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Received Date -->
                            <div>
                                <label for="received_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Ontvangstdatum *
                                </label>
                                <input type="date" id="received_date" name="received_date" value="{{ old('received_date', now()->format('Y-m-d')) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('received_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Leverancier *
                                </label>
                                <select id="supplier_id" name="supplier_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecteer leverancier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Storage Location -->
                            <div>
                                <label for="food_storage_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Opslaglocatie *
                                </label>
                                <select id="food_storage_id" name="food_storage_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecteer opslaglocatie</option>
                                    @foreach($storages as $storage)
                                        <option value="{{ $storage->id }}" {{ old('food_storage_id') == $storage->id ? 'selected' : '' }}>
                                            {{ $storage->name }} ({{ $storage->storage_type }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('food_storage_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div>
                            <label for="opmerking" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Opmerking
                            </label>
                            <textarea id="opmerking" name="opmerking" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Bijv. Extra informatie over het product...">{{ old('opmerking') }}</textarea>
                            @error('opmerking')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('foodstorage.index') }}" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Annuleren
                            </a>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Product Toevoegen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
