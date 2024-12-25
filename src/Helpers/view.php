<?php

function partial(string $name, array $options = []): void
{
    $basePath = __DIR__ . '/../Views/partials/';
    $filePath = $basePath . $name . '.php';
    
    if (!file_exists($filePath)) {
        throw new RuntimeException("Partial '{$name}' not found at {$filePath}");
    }
    
    extract($options);
    include $filePath;
}

function layout(string $name, array $options = [], string $child = "", array $slots = []): void {
    $basePath = __DIR__ . '/../Views/layouts/';
    $filePath = $basePath . $name . '.php';
    
    if (!file_exists($filePath)) {
        throw new RuntimeException("Layout '{$name}' not found at {$filePath}");
    }

    $slots = array_merge($slots, ['child' => $child]);

    extract($options);

    foreach ($slots as $key => $slot) {
        if (file_exists(BASE_PATH ."/src/". $slot)) {
            ob_start();
            include BASE_PATH ."/src/". $slot;
            $slots[$key] = ob_get_clean();
        }
    }

    extract($slots);

    include $filePath;
}