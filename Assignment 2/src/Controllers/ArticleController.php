<?php
declare(strict_types=1);

namespace src\Controllers;

use Database;
use PDO;

final class ArticleController
{
    public function index(): void
    {
        $pdo   = Database::pdo();
        $limit = 5;

        $q    = trim($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;

        // WHERE clause for search
        $where  = '';
        $params = [];
        if ($q !== '') {
            $where = 'WHERE (a.title LIKE :q1 OR a.url LIKE :q2)';
            $params[':q1'] = '%' . $q . '%';
            $params[':q2'] = '%' . $q . '%';
        }

        // Count for pagination
        $countSql  = "SELECT COUNT(*) FROM articles a {$where}";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total      = (int)$countStmt->fetchColumn();
        $totalPages = max(1, (int)ceil($total / $limit));

        $sql = "
            SELECT a.id, a.title, a.url, a.author_id, a.created_at, a.updated_at,
                   u.name AS author_name, u.profile_picture
              FROM articles a
              JOIN users u ON u.id = a.author_id
              {$where}
              ORDER BY a.created_at DESC
              LIMIT :limit OFFSET :offset
        ";
        $stmt = $pdo->prepare($sql);

        if ($q !== '') {
            $stmt->bindValue(':q1', '%' . $q . '%', PDO::PARAM_STR);
            $stmt->bindValue(':q2', '%' . $q . '%', PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll();

        require __DIR__ . '/../../views/index.view.php';
        
    }

    public function show(int $id): void
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('
            SELECT a.*, u.name AS author_name, u.profile_picture
              FROM articles a
              JOIN users u ON u.id = a.author_id
             WHERE a.id = :id
             LIMIT 1
        ');
        $stmt->execute([':id' => $id]);
        $article = $stmt->fetch();

        if (!$article) {
            http_response_code(404);
            require __DIR__ . '/../../views/404.view.php';
            return;
        }

        require __DIR__ . '/../../views/show.view.php';
        
    }

    public function create(): void
    {
        require_auth();
        require __DIR__ . '/../../views/create.view.php';
    }

    public function store(): void
    {
        require_auth();
        if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }

        $title = trim($_POST['title'] ?? '');
        $url   = trim($_POST['url'] ?? '');

        if ($title === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            flash('error', 'Please provide a title and a valid URL.');
            redirect('/articles/create');
        }

        $pdo = Database::pdo();
        $stmt = $pdo->prepare('
            INSERT INTO articles (title, url, author_id, created_at, updated_at)
            VALUES (:t, :u, :aid, NOW(), NOW())
        ');
        $stmt->execute([
            ':t'   => $title,
            ':u'   => $url,
            ':aid' => auth_id(),
        ]);

        flash('success', 'Article submitted.');
        redirect('/');
    }

    public function edit(int $id): void
    {
        require_auth();
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $article = $stmt->fetch();

        if (!$article) {
            http_response_code(404);
            require __DIR__ . '/../../views/404.view.php';
            return;
        }

        if ((int)$article['author_id'] !== (int)auth_id()) {
            http_response_code(401);
            require __DIR__ . '/../../views/401.view.php';
            return;
        }

        require __DIR__ . '/../../views/edit.view.php';
    }

    public function update(int $id): void
    {
        require_auth();
        if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }

        $title = trim($_POST['title'] ?? '');
        $url   = trim($_POST['url'] ?? '');

        if ($title === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            flash('error', 'Please provide a title and a valid URL.');
            redirect("/articles/{$id}/edit");
        }

        $pdo = Database::pdo();

        $upd = $pdo->prepare('
            UPDATE articles
               SET title = :t, url = :u, updated_at = NOW()
             WHERE id = :id AND author_id = :aid
        ');
        $upd->execute([
            ':t'   => $title,
            ':u'   => $url,
            ':id'  => $id,
            ':aid' => auth_id(),
        ]);

        if ($upd->rowCount() === 0) {
            http_response_code(401);
            require __DIR__ . '/../../views/401.view.php';
            return;
        }

        flash('success', 'Article updated.');
        redirect("/articles/{$id}");
    }

    public function destroy(int $id): void
    {
        require_auth();
        if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }

        $pdo = Database::pdo();

        $del = $pdo->prepare('DELETE FROM articles WHERE id = :id AND author_id = :aid');
        $del->execute([':id' => $id, ':aid' => auth_id()]);

        if ($del->rowCount() === 0) {
            http_response_code(401);
            require __DIR__ . '/../../views/401.view.php';
            return;
        }

        flash('success', 'Article deleted.');
        redirect('/');
    }
}

