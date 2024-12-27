<?php if (!$activeSchema): ?>
    <h1 class="text-2xl font-bold mb-6">Welcome back, <?= htmlspecialchars($username) ?>!</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stat bg-base-200 rounded-lg">
            <div class="stat-title">Total Entries</div>
            <div class="stat-value"><?= count($entries) ?></div>
            <div class="stat-desc">All content types</div>
        </div>
    </div>
<?php else: ?>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold"><?= htmlspecialchars($activeSchema['label']) ?> Entries</h1>
        <a href="editor?type=<?= htmlspecialchars($activeSchema['name']) ?>" class="btn btn-primary">
            New <?= htmlspecialchars($activeSchema['label']) ?>
        </a>
    </div>


    <?php if (empty($entries)): ?>
        <div class="text-center py-12">
            <h3 class="text-lg font-medium mb-2">No entries yet</h3>
            <p class="text-base-content/60 mb-4">Create your first <?= strtolower($activeSchema['label']) ?> to get started</p>
            <a href="editor?type=<?= htmlspecialchars($activeSchema['name']) ?>" class="btn btn-primary">
                Create <?= htmlspecialchars($activeSchema['label']) ?>
            </a>
        </div>
    <?php else: ?>

        <div class="overflow-x-auto">
            <table class="table bg-base-200">
                <thead>
                    <tr>
                        <?php 
                        $headers = array_reduce($activeSchema['fields'], function($acc, $f) {
                            $acc[$f['name']] = $f['label'];
                            return $acc; 
                        }, array());

                        foreach (array_values($headers) as $column): 
                        ?>
                            <th><?= htmlspecialchars(ucwords(str_replace('_', ' ', $column))) ?></th>
                        <?php endforeach; ?>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $entry): 
                        $data = json_decode($entry['data'], true);
                    ?>
                        <tr>
                            <?php foreach (array_keys($headers) as $header): 
                                $value = $data[$header] ?? '';
                            ?>
                                <?php if (is_array($value)): ?>
                                    <td>
                                        <button onclick="showArrayData(this)" 
                                                class="btn btn-ghost btn-xs"
                                                data-array='<?= htmlspecialchars(json_encode($value)) ?>'>
                                            View Data
                                        </button>
                                        
                                        <dialog class="modal">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg mb-4">Array Data</h3>
                                                <div class="array-content whitespace-pre-wrap font-mono text-sm bg-base-300 p-4 rounded-lg overflow-auto max-h-96"></div>
                                                <div class="modal-action">
                                                    <form method="dialog">
                                                        <button class="btn">Close</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </dialog>
                                    </td>
                                <?php else: ?>
                                    <td>
                                        <?= htmlspecialchars(substr($value, 0, 50)) . (strlen($value) > 50 ? '...' : '') ?>
                                    </td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($entry['created_at']))) ?></td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="editor?type=<?= $activeSchema['name'] ?>&id=<?= $entry['id'] ?>" class="btn btn-ghost btn-xs">
                                        Edit
                                    </a>
                                    <form method="POST" action="/delete-entry" class="inline">
                                        <input type="hidden" name="id" value="<?= $entry['id'] ?>">
                                        <input type="hidden" name="type" value="<?= $activeSchema['name'] ?>">
                                        <button type="submit" class="btn btn-ghost btn-xs text-error" 
                                                onclick="return confirm('Are you sure you want to delete this entry?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php endif; ?>
