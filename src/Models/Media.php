<?php

namespace App\Models;

use App\Model;

class Media extends Model
{
    protected static string $table = 'media';
    
    public function store($file): bool
    {
        $uploadDir = 'uploads/' . date('Y/m');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $originalName = $file['name'];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $safeName = $this->generateSafeName($originalName);
        $path = $uploadDir . '/' . $safeName;

        if (move_uploaded_file($file['tmp_name'], $path)) {
            return $this->save();
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

    public function delete(): bool
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }
        
        return parent::delete();
    }

    public static function createTable(): string
    {
        return "CREATE TABLE IF NOT EXISTS media (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            original_name VARCHAR(255) NOT NULL,
            mime_type VARCHAR(127) NOT NULL,
            size INT NOT NULL,
            path VARCHAR(255) NOT NULL,
            url VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
    }
} 