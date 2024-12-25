function openMediaModal(field) {
    const modal = document.getElementById('media-modal');
    const input = field.closest('.media-field').querySelector('input');

    modal.dataset.field = input.name;
    modal.addEventListener('close', function() {
        const selected = JSON.parse(input.value);
        updatePreview(field.closest('.media-field').querySelector('.media-preview'), selected);
    });
    modal.showModal();
}

function updatePreview(mediaPreview, items) {
    const preview = Array.from(items).map(item => `
        <div class="relative group">
            <img src="${item.url}" alt="${item.name}" class="w-full h-32 object-cover rounded">
            <button type="button" 
                    onclick="removeMedia(this, event)" 
                    class="btn btn-sm btn-circle btn-error absolute top-1 right-1 opacity-0 group-hover:opacity-100">
                ✕
            </button>
        </div>
    `).join('');

    mediaPreview.innerHTML = preview;
}

function removeMedia(button, event) {
    event.stopPropagation(); // Prevent modal from opening when removing
    const mediaItem = button.closest('.relative');
    const preview = mediaItem.parentElement;
    const input = preview.closest('.form-control').querySelector('input[type="hidden"]');
    
    const currentValue = JSON.parse(input.value || '[]');
    const index = Array.from(preview.children).indexOf(mediaItem);
    
    currentValue.splice(index, 1);
    input.value = JSON.stringify(currentValue);
    
    mediaItem.remove();
}