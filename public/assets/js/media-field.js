function openMediaModal(field) {
    const modal = document.getElementById('media-modal');

    modal.dataset.multi = field.closest('.media-field').dataset.multi;

    // maybe be should unregister the listener here
    modal.addEventListener('close', function() {
        const selected = JSON.parse(modal.dataset.value);
        updatePreview(field.closest('.media-field').querySelector('.media-preview'), selected);
    }, { once: true });

    modal.showModal();
}

function updatePreview(mediaPreview, items) {
    const previewTemplate = document.getElementById('media-preview-template').content;

    if (mediaPreview.closest('.media-field').dataset.multi === 'false') {
        mediaPreview.innerHTML = '';
    }

    const preview = Array.from(items).map(item => {
        const clone = previewTemplate.cloneNode(true);
        clone.querySelector('img').src = item.url;
        clone.querySelector('img').alt = item.name;
        clone.querySelector('input').value = item.id;
        return clone;
    });

    mediaPreview.append(...preview);

    var a = mediaPreview.closest("[data-index]");
    var indexes = [];
    while (a ) {
        indexes.push(a.dataset.index);
        a = a.parentNode.closest("[data-index]");
    }

    indexes.reverse().forEach((index, _) => {
        mediaPreview.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace("{{index}}", index);
        });
    }); 
}

function removeMedia(button, event) {
    event.stopPropagation(); // Prevent modal from opening when removing
    const mediaItem = button.closest('.relative');

    mediaItem.remove();
}