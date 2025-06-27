      <!-- Developer Tools (Only visible in development) -->
                                        <div class="mb-8 bg-yellow-50 p-5 rounded-lg shadow-sm border border-yellow-200">
                                            <h3 class="text-lg font-medium text-yellow-700 mb-4 pb-2 border-b-2 border-yellow-200 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                                </svg>
                                                Developer Tools
                                            </h3>
                                            
                                            <div class="mb-3">
                                                <input type="hidden" name="simulate_error" id="simulate_error_input" value="0">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" id="simulate_error_checkbox" class="hidden">
                                                    <span class="relative inline-block w-10 h-5 rounded-full bg-gray-300 transition-colors ease-in-out duration-200 mr-3" id="simulate_error_toggle">
                                                        <span class="simulate-error-slider absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform"></span>
                                                    </span>
                                                    <span class="text-sm font-medium text-yellow-700">Simuleer technische fout</span>
                                                </label>
                                                <p class="text-xs text-yellow-600 mt-1">Alleen voor testdoeleinden. Hiermee kun je een fout simuleren tijdens het aanmaken van een factuur.</p>
                                            </div>
                                        </div>

        <script>
            // Toggle functionality for simulate error
            const simulateErrorCheckbox = document.getElementById('simulate_error_checkbox');
            const simulateErrorInput = document.getElementById('simulate_error_input');
            const simulateErrorToggle = document.getElementById('simulate_error_toggle');
            
            if (simulateErrorCheckbox && simulateErrorInput && simulateErrorToggle) {
                simulateErrorCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        simulateErrorInput.value = '1';
                        simulateErrorToggle.classList.remove('bg-gray-300');
                        simulateErrorToggle.classList.add('bg-yellow-500');
                        document.querySelector('.simulate-error-slider').classList.add('translate-x-5');
                    } else {
                        simulateErrorInput.value = '0';
                        simulateErrorToggle.classList.remove('bg-yellow-500');
                        simulateErrorToggle.classList.add('bg-gray-300');
                        document.querySelector('.simulate-error-slider').classList.remove('translate-x-5');
                    }
                });
            }
        </script>

                                        <!-- End Developer Tools -->