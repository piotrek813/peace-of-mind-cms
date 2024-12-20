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