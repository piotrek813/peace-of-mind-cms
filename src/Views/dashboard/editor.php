<!DOCTYPE html>
<html data-theme="dark">
<head>
    <title>New Entry - Peace of Mind</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Group Field Assets -->
    <link href="/assets/css/fields.css" rel="stylesheet" type="text/css" />
    <script src="/assets/js/fields.js" defer></script>
</head>
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

    <!-- Main Content -->
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
</body>
</html>