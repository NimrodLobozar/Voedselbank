<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
        <x-dashboard.system-controls :isMaintenanceMode="$isMaintenanceMode" />
</x-app-layout>

<script>
    // Verify Chart.js loaded
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js failed to load!');
            alert('Error: Chart.js could not be loaded. Some dashboard features may not work.');
        } else {
            console.log('Chart.js loaded successfully');
        }

        // Collapsible functionality
        const collapsibleHeaders = document.querySelectorAll('.collapsible-header');

        // Save collapsed state to localStorage
        function saveCollapsedState(id, isCollapsed) {
            localStorage.setItem('collapsed-' + id, isCollapsed);
        }

        // Get collapsed state from localStorage with default to collapsed
        function getCollapsedState(id) {
            const saved = localStorage.getItem('collapsed-' + id);
            // If no saved state, default to collapsed (true)
            return saved === null ? true : saved === 'true';
        }

        collapsibleHeaders.forEach(header => {
            const targetId = header.dataset.target;
            const content = document.getElementById(targetId);
            const arrow = header.querySelector('.collapsible-arrow');

            // Set initial state from localStorage or default to collapsed
            const isCollapsed = getCollapsedState(targetId);
            if (isCollapsed) {
                content.style.maxHeight = '0px';
                content.style.overflow = 'hidden';
                arrow.classList.add('rotate-180');
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
            }

            header.addEventListener('click', () => {
                const isCollapsed = content.style.maxHeight === '0px' || content.style
                    .maxHeight === '';

                if (isCollapsed) {
                    content.style.maxHeight = content.scrollHeight + 'px';
                    arrow.classList.remove('rotate-180');
                    saveCollapsedState(targetId, false);
                } else {
                    content.style.maxHeight = '0px';
                    arrow.classList.add('rotate-180');
                    saveCollapsedState(targetId, true);
                }
            });
        });
    });
</script>
<style>
    .collapsible-content {
        max-height: 0px;
        /* Default to collapsed */
        overflow: hidden;
        transition: max-height 0.3s ease-in-out;
    }

    .rotate-180 {
        transform: rotate(180deg);
    }

    .collapsible-header {
        transition: background-color 0.2s ease;
    }

    .collapsible-header:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
</style>
