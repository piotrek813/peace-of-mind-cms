<?php partial('head'); ?>
<body class="bg-base-300 min-h-screen">

<div class="flex">
    <?php partial('sidebar', ['schemas' => $schemas]); ?>
    <main class="flex-1 p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Media Library</h1>
            <form action="/media-library/upload" method="post" enctype="multipart/form-data">
                <label for="upload-media" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    Upload Media
                </label>
                <input type="file" id="upload-media" class="hidden" multiple accept="image/*">
            </form>
        </div>

        <div class="flex-1 bg-base-200 rounded-lg p-4">
            <div id="media-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php foreach ($media as $item): ?>
                    <div class="card bg-base-100 shadow-sm group">
                        <figure class="aspect-square">
                            <img src="<?= htmlspecialchars($item->url) ?>" 
                                    alt="<?= htmlspecialchars($item->name) ?>"
                                    class="w-full h-full object-cover">
                        </figure>
                        <div class="card-body p-3">
                            <h3 class="card-title text-sm truncate"><?= htmlspecialchars($item->name) ?></h3>
                            <p class="text-xs text-base-content/70"><?= formatFileSize($item->size) ?></p>
                        </div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-circle btn-sm btn-ghost bg-base-100">⋮</label>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                    <li><a href="#" onclick="copyToClipboard('<?= htmlspecialchars($item->url) ?>')">Copy URL</a></li>
                                    <li><a href="<?= htmlspecialchars($item->url) ?>" download>Download</a></li>
                                    <li><a href="#" class="text-error" onclick="deleteMedia(<?= $item->id ?>)">Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($media)): ?>
                <div class="flex flex-col items-center justify-center p-8 text-base-content/70">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-lg font-medium mb-2">No media files yet</p>
                    <p>Upload some files to get started</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // You could add a toast notification here
    });
}

async function deleteMedia(id) {
    if (confirm('Are you sure you want to delete this media item?')) {
        try {
            const response = await fetch(`/api/media/${id}`, {
                method: 'DELETE'
            });
            
            if (response.ok) {
                window.location.reload();
            }
        } catch (error) {
            console.error('Delete failed:', error);
        }
    }
}
</script>

</body>
</html>