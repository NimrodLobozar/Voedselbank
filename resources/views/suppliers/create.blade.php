<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nieuwe Leverancier Toevoegen') }}
        </h2>
        <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
            {{ now()->format('d M Y') }}
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-6">
                        @csrf

                        <!-- Bedrijfsnaam -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Bedrijfsnaam *
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contactpersoon -->
                        <div>
                            <label for="contact_person"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contactpersoon *
                            </label>
                            <input type="text" name="contact_person" id="contact_person"
                                value="{{ old('contact_person') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('contact_person')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefoon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Telefoon *
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                E-mailadres *
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Adres -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Adres *
                            </label>
                            <textarea name="address" id="address" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Leverancier Type -->
                        <div>
                            <label for="supplier_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Leverancier Type *
                            </label>
                            <select name="supplier_type" id="supplier_type" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Selecteer een type</option>
                                <option value="Supermarket"
                                    {{ old('supplier_type') == 'Supermarket' ? 'selected' : '' }}>Supermarkt</option>
                                <option value="Farmer" {{ old('supplier_type') == 'Farmer' ? 'selected' : '' }}>Boer
                                </option>
                                <option value="Wholesaler"
                                    {{ old('supplier_type') == 'Wholesaler' ? 'selected' : '' }}>Groothandel</option>
                                <option value="Individual"
                                    {{ old('supplier_type') == 'Individual' ? 'selected' : '' }}>Particulier</option>
                            </select>
                            @error('supplier_type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actief -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_actief" id="is_actief" value="1"
                                {{ old('is_actief', true) ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                            <label for="is_actief" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                Leverancier is actief
                            </label>
                        </div>

                        <!-- Opmerking -->
                        <div>
                            <label for="opmerking" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Opmerking
                            </label>
                            <textarea name="opmerking" id="opmerking" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('opmerking') }}</textarea>
                            @error('opmerking')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('suppliers.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuleren
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Leverancier Aanmaken
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
