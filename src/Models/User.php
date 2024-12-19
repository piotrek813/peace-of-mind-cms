<?php

namespace App\Models;

use App\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($username, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        
        try {
            $this->db->query($sql, [$username, $hashedPassword]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $stmt = $this->db->query($sql, [$username]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
} 