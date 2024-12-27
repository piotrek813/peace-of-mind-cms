<?php

namespace App\Models;

use App\Database;

class Media
{
    public function store($file): int|bool
    {
        $uploadDir = BASE_PATH . '/uploads/' . date('Y/m');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $originalName = $file['name'];
        $safeName = $this->generateSafeName($originalName);
        $path = $uploadDir . '/' . $safeName;
        $url = 'uploads/' . date('Y/m') . '/' . $safeName;

        if (move_uploaded_file($file['tmp_name'], $path)) {
            return $this->save($safeName, $file['type'], $file['size'], $url);
        }

        return false;
    }

    private function generateSafeName(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $safeName = preg_replace('/[^a-z0-9]+/', '-', strtolower($originalName));
        $safeName = trim($safeName, '-');
        $uniqueName = $safeName . '-' . uniqid();
        
        return $uniqueName . '.' . $extension;
    }

    public function delete(int $id): bool
    {
        $media = $this->getById($id);
        if (file_exists(BASE_PATH . $media['url'])) {
            unlink(BASE_PATH . $media['url']);
        }

        $db = Database::getInstance();
        $sql = "DELETE FROM media WHERE id = ?";
        $db->query($sql, [$id]);

        return true;
    }

    public function save(string $name, string $mimeType, int $size, string $url)
    {
        $formattedSize = $this->formatSize($size);
        
        $db = Database::getInstance();
        $sql = "INSERT INTO media (name, mime_type, size, formatted_size, url) VALUES (?, ?, ?, ?, ?)";
        $db->query($sql, [$name, $mimeType, $size, $formattedSize, $url]);

        return $db->lastInsertId();
    }

    private function formatSize(int $size): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        
        return number_format($size / pow(1024, $power), 2, '.', '') . ' ' . $units[$power];
    }

    public function getById(int $id)
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM media WHERE id = ? LIMIT 1";
        return $db->query($sql, [$id])->fetch(\PDO::FETCH_ASSOC);
    }

    public function findAll()
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM media";
        return $db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
} 