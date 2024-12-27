function hideSelectionActions() {
    document.getElementById('selection-actions').style.display = 'none';
}

function handleSelection() {
    const selectionActions = document.getElementById('selection-actions');
    const selectedMedia = document.querySelectorAll('.media-item:has(input:checked)');

    const copyUrlButton = document.getElementById('copy-url');
    selectionActions.style.display = selectedMedia.length > 0 ? 'flex' : 'none';

    if (selectedMedia.length === 1) {
        copyUrlButton.style.display = "flex";
    } else {
        copyUrlButton.style.display = "none";
    }
}

function selectMedia() {
    const modal = document.getElementById('media-modal');
    modal.close();

    const selectedItems = document.querySelectorAll('.media-item:has(input:checked)');
    const selected = Array.from(selectedItems).map(e => ({
        id: e.dataset.id,
        name: e.querySelector('img').alt,
        url: e.querySelector('img').src
    }));


    const input = document.querySelector(`input[name="${modal.dataset.field}"]`);
    input.value = JSON.stringify([...selected, ...JSON.parse(input.value)]);

    clearSelection();
}

function clearSelection() {
    Array.from(document.querySelectorAll('.media-item > input')).map(e => {
        e.checked = false;
    });

    hideSelectionActions();
}

function copyUrl() {
    hideSelectionActions();
    const url = document.querySelector('.media-item:has(input:checked)').querySelector('img').src;
    document.querySelector('.media-item > input:checked').checked = false;
    navigator.clipboard.writeText(url).then(() => {

    });
}

function downloadMedia() {
    hideSelectionActions();
    const urls = Array.from(document.querySelectorAll('.media-item:has(input:checked)')).map(e => {
        e.querySelector('input:checked').checked = false;
        const name = e.querySelector('img').alt;
        const url = e.querySelector('img').src;
        return {
            name,
            url
        };
    });

    urls.forEach(url => {
        const a = document.createElement('a');
        a.href = url.url;
        a.download = url.name;
        a.click();
    });
}

async function uploadMedia() {
    hideSelectionActions();
    const formData = new FormData(document.getElementById('upload-media').closest('form'));

    const response = await fetch('media-library/upload', {
        method: 'POST',
        body: formData,
    });

    const data = await response.json();
    if (data.success) {
        for (const media of data.media) {
            const mediaGrid = document.getElementById('media-grid');
            const mediaItem = document.getElementById('media-item-template')
                .content.cloneNode(true);

            mediaItem.firstElementChild.outerHTML = mediaItem.firstElementChild.outerHTML
                .replaceAll(/{{id}}/g, media.id)
                .replaceAll(/{{url}}/g, media.url)
                .replaceAll(/{{name}}/g, media.name)
                .replaceAll(/{{formattedSize}}/g, media.formatted_size);

            mediaGrid.appendChild(mediaItem);
            document.getElementById('no-media-message').style.display = 'none';
        }
    }
}

async function deleteMedia() {
    hideSelectionActions();
    if (confirm('Are you sure you want to delete this media item?')) {
        Array.from(document.querySelectorAll('.media-item:has(input:checked)')).map(async e => {
            e.querySelector('input:checked').checked = false;
            const id = e.dataset.id;
            try {
                const response = await fetch(`/media-library/${id}`, {
                    method: 'DELETE'
                });

                if (response.ok) {
                    document.querySelector(`#media-item-${id}`).parentNode.remove();

                    if (document.getElementById('media-grid').children.length === 0) {
                        document.getElementById('no-media-message').style.display = 'flex';
                    }
                }
            } catch (error) {
                console.error('Delete failed:', error);
            }
        });
    }
}
