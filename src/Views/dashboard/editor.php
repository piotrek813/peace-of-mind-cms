<?php partial('head'); ?>
<body class="bg-base-300 min-h-screen">
    <?php partial('navbar', ['username' => $username]); ?>

    <div class="p-6">
        <div class="max-w-5xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold"><?= $entry ? 'Edit' : 'New' ?> Entry</h1>
                <button form="entry-form" type="submit" class="btn btn-primary">Save Entry</button>
            </div>

            <form id="entry-form" method="POST" action="/editor" class="space-y-6">
                <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
                <?php if ($entry): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($entry['id']) ?>">
                <?php endif; ?>

                <?= $form->render() ?>
            </form>
        </div>
    </div>

    <dialog id="media-modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl h-[90vh] p-0">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-10">✕</button>
            </form>
            
            <div class="p-4 bg-base-200 border-b border-base-300">
                <h3 class="font-bold text-lg">Media Library</h3>
            </div>
            
            <div class="p-4 overflow-y-auto max-h-[calc(90vh-4rem)]">
                <?= $library ?>
            </div>
        </div>
        
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</body>
</html>