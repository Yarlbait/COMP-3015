<?php
declare(strict_types=1);

namespace src\Controllers;

use src\Controllers\Controller;
use Database;

final class SettingsController extends Controller
{
    public function show(): void
    {
        require_auth();

        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT id, name, email, profile_picture FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => auth_id()]);
        $user = $stmt->fetch();

        if (!$user) {
            http_response_code(404);
            $this->render('404');
            return;
        }

        $this->render('settings', ['user' => $user]);
    }

    public function update(): void
    {
        require_auth();
        if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }

        $name = trim((string)($_POST['name'] ?? ''));
        $errors = [];
        if ($name === '') {
            $errors['name'] = 'Name cannot be empty.';
        }

        $uploadFilename = null;
        if (!empty($_FILES['profile_picture']['name'])) {
            $targetDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($targetDir)) { @mkdir($targetDir, 0777, true); }

            $filename  = uniqid('', true) . '-' . basename($_FILES['profile_picture']['name']);
            $target    = $targetDir . $filename;
            $ext       = strtolower(pathinfo($target, PATHINFO_EXTENSION));
            $allowed   = ['jpg','jpeg','png','gif'];

            if (!in_array($ext, $allowed, true)) {
                $errors['profile_picture'] = 'Invalid file type.';
            } elseif (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
                $errors['profile_picture'] = 'Upload failed.';
            } else {
                $uploadFilename = $filename;
            }
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            redirect('/settings');
        }

        $pdo = Database::pdo();
        $sql = 'UPDATE users SET name = :n' . ($uploadFilename ? ', profile_picture = :pp' : '') . ' WHERE id = :id';
        $params = [':n' => $name, ':id' => auth_id()];
        if ($uploadFilename) { $params[':pp'] = $uploadFilename; }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        flash('success', 'Profile updated!');
        redirect('/settings');
    }
}
