<!-- Developer Tools (Only visible in development) -->
<div class="mb-8 bg-yellow-50 p-5 rounded-lg shadow-sm border border-yellow-200">
    <h3 class="text-lg font-medium text-yellow-700 mb-4 pb-2 border-b-2 border-yellow-200 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
        </svg>
        Developer Tools
    </h3>
    
    <div class="mb-3">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" id="dataToggle" class="hidden" checked>
            <span class="relative inline-block w-10 h-5 rounded-full bg-green-500 transition-colors ease-in-out duration-200 mr-3" id="dataToggleSwitch">
                <span class="data-toggle-slider absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform translate-x-5"></span>
            </span>
            <span class="text-sm font-medium text-yellow-700">Toon Data</span>
        </label>
        <p class="text-xs text-yellow-600 mt-1">Toggle om de klantgegevens te tonen of te verbergen voor testdoeleinden.</p>
    </div>
</div>

<script>
    // Toggle functionality for data visibility
    const dataToggle = document.getElementById('dataToggle');
    const dataToggleSwitch = document.getElementById('dataToggleSwitch');
    const dataContainer = document.getElementById('dataContainer');
    const errorContainer = document.getElementById('errorContainer');
    
    if (dataToggle && dataToggleSwitch && dataContainer && errorContainer) {
        dataToggle.addEventListener('change', function() {
            if (this.checked) {
                // Show data, hide error
                dataContainer.classList.remove('hidden');
                errorContainer.classList.add('hidden');
                dataToggleSwitch.classList.remove('bg-red-500');
                dataToggleSwitch.classList.add('bg-green-500');
                document.querySelector('.data-toggle-slider').classList.add('translate-x-5');
            } else {
                // Hide data, show error
                dataContainer.classList.add('hidden');
                errorContainer.classList.remove('hidden');
                dataToggleSwitch.classList.remove('bg-green-500');
                dataToggleSwitch.classList.add('bg-red-500');
                document.querySelector('.data-toggle-slider').classList.remove('translate-x-5');
            }
        });
    }
</script>
<!-- End Developer Tools -->