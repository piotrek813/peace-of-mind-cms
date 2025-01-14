<?php partial('head'); ?>
<body class="bg-base-300 min-h-screen">
    <?php partial('navbar', ['username' => $username]); ?>
<div class="grid" style="grid-template-columns: 250px 1fr;">
    <?php partial('sidebar', ['schemas' => $schemas]); ?>
    <main class="overflow-x-hidden p-4">
        <div class="bg-base-200 rounded-lg <?php echo isset($no_padding) ? '' : 'p-4'; ?>">
            <?= $child; ?>
        </div>
    </main>
</div>
</body>
</html>