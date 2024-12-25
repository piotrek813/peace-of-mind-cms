<?php partial('head'); ?>
<body class="bg-base-300 min-h-screen">
    <?php partial('navbar', ['username' => $username]); ?>
<div class="grid grid-flow-col">
    <?php partial('sidebar', ['schemas' => $schemas]); ?>
    <main class="overflow-x-hidden p-4">
        <div class="bg-base-200 rounded-lg p-4">
            <?= $child; ?>
        </div>
    </main>
</div>
</body>
</html>