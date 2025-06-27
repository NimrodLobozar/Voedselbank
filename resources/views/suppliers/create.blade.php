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
                    <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-6" id="supplierForm"
                        novalidate>
                        @csrf

                        <!-- Bedrijfsnaam -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Bedrijfsnaam *
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                minlength="2" maxlength="255"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Voer de bedrijfsnaam in">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div class="invalid-feedback text-sm text-red-600 dark:text-red-400 mt-1 hidden"></div>
                        </div>

                        <!-- Contactpersoon -->
                        <div>
                            <label for="contact_person"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contactpersoon *
                            </label>
                            <input type="text" name="contact_person" id="contact_person"
                                value="{{ old('contact_person') }}" required minlength="2" maxlength="255"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Voer de naam van de contactpersoon in">
                            @error('contact_person')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div class="invalid-feedback text-sm text-red-600 dark:text-red-400 mt-1 hidden"></div>
                        </div>

                        <!-- Telefoon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Telefoon *
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                minlength="10" maxlength="20" pattern="[0-9\+\-\s\(\)]+"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Bijv. +31 6 12345678 of 020-1234567">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div class="invalid-feedback text-sm text-red-600 dark:text-red-400 mt-1 hidden"></div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                E-mailadres *
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                maxlength="255"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="info@bedrijf.nl">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div class="invalid-feedback text-sm text-red-600 dark:text-red-400 mt-1 hidden"></div>
                        </div>

                        <!-- Adres -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Adres *
                            </label>
                            <textarea name="address" id="address" rows="3" required minlength="5" maxlength="500"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Voer het volledige adres in inclusief postcode en plaats">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div class="invalid-feedback text-sm text-red-600 dark:text-red-400 mt-1 hidden"></div>
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
                            <div class="invalid-feedback text-sm text-red-600 dark:text-red-400 mt-1 hidden"></div>
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
                            <textarea name="opmerking" id="opmerking" rows="3" maxlength="1000"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Eventuele opmerkingen over deze leverancier">{{ old('opmerking') }}</textarea>
                            @error('opmerking')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div class="invalid-feedback text-sm text-red-600 dark:text-red-400 mt-1 hidden"></div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <span id="opmerking-count">0</span>/1000 karakters
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('suppliers.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                Annuleren
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                Leverancier Aanmaken
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side validation JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('supplierForm');
            const fields = ['name', 'contact_person', 'phone', 'email', 'address', 'supplier_type', 'opmerking'];

            // Character counter for opmerking
            const opmerkingField = document.getElementById('opmerking');
            const opmerkingCount = document.getElementById('opmerking-count');

            opmerkingField.addEventListener('input', function() {
                const count = this.value.length;
                opmerkingCount.textContent = count;

                if (count > 1000) {
                    opmerkingCount.parentElement.classList.add('text-red-500');
                    opmerkingCount.parentElement.classList.remove('text-gray-500');
                } else {
                    opmerkingCount.parentElement.classList.remove('text-red-500');
                    opmerkingCount.parentElement.classList.add('text-gray-500');
                }
            });

            // Initial count
            opmerkingCount.textContent = opmerkingField.value.length;

            // Real-time validation
            fields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.addEventListener('blur', () => validateField(field));
                    field.addEventListener('input', () => clearError(field));
                }
            });

            // Form submission validation
            form.addEventListener('submit', function(e) {
                let isValid = true;

                fields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (field && !validateField(field)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });

            function validateField(field) {
                const value = field.value.trim();
                const fieldName = field.name;
                let isValid = true;
                let errorMessage = '';

                // Clear previous errors
                clearError(field);

                switch (fieldName) {
                    case 'name':
                        if (!value) {
                            errorMessage = 'Bedrijfsnaam is verplicht.';
                            isValid = false;
                        } else if (value.length < 2) {
                            errorMessage = 'Bedrijfsnaam moet minimaal 2 karakters bevatten.';
                            isValid = false;
                        } else if (value.length > 255) {
                            errorMessage = 'Bedrijfsnaam mag maximaal 255 karakters bevatten.';
                            isValid = false;
                        }
                        break;

                    case 'contact_person':
                        if (!value) {
                            errorMessage = 'Contactpersoon is verplicht.';
                            isValid = false;
                        } else if (value.length < 2) {
                            errorMessage = 'Contactpersoon moet minimaal 2 karakters bevatten.';
                            isValid = false;
                        } else if (value.length > 255) {
                            errorMessage = 'Contactpersoon mag maximaal 255 karakters bevatten.';
                            isValid = false;
                        }
                        break;

                    case 'phone':
                        const phoneRegex = /^[0-9\+\-\s\(\)]+$/;
                        if (!value) {
                            errorMessage = 'Telefoonnummer is verplicht.';
                            isValid = false;
                        } else if (value.length < 10) {
                            errorMessage = 'Telefoonnummer moet minimaal 10 karakters bevatten.';
                            isValid = false;
                        } else if (value.length > 20) {
                            errorMessage = 'Telefoonnummer mag maximaal 20 karakters bevatten.';
                            isValid = false;
                        } else if (!phoneRegex.test(value)) {
                            errorMessage = 'Telefoonnummer bevat ongeldige karakters.';
                            isValid = false;
                        }
                        break;

                    case 'email':
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!value) {
                            errorMessage = 'E-mailadres is verplicht.';
                            isValid = false;
                        } else if (!emailRegex.test(value)) {
                            errorMessage = 'E-mailadres moet geldig zijn.';
                            isValid = false;
                        } else if (value.length > 255) {
                            errorMessage = 'E-mailadres mag maximaal 255 karakters bevatten.';
                            isValid = false;
                        }
                        break;

                    case 'address':
                        if (!value) {
                            errorMessage = 'Adres is verplicht.';
                            isValid = false;
                        } else if (value.length < 5) {
                            errorMessage = 'Adres moet minimaal 5 karakters bevatten.';
                            isValid = false;
                        } else if (value.length > 500) {
                            errorMessage = 'Adres mag maximaal 500 karakters bevatten.';
                            isValid = false;
                        }
                        break;

                    case 'supplier_type':
                        if (!value) {
                            errorMessage = 'Leverancier type is verplicht.';
                            isValid = false;
                        } else if (!['Supermarket', 'Farmer', 'Wholesaler', 'Individual'].includes(value)) {
                            errorMessage = 'Geselecteerd leverancier type is ongeldig.';
                            isValid = false;
                        }
                        break;

                    case 'opmerking':
                        if (value.length > 1000) {
                            errorMessage = 'Opmerking mag maximaal 1000 karakters bevatten.';
                            isValid = false;
                        }
                        break;
                }

                if (!isValid) {
                    showError(field, errorMessage);
                }

                return isValid;
            }

            function showError(field, message) {
                const errorDiv = field.parentElement.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.textContent = message;
                    errorDiv.classList.remove('hidden');
                }
                field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
            }

            function clearError(field) {
                const errorDiv = field.parentElement.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.classList.add('hidden');
                }
                field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
            }
        });
    </script>
</x-app-layout>
