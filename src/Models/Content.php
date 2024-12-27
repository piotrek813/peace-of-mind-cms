<?php

namespace App\Models;

use App\Database;

class Content
{
    private $db;
    private $table = 'content';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(array $data, int $userId, string $type): bool
    {
        $json_data = json_encode($data);
        $now = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO content (data, created_at, updated_at, user_id, type) 
                VALUES (:data, :created_at, :updated_at, :user_id, :type)";
        
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'data' => $json_data,
            'created_at' => $now,
            'updated_at' => $now,
            'user_id' => $userId,
            'type' => $type
        ]);
    }

    public function update(int $id, array $data, string $type): bool
    {
        $json_data = json_encode($data);
        $now = date('Y-m-d H:i:s');
        
        $sql = "UPDATE content 
                SET data = :data, 
                    updated_at = :updated_at,
                    type = :type
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $id,
            'data' => $json_data,
            'updated_at' => $now,
            'type' => $type
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM content WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function getById(int $id)
    {
        $sql = "SELECT * FROM content WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByType(string $type, int $userId): array
    {
        $sql = "SELECT * FROM content WHERE type = :type AND user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['type' => $type, 'user_id' => $userId]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 