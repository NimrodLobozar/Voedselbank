<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product Bewerken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('foodstorage.update', $foodstorage) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Er zijn fouten opgetreden:</strong>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Leverancier -->
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Leverancier <span class="text-red-500">*</span>
                                </label>
                                <select name="supplier_id" id="supplier_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('supplier_id') border-red-500 @enderror">
                                    <option value="">Selecteer een leverancier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" 
                                                {{ (old('supplier_id', $foodstorage->supplier_id) == $supplier->id) ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Opslaglocatie -->
                            <div>
                                <label for="food_storage_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Opslaglocatie <span class="text-red-500">*</span>
                                </label>
                                <select name="food_storage_id" id="food_storage_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('food_storage_id') border-red-500 @enderror">
                                    <option value="">Selecteer een opslaglocatie</option>
                                    @foreach($storages as $storage)
                                        <option value="{{ $storage->id }}" 
                                                {{ (old('food_storage_id', $foodstorage->food_storage_id) == $storage->id) ? 'selected' : '' }}>
                                            {{ $storage->name }} - {{ $storage->location }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('food_storage_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Productnaam -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Productnaam <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $foodstorage->name) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                                       placeholder="Bijv. Appels">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Merk -->
                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Merk
                                </label>
                                <input type="text" name="brand" id="brand" value="{{ old('brand', $foodstorage->brand) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('brand') border-red-500 @enderror"
                                       placeholder="Bijv. Albert Heijn">
                                @error('brand')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Categorie -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Categorie <span class="text-red-500">*</span>
                                </label>
                                <select name="category" id="category" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('category') border-red-500 @enderror">
                                    <option value="">Selecteer een categorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" 
                                                {{ (old('category', $foodstorage->category) == $category) ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Status
                                </label>
                                <select name="status" id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                                    <option value="">Selecteer een status</option>
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" 
                                                {{ (old('status', $foodstorage->foodStorage->status ?? '') == $value) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Vervaldatum -->
                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Vervaldatum <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="expiry_date" id="expiry_date" 
                                       value="{{ old('expiry_date', $foodstorage->expiry_date->format('Y-m-d')) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('expiry_date') border-red-500 @enderror">
                                @error('expiry_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ontvangstdatum -->
                            <div>
                                <label for="received_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Ontvangstdatum <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="received_date" id="received_date" 
                                       value="{{ old('received_date', $foodstorage->received_date->format('Y-m-d')) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('received_date') border-red-500 @enderror">
                                @error('received_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hoeveelheid -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Hoeveelheid <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="amount" id="amount" value="{{ old('amount', $foodstorage->amount) }}" 
                                       required min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('amount') border-red-500 @enderror"
                                       placeholder="Bijv. 10">
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Eenheid -->
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Eenheid <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="unit" id="unit" value="{{ old('unit', $foodstorage->unit) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('unit') border-red-500 @enderror"
                                       placeholder="Bijv. kg, stuks, liter">
                                @error('unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gewicht per eenheid -->
                            <div>
                                <label for="weight_per_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Gewicht per eenheid (kg)
                                </label>
                                <input type="number" name="weight_per_unit" id="weight_per_unit" 
                                       value="{{ old('weight_per_unit', $foodstorage->weight_per_unit) }}" 
                                       step="0.001" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('weight_per_unit') border-red-500 @enderror"
                                       placeholder="Bijv. 0.250">
                                @error('weight_per_unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Opmerking -->
                        <div>
                            <label for="opmerking" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Opmerking
                            </label>
                            <textarea name="opmerking" id="opmerking" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Eventuele opmerkingen over het product">{{ old('opmerking', $foodstorage->opmerking) }}</textarea>
                        </div>

                        <!-- Submit buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('foodstorage.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuleren
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Product Bijwerken
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
