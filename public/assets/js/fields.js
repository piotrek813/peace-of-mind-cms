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
        const fieldConfigs = JSON.parse(listField.dataset.fields);

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

        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                if (searchInput.value === "" || fieldOptions.length === 0) return;

                insertField([...fieldOptions].filter(opt => opt.style.display !== 'none')[0]);
            }
        });

        fieldOptions.forEach(option => {
            option.addEventListener('click', () => insertField(option));
        });

        // Initialize existing items
        listItems.querySelectorAll('.list-item').forEach(initializeListItem);

        function insertField(option) {
            const fieldKey = option.dataset.fieldKey;
            const fieldConfig = fieldConfigs[fieldKey];
            const newItem = createListItem(
                listField.dataset.name,
                fieldKey,
                itemCount++,
                fieldConfig
            );
            listItems.appendChild(newItem);
            initializeListItem(listItems.lastElementChild);
            modal.close();
        }
    });


    function createListItem(listName, fieldKey, index, config) {
        const template = document.getElementById('list-item-template');
        const clone = template.content.cloneNode(true);
        
        const listItem = clone.querySelector('[data-index]');
        listItem.dataset.index = index;
        
        const label = listItem.querySelector('.font-medium');
        label.textContent = config.label;

        clone.querySelector('.list-item-content').appendChild(createFieldContent(listName, fieldKey, index, config));
        
        return clone;
    }

    function createFieldContent(listName, fieldKey, index, config) {
        const name = `${listName}[${index}][${fieldKey}]`;
        const templateId = `field-${fieldKey}-template`;
        const template = document.getElementById(templateId);
        const clone = template.content.cloneNode(true);

        if (!template) {
            return `<div class="text-error">Unknown field type: ${config.type}</div>`;
        }

        clone.id = name;
        clone.name = name;

        return clone;
    }

    function initializeListItem(item) {
        const deleteBtn = item.querySelector('.delete-item');
        deleteBtn.addEventListener('click', () => {
            item.remove();
        });

        // Add collapse functionality
        const header = item.querySelector('.list-item-header');
        const content = item.querySelector('.list-item-content');
        const icon = header.querySelector('.collapse-icon');
        
        header.addEventListener('click', (e) => {
            // Don't collapse if clicking delete or drag buttons
            if (e.target.closest('.delete-item') || e.target.closest('.drag-handle')) {
                return;
            }
            content.classList.toggle('collapsed');
            icon.classList.toggle('rotate-180');
        });

        // Make drag handle draggable
        const dragHandle = item.querySelector(':has(>.drag-handle)');
        item.setAttribute('draggable', false);
        
        dragHandle.addEventListener('mousedown', () => {
            item.draggable = true;
        });

        dragHandle.addEventListener('mouseup', () => {
            item.draggable = false; 
        });

        item.addEventListener('dragstart', e => {
            e.target.classList.add('dragging');
        });

        item.addEventListener('dragend', e => {
            e.target.classList.remove('dragging');
            item.draggable = false;
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