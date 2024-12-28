function showArrayData(button) {
    const data = JSON.parse(button.dataset.array);
    const modal = button.nextElementSibling;
    modal.querySelector('.array-content').textContent = JSON.stringify(data, null, 2);
    modal.showModal();
} 

function openJsonEditor(button) {
    const modal = document.getElementById('json-editor-modal');
    const editor = document.getElementById('json-editor');
    
    // Store entry data in modal dataset
    modal.dataset.entryId = button.dataset.entryId;
    modal.dataset.type = button.dataset.type;
    
    // Format and set JSON in editor
    const json = JSON.parse(button.dataset.json);
    editor.value = JSON.stringify(json, null, 2);
    
    modal.showModal();
}

async function saveJson() {
    const modal = document.getElementById('json-editor-modal');
    const editor = document.getElementById('json-editor');
    
    try {
        // Validate JSON
        const json = JSON.parse(editor.value);
        
        // Send to server
        const response = await fetch('save-json', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: modal.dataset.entryId,
                type: modal.dataset.type,
                data: json
            })
        });
        
        if (!response.ok) alert('Failed to save');

        window.location.reload();
    } catch (e) {
        alert('Invalid JSON format');
    }
}