<form id="dynamicForm" method="POST" action="{{ route('endpoints.store') }}" class="w-full max-w-4xl mx-auto p-4 bg-white rounded-lg shadow-md">
    @csrf
    
    {{-- Name field --}}
    <div class="mb-6">
        <label for="name" class="block text-gray-700 font-semibold mb-2">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Dynamic form groups container --}}
    <div id="formGroupsContainer" class="mb-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Fields</h2>
        
        {{-- This container will hold all the dynamic form groups --}}
        <div id="groups-root" class="space-y-4">
            @if(old('inputs'))
                @foreach(old('inputs') as $index => $input)
                    @include('endpoints.partials.form-group', [
                        'index' => $index,
                        'level' => 0,
                        'input' => $input,
                        'categories' => $categories,
                        'parentId' => 'groups-root',
                        'fieldPrefix' => "inputs[$index]"
                    ])
                @endforeach
            @else
                {{-- Default initial group --}}
                @include('endpoints.partials.form-group', [
                    'index' => 0,
                    'level' => 0,
                    'input' => null,
                    'categories' => $categories,
                    'parentId' => 'groups-root',
                    'fieldPrefix' => "inputs[0]"
                ])
            @endif
        </div>
        
        {{-- Add group button --}}
        <div class="mt-4">
            <button type="button" id="addGroupBtn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Add Field
            </button>
        </div>
    </div>
    
    {{-- Submit button --}}
    <div class="mt-6">
        <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
            Submit Form
        </button>
    </div>
</form>

{{-- Form group template (for JavaScript cloning) --}}
<template id="formGroupTemplate">
    <div class="form-group bg-gray-50 p-4 rounded-lg border border-gray-200 relative" data-level="__LEVEL__" data-group-id="__GROUP_ID__" data-has-nested="false">
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

{{-- JavaScript for dynamic form handling --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const formGroupsContainer = document.getElementById('groups-root');
    const addGroupBtn = document.getElementById('addGroupBtn');
    const formGroupTemplate = document.getElementById('formGroupTemplate');
    let groupCounter = document.querySelectorAll('.form-group').length;
    
    // Initialize sortable functionality for root level
    makeSortable(formGroupsContainer);
    
    // Add root level group
    addGroupBtn.addEventListener('click', function() {
        addGroup(formGroupsContainer, 0);
    });
    
    // Initialize existing groups
    document.querySelectorAll('.form-group').forEach(initializeGroup);
    
    // Setup category change listeners for existing groups
    document.querySelectorAll('.category-select').forEach(select => {
        select.addEventListener('change', handleCategoryChange);
    });
    
    // Add a new group to the specified container
    function addGroup(container, level, inputData = null, parentPrefix = 'inputs') {
        const index = groupCounter++;
        const groupId = `group-${Date.now()}-${index}`;
        let newGroup = formGroupTemplate.innerHTML
            .replace(/__INDEX__/g, index)
            .replace(/__LEVEL__/g, level)
            .replace(/__GROUP_ID__/g, groupId);
        
        // Create the field prefix based on the parent's prefix
        let fieldPrefix;
        if (level === 0) {
            fieldPrefix = `${parentPrefix}[${container.children.length}]`;
        } else {
            fieldPrefix = `${parentPrefix}[nested][${container.children.length}]`;
        }
        
        newGroup = newGroup.replace(/__PREFIX__/g, fieldPrefix);
        
        // Replace categories with actual options
        let categoryOptions = '';
        @foreach($categories as $id => $category)
            categoryOptions += `<option value="{{ $id }}">{{ $category['label'] }}</option>`;
        @endforeach
        newGroup = newGroup.replace('__CATEGORIES_OPTIONS__', categoryOptions);
        
        // Create element
        const groupDiv = document.createElement('div');
        groupDiv.innerHTML = newGroup;
        const groupElement = groupDiv.firstElementChild;
        groupElement.dataset.fieldPrefix = fieldPrefix;
        groupElement.dataset.hasNested = 'false';
        
        // Add to container
        container.appendChild(groupElement);
        
        // Fill with data if provided
        if (inputData) {
            groupElement.querySelector('.key-input').value = inputData.key || '';
            
            const categorySelect = groupElement.querySelector('.category-select');
            if (inputData.category) {
                categorySelect.value = inputData.category;
                populateContextOptions(categorySelect, inputData.context);
            }
            
            // Handle nested groups
            if (inputData.nested && inputData.nested.length > 0) {
                const nestedContainer = groupElement.querySelector('.nested-groups-container');
                inputData.nested.forEach((nestedInput, nestedIndex) => {
                    addGroup(nestedContainer, level + 1, nestedInput, fieldPrefix);
                });
                
                // Hide category and context for groups with nested elements
                groupElement.dataset.hasNested = 'true';
                hideContextAndCategory(groupElement);
            }
        }
        
        // Initialize the new group
        initializeGroup(groupElement);
        
        // If this is a nested group, hide category and context in the parent
        if (level > 0) {
            const parentGroup = container.closest('.form-group');
            if (parentGroup) {
                parentGroup.dataset.hasNested = 'true';
                hideContextAndCategory(parentGroup);
            }
        }
        
        return groupElement;
    }
    
    // Hide category and context fields for a group
    function hideContextAndCategory(group) {
        const categoryContainer = group.querySelector('.category-container');
        const contextContainer = group.querySelector('.context-container');
        const categorySelect = group.querySelector('.category-select');
        const contextSelect = group.querySelector('.context-select');
        
        if (categoryContainer) categoryContainer.style.display = 'none';
        if (contextContainer) contextContainer.style.display = 'none';
        
        // Disable the selects to prevent them from being submitted
        if (categorySelect) categorySelect.disabled = true;
        if (contextSelect) contextSelect.disabled = true;
    }
    
    // Show category and context fields for a group
    function showContextAndCategory(group) {
        const categoryContainer = group.querySelector('.category-container');
        const contextContainer = group.querySelector('.context-container');
        const categorySelect = group.querySelector('.category-select');
        const contextSelect = group.querySelector('.context-select');
        
        if (categoryContainer) categoryContainer.style.display = '';
        if (contextContainer) contextContainer.style.display = '';
        
        // Enable the selects
        if (categorySelect) categorySelect.disabled = false;
        if (contextSelect) contextSelect.disabled = false;
    }
    
    // Initialize group event listeners
    function initializeGroup(group) {
        const level = parseInt(group.dataset.level);
        const nestedContainer = group.querySelector('.nested-groups-container');
        
        // Make nested container sortable
        makeSortable(nestedContainer);
        
        // Add nested group button
        group.querySelector('.add-nested-btn').addEventListener('click', function() {
            const fieldPrefix = group.dataset.fieldPrefix;
            addGroup(nestedContainer, level + 1, null, fieldPrefix);
            
            // Hide category and context fields since we now have nested groups
            group.dataset.hasNested = 'true';
            hideContextAndCategory(group);
            
            updateFormStructure();
        });
        
        // Remove group button
        group.querySelector('.remove-group-btn').addEventListener('click', function() {
            const parentContainer = group.parentElement;
            group.remove();
            
            // Check if parent group has any nested groups left
            if (parentContainer && parentContainer.classList.contains('nested-groups-container')) {
                const parentGroup = parentContainer.closest('.form-group');
                if (parentGroup && parentContainer.children.length === 0) {
                    parentGroup.dataset.hasNested = 'false';
                    showContextAndCategory(parentGroup);
                }
            }
            
            updateFormStructure();
        });
        
        // Category select
        group.querySelector('.category-select').addEventListener('change', handleCategoryChange);
        
        // Move up button
        group.querySelector('.move-up-btn').addEventListener('click', function() {
            const parent = group.parentElement;
            const prev = group.previousElementSibling;
            if (prev && prev.classList.contains('form-group')) {
                parent.insertBefore(group, prev);
                updateFormStructure();
            }
        });
        
        // Move down button
        group.querySelector('.move-down-btn').addEventListener('click', function() {
            const parent = group.parentElement;
            const next = group.nextElementSibling;
            if (next && next.classList.contains('form-group')) {
                parent.insertBefore(next, group);
                updateFormStructure();
            }
        });
    }
    
    // Handle category change
    function handleCategoryChange(e) {
        const categorySelect = e.target;
        const categoryId = categorySelect.value;
        
        populateContextOptions(categorySelect);
    }
    
    // Populate context options based on selected category
    function populateContextOptions(categorySelect, selectedValue = null) {
        const categoryId = categorySelect.value;
        const group = categorySelect.closest('.form-group');
        const contextSelect = group.querySelector('.context-select');
        
        // Clear existing options
        contextSelect.innerHTML = '<option value="">Select an option</option>';
        
        if (categoryId) {
            // Get options for this category
            const options = getCategoryOptions(categoryId);
            
            // Add options to the context select
            options.forEach(option => {
                const optElement = document.createElement('option');
                optElement.value = option.value;
                optElement.textContent = option.label;
                contextSelect.appendChild(optElement);
            });
            
            // Set selected value if provided
            if (selectedValue) {
                contextSelect.value = selectedValue;
            }
        }
    }
    
    // Get options for a category
    function getCategoryOptions(categoryId) {
        const categoryOptions = {
            @foreach($categories as $id => $category)
                {{ $id }}: [
                    @foreach($category['inputs'] ?? [] as $input)
                        { value: '{{ $input['value'] }}', label: '{{ $input['label'] }}' },
                    @endforeach
                ],
            @endforeach
        };
        
        return categoryOptions[categoryId] || [];
    }
    
    // Make a container sortable
    function makeSortable(container) {
        if (!container) return;
        
        new Sortable(container, {
            group: { 
                name: `level-${container.dataset.level || 0}`,
                pull: false, // Don't allow dragging to other containers
                put: false // Don't allow dropping from other containers
            },
            animation: 150,
            draggable: '.form-group',
            onEnd: function() {
                updateFormStructure();
            }
        });
    }
    
    // Update the entire form structure and rename all inputs properly
    function updateFormStructure() {
        updateContainerStructure(document.getElementById('groups-root'), 'inputs');
    }
    
    // Update form structure recursively
    function updateContainerStructure(container, parentPrefix) {
        // Skip if not a valid container
        if (!container) return;
        
        Array.from(container.children).forEach((group, index) => {
            if (!group.classList.contains('form-group')) return;
            
            // Update group index display
            const groupTitle = group.querySelector('h3');
            if (groupTitle) {
                groupTitle.textContent = `Field #${index}`;
            }
            
            // Create the field prefix for this group
            let fieldPrefix;
            const level = parseInt(group.dataset.level);
            
            if (level === 0) {
                fieldPrefix = `${parentPrefix}[${index}]`;
            } else {
                fieldPrefix = `${parentPrefix}[nested][${index}]`;
            }
            
            // Store the prefix for future reference
            group.dataset.fieldPrefix = fieldPrefix;
            
            // Update all input names in this group
            const keyInput = group.querySelector('.key-input');
            if (keyInput) {
                keyInput.name = `${fieldPrefix}[key]`;
            }
            
            const categorySelect = group.querySelector('.category-select');
            if (categorySelect) {
                categorySelect.name = `${fieldPrefix}[category]`;
            }
            
            const contextSelect = group.querySelector('.context-select');
            if (contextSelect) {
                contextSelect.name = `${fieldPrefix}[context]`;
            }
            
            // Update nested groups recursively
            const nestedContainer = group.querySelector('.nested-groups-container');
            if (nestedContainer) {
                updateContainerStructure(nestedContainer, fieldPrefix);
            }
        });
    }
    
    // Update form structure on page load
    updateFormStructure();
    
    // Check and update nested status for all groups
    document.querySelectorAll('.form-group').forEach(group => {
        const nestedContainer = group.querySelector('.nested-groups-container');
        if (nestedContainer && nestedContainer.children.length > 0) {
            group.dataset.hasNested = 'true';
            hideContextAndCategory(group);
        }
    });
    
    // Validate form before submission
    document.getElementById('dynamicForm').addEventListener('submit', function(e) {
        const groups = document.querySelectorAll('#groups-root > .form-group');
        if (groups.length === 0) {
            e.preventDefault();
            alert('Please add at least one endpoint field before submitting.');
        }
    });
});
</script>