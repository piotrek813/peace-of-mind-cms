<?php partial('head'); ?>
<body class="bg-base-300 min-h-screen">
    <!-- Navigation -->
    <div class="navbar bg-base-200">
        <div class="navbar-start">
            <a href="/dashboard" class="btn btn-ghost text-xl text-primary">Dashboard</a>
        </div>
        <div class="navbar-end">
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost btn-circle avatar placeholder">
                    <div class="bg-primary text-primary-content rounded-full w-10">
                        <span><?= substr($username, 0, 1) ?></span>
                    </div>
                </label>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-200 rounded-box w-52">
                    <li><a>Profile</a></li>
                    <li><a>Settings</a></li>
                    <li><a href="/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- After the navbar -->
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-base-200 min-h-screen p-4">
            <ul class="menu menu-vertical">
                <li class="menu-title">Content</li>
                <?php foreach ($schemas as $schema): ?>
                <li>
                    <a href="/dashboard?type=<?= htmlspecialchars($schema['name']) ?>" class="flex items-center gap-2">
                        <?php if ($schema['icon'] === 'document-text'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <?php elseif ($schema['icon'] === 'template'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        <?php endif; ?>
                        <?= htmlspecialchars($schema['label']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
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
                        <a href="/editor?type=<?= htmlspecialchars($activeSchema['name']) ?>" class="btn btn-primary">
                            New <?= htmlspecialchars($activeSchema['label']) ?>
                        </a>
                    </div>

                    <?php if (empty($entries)): ?>
                        <div class="text-center py-12">
                            <h3 class="text-lg font-medium mb-2">No entries yet</h3>
                            <p class="text-base-content/60 mb-4">Create your first <?= strtolower($activeSchema['label']) ?> to get started</p>
                            <a href="/editor?type=<?= htmlspecialchars($activeSchema['name']) ?>" class="btn btn-primary">
                                Create <?= htmlspecialchars($activeSchema['label']) ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="table bg-base-200">
                                <thead>
                                    <tr>
                                        <?php 
                                        $firstEntry = reset($entries);
                                        $data = $firstEntry['data'];
                                        foreach (array_keys(json_decode($data, true)) as $column): 
                                        ?>
                                            <th><?= htmlspecialchars(ucwords(str_replace('_', ' ', $column))) ?></th>
                                        <?php endforeach; ?>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($entries as $entry): 
                                        $data = $entry['data'];
                                    ?>
                                        <tr>
                                            <?php foreach (json_decode($data, true) as $value): ?>
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
                                                    <a href="/editor?type=<?= $activeSchema['name'] ?>&id=<?= $entry['id'] ?>" class="btn btn-ghost btn-xs">
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
            </div>
        </div>
    </div>
</body>
</html> 