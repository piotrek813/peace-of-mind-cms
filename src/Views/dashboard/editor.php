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

            <?= $library ?>
        </div>
    </div>
</body>
</html>