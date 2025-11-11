<?php

namespace src\Repositories;

require_once 'Repository.php';
require_once __DIR__ . '/../Models/User.php';

use core\Log;
use PDOException;
use src\Models\User;

class UserRepository extends Repository
{
    public function create(string $name, string $email, string $passwordDigest): ?int
    {
        try
        {
            $stmt = $this->pdo->prepare(
                'INSERT INTO users (name, email, password_digest, profile_picture, created_at, updated_at)
                 VALUES (:name, :email, :pwd, NULL, NOW(), NOW())'
            );
            $stmt->execute([':name' => $name, ':email' => $email, ':pwd' => $passwordDigest]);
            return (int)$this->pdo->lastInsertId();
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return null;
        }
    }

    public function findByEmail(string $email): ?User
    {
        try
        {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $row = $stmt->fetch();
            return $row ? (new User())->fill($row) : null;
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return null;
        }
    }

    public function findById(int $id): ?User
    {
        try
        {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            return $row ? (new User())->fill($row) : null;
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return null;
        }
    }

    public function updateName(int $id, string $name): bool
    {
        try
        {
            $stmt = $this->pdo->prepare('UPDATE users SET name = :n, updated_at = NOW() WHERE id = :id');
            return $stmt->execute([':n' => $name, ':id' => $id]);
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function updateProfile(int $id, string $name, ?string $profilePicture): bool
    {
        try
        {
            if ($profilePicture !== null)
            {
                $stmt = $this->pdo->prepare(
                    'UPDATE users SET name = :n, profile_picture = :pp, updated_at = NOW() WHERE id = :id'
                );
                return $stmt->execute([':n' => $name, ':pp' => $profilePicture, ':id' => $id]);
            }
            else
            {
                return $this->updateName($id, $name);
            }
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return false;
        }
    }
}
