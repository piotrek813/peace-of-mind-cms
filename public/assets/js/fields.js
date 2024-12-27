document.addEventListener('DOMContentLoaded', function() {
    initializeAllFields();
});

function initializeAllFields() {
    // Initialize all collapsible headers
    document.querySelectorAll('.collapseble-header').forEach(initializeHeader);

    // Initialize all list fields
    document.querySelectorAll('.list-field').forEach(initializeListField);
}

function initializeHeader(header) {
    // Remove existing listener if any
    header.removeEventListener('click', handleHeaderClick);
    // Add click handler
    header.addEventListener('click', handleHeaderClick);
}

function handleHeaderClick(e) {
    const content = this.nextElementSibling;
    const icon = this.querySelector('.collapse-icon');
    
    // Toggle the content visibility
    content.classList.toggle('collapsed');
    
    // Rotate the icon
    icon.classList.toggle('rotate-180');
}

function initializeListField(listField) {
    const addButton = listField.querySelector('.add-item');
    const modal = listField.querySelector('.field-search-modal');
    const searchInput = modal.querySelector('.field-search');
    const fieldOptions = modal.querySelectorAll('.field-option');
    const listItems = listField.querySelector('.list-items');
    const fieldConfigs = JSON.parse(listField.dataset.fields);

    let itemCount = listField.querySelectorAll('.list-item').length;

    addButton.addEventListener('click', (e) => {
        e.stopPropagation();
        
        if (fieldOptions.length === 1) {
            insertField(fieldOptions[0]);
            return;
        }

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
            const visibleOptions = [...fieldOptions].filter(opt => opt.style.display !== 'none');

            if (visibleOptions.length === 0) return;

            insertField(visibleOptions[0]);
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

        // Initialize the new item and any nested fields
        const addedItem = listItems.lastElementChild;


        addedItem.querySelectorAll('.collapseble-header').forEach(initializeHeader);
        addedItem.querySelectorAll('.list-field').forEach(initializeListField);

        initializeListItem(addedItem);
        modal.close();
    }
}

function createListItem(listName, fieldKey, index, config) {
    const template = document.getElementById('list-item-template');
    const clone = template.content.cloneNode(true);
    
    const listItem = clone.querySelector('[data-index]');
    listItem.dataset.index = index;
    
    const label = listItem.querySelector('.font-medium');
    label.textContent = config.label;

    clone.querySelector('.list-item-content').appendChild(createFieldContent(listName, fieldKey, config));
    
    return clone;
}

function createFieldContent(listName, fieldKey, config) {
    const templateId = `field-${listName}-${fieldKey}-template`;
    const template = document.getElementById(templateId);

    if (!template) {
        const error = document.createElement('div');
        error.classList.add('text-error');
        error.textContent = `Unknown field type: ${config.type}`;

        console.error(`${templateId} not found`);

        return error;
    }

    const clone = template.content.cloneNode(true);

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

        // Fill in the indexes in the name attribute for the new item
    var a = item.closest("[data-index]");
    var indexes = [];
    while (a ) {
        indexes.push(a.dataset.index);
        a = a.parentNode.closest("[data-index]");
    }

    indexes.reverse().forEach((index, _) => {
        item.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace("{{index}}", index);
        });
    }); 
    
    header.addEventListener('click', (e) => {
        // Don't collapse if clicking delete button
        if (e.target.closest('.delete-item')) {
            return;
        }
        content.classList.toggle('collapsed');
        icon.classList.toggle('rotate-180');
    });

    // Drag and drop functionality
    header.addEventListener('mousedown', (e) => {
        // Don't initiate drag if clicking delete button
        if (e.target.closest('.delete-item')) {
            return;
        }
        item.draggable = true;
    });
    
    header.addEventListener('mouseup', () => {
        item.draggable = false;
    });
    
    item.addEventListener('dragstart', (e) => {
        e.target.closest('.list-item').classList.add('dragging');
    });
    
    item.addEventListener('dragend', (e) => {
        e.target.closest('.list-item').classList.remove('dragging');
        item.draggable = false;
    });
    
    item.addEventListener('dragover', e => {
        e.preventDefault();
        e.stopPropagation();
        
        const parentList = item.parentElement.closest('.list-items');
        const draggingItem = parentList.querySelector(':scope > .list-item.dragging');
        
        if (!draggingItem || draggingItem === item || draggingItem.parentElement !== parentList) return;
        
        const rect = item.getBoundingClientRect();
        const offset = e.clientY - rect.top;
        const threshold = rect.height / 2;
        
        if (offset < threshold) {
            parentList.insertBefore(draggingItem, item);
        } else {
            parentList.insertBefore(draggingItem, item.nextElementSibling);
        }
    });
} 

