<?php
declare(strict_types=1);

namespace Repositories;

require_once __DIR__ . '/Repository.php';

use Models\Post;
use Logger\Log;
use PDOException;

class PostRepository extends Repository
{
    /**
     * @return Post[]
     */
    public function getAllPosts() : array
    {
        $stmt = $this->pdo->query("SELECT * FROM posts ORDER BY id DESC");
        $rows = $stmt->fetchAll();

        $posts = [];
        foreach ($rows as $row)
        {
            $posts[] = (new Post())->fill($row);
        }
        return $posts;
    }

    public function findById(int $id) : ?Post
    {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? (new Post())->fill($row) : null;
    }

    public function save(Post $post) : void
    {
        try
        {
            $stmt = $this->pdo->prepare(
                "INSERT INTO posts (created_at, updated_at, body, title)
                 VALUES (:created_at, NULL, :body, :title)"
            );

            $stmt->execute([
                ':created_at' => date('Y-m-d H:i:s'),
                ':body'       => $post->getBody(),
                ':title'      => $post->getTitle(),
            ]);

            $id = (int)$this->pdo->lastInsertId();

            $select = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id");
            $select->execute([':id' => $id]);

            $post->fill($select->fetch());
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function update(Post $post) : bool
    {
        try
        {
            $stmt = $this->pdo->prepare(
                "UPDATE posts
                 SET title = :title, body = :body, updated_at = :updated_at
                 WHERE id = :id"
            );

            $ok = $stmt->execute([
                ':title'      => $post->getTitle(),
                ':body'       => $post->getBody(),
                ':updated_at' => date('Y-m-d H:i:s'),
                ':id'         => $post->getId(),
            ]);

            if ($ok)
            {
                $select = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id");
                $select->execute([':id' => $post->getId()]);
                $post->fill($select->fetch());
            }

            return $ok;
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function delete(Post $post) : bool
    {
        try
        {
            $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = :id");
            return $stmt->execute([':id' => $post->getId()]);
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            return false;
        }
    }
}

