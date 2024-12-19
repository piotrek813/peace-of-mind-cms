document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to all group field headers
    document.querySelectorAll('.group-field-header').forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('.collapse-icon');
            
            // Toggle the content visibility
            content.classList.toggle('collapsed');
            
            // Rotate the icon
            icon.classList.toggle('rotate-180');
        });
    });

    // List field functionality
    document.querySelectorAll('.list-field').forEach(listField => {
        const addButton = listField.querySelector('.add-item');
        const modal = listField.querySelector('.field-search-modal');
        const searchInput = modal.querySelector('.field-search');
        const fieldOptions = modal.querySelectorAll('.field-option');
        const listItems = listField.querySelector('.list-items');
        let itemCount = listField.querySelectorAll('.list-item').length;

        addButton.addEventListener('click', () => {
            modal.showModal();
            searchInput.value = '';
            searchInput.focus();
            fieldOptions.forEach(opt => opt.style.display = '');
        });

        searchInput.addEventListener('input', (e) => {
            const search = e.target.value.toLowerCase();
            fieldOptions.forEach(option => {
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(search) ? '' : 'none';
            });
        });

        fieldOptions.forEach(option => {
            option.addEventListener('click', () => {
                const newItem = createListItem(
                    listField.dataset.name,
                    option.dataset.fieldKey,
                    itemCount++,
                    option.textContent.trim()
                );
                listItems.insertAdjacentHTML('beforeend', newItem);
                initializeListItem(listItems.lastElementChild);
                modal.close();
            });
        });

        // Initialize existing items
        listItems.querySelectorAll('.list-item').forEach(initializeListItem);
    });

    function createListItem(listName, fieldKey, index, label) {
        return `
            <div class="list-item bg-base-200 rounded-lg mb-4" data-index="${index}">
                <div class="flex items-center p-4 border-b border-base-300">
                    <button type="button" class="drag-handle mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </button>
                    <span class="font-medium">${label}</span>
                    <button type="button" class="delete-item ml-auto text-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">${label}</span>
                        </label>
                        <input type="text" 
                               name="${listName}[${index}][${fieldKey}]" 
                               class="input input-bordered w-full" 
                               value="">
                    </div>
                </div>
            </div>
        `;
    }

    function initializeListItem(item) {
        const deleteBtn = item.querySelector('.delete-item');
        deleteBtn.addEventListener('click', () => {
            item.remove();
        });

        // Make item draggable
        item.setAttribute('draggable', true);
        
        item.addEventListener('dragstart', e => {
            e.target.classList.add('dragging');
        });

        item.addEventListener('dragend', e => {
            e.target.classList.remove('dragging');
        });

        item.addEventListener('dragover', e => {
            e.preventDefault();
            const draggingItem = document.querySelector('.dragging');
            const listItems = item.parentNode;
            const siblings = [...listItems.querySelectorAll('.list-item:not(.dragging)')];
            
            const nextSibling = siblings.find(sibling => {
                const rect = sibling.getBoundingClientRect();
                return e.clientY < rect.top + rect.height / 2;
            });

            listItems.insertBefore(draggingItem, nextSibling);
        });
    }
}); 