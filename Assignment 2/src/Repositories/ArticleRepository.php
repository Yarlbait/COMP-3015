<?php

namespace src\Repositories;

require_once 'Repository.php';
require_once __DIR__ . '/../Models/Article.php';

use core\Log;
use PDO;
use PDOException;
use src\Models\Article;

class ArticleRepository extends Repository
{
    // Create an article
    public function create(int $authorId, string $title, string $url, ?string $description = null): ?int
    {
        try
        {
            $stmt = $this->pdo->prepare(
                'INSERT INTO articles (author_id, title, url, description, created_at, updated_at)
                 VALUES (:aid, :t, :u, :d, NOW(), NOW())'
            );
            $stmt->execute([
                ':aid' => $authorId,
                ':t'   => $title,
                ':u'   => $url,
                ':d'   => $description
            ]);
            return (int)$this->pdo->lastInsertId();
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return null;
        }
    }

    // Update an article
    public function update(int $id, string $title, string $url, ?string $description = null): bool
    {
        try
        {
            $stmt = $this->pdo->prepare(
                'UPDATE articles SET title = :t, url = :u, description = :d, updated_at = NOW() WHERE id = :id'
            );
            return $stmt->execute([
                ':t'  => $title,
                ':u'  => $url,
                ':d'  => $description,
                ':id' => $id
            ]);
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return false;
        }
    }

    // Delete an article
    public function delete(int $id): bool
    {
        try
        {
            $stmt = $this->pdo->prepare('DELETE FROM articles WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return false;
        }
    }

    // Find single article
    public function findById(int $id): ?Article
    {
        try
        {
            $stmt = $this->pdo->prepare('SELECT * FROM articles WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            return $row ? (new Article())->fill($row) : null;
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return null;
        }
    }

    // check owners
    public function ownerId(int $id): ?int
    {
        try
        {
            $stmt = $this->pdo->prepare('SELECT author_id FROM articles WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            return $row ? (int)$row['author_id'] : null;
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return null;
        }
    }

    // Paginated list with simple search on title
    public function getPaginated(int $limit, int $offset, string $search = ''): array
    {
        $params = [];
        $where = '';
        if ($search !== '')
        {
            $where = 'WHERE a.title LIKE :q';
            $params[':q'] = "%{$search}%";
        }

        try
        {
            $sql = "SELECT a.*
                    FROM articles a
                    {$where}
                    ORDER BY a.created_at DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v, PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            return array_map(fn($r) => (new Article())->fill($r), $rows);
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return [];
        }
    }

    // Count 
    public function getCount(string $search): int
    {
        try
        {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM articles WHERE title LIKE :q");
            $stmt->execute([':q' => "%{$search}%"]);
            return (int)$stmt->fetchColumn();
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return 0;
        }
    }
}
