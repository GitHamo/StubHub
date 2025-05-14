{{-- Form group template (for JavaScript cloning) --}}
<template id="formGroupTemplate">
    <div class="form-group bg-gray-50 p-4 rounded-lg border border-gray-200 cursor-grab active:cursor-grabbing relative" data-level="__LEVEL__" data-group-id="__GROUP_ID__" data-has-nested="false">
        <div class="flex justify-between mb-3">
            <h3 class="font-medium text-gray-700">Field #__INDEX__</h3>
            <div class="flex space-x-2">
                <button type="button" class="move-up-btn text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button type="button" class="move-down-btn text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button type="button" class="remove-group-btn text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            {{-- Key input --}}
            <div>
                <label class="block text-gray-700 mb-1">Key</label>
                <input type="text" name="__PREFIX__[key]" class="key-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Repeat input - Only show if has nested groups --}}
            <div class="repeat-container mb-4" style="display:none;">
                <label class="block text-gray-700 mb-1">Repeat</label>
                <input
                    type="number"
                    name="__PREFIX__[repeat]"
                    class="repeat-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="0"
                    min="0"
                    disabled
                >
            </div>

            {{-- Category select --}}
            <div class="category-container">
                <label class="block text-gray-700 mb-1">Category</label>
                <select name="__PREFIX__[category]" class="category-select w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select a category</option>
                    __CATEGORIES_OPTIONS__
                </select>
            </div>
        </div>

        {{-- Context select --}}
        <div class="context-container mb-4">
            <label class="block text-gray-700 mb-1">Context</label>
            <select name="__PREFIX__[context]" class="context-select w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select category first</option>
            </select>
        </div>

        {{-- Nested groups container --}}
        <div class="nested-groups-container mt-4 pl-4 border-l-2 border-gray-300 space-y-3">
            {{-- Nested groups will be added here --}}
        </div>

        {{-- Add nested group button --}}
        <div class="mt-3">
            <button type="button" class="add-nested-btn px-3 py-1 bg-indigo-500 text-white text-sm rounded hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Add Nested Field
            </button>
        </div>
    </div>
</template>
