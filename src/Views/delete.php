<?php
declare(strict_types=1);

$base = dirname(__DIR__, 2);

require_once $base . '/helpers/session.php';
require_once $base . '/helpers/redirect.php';
require_once $base . '/src/Repositories/PostRepository.php';
require_once $base . '/src/Models/Post.php';

use Repositories\PostRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    redirect('index.php', 302);
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0)
{
    flash_set('error', 'Invalid id for delete.');
    redirect('index.php');
}

$repo = new PostRepository();
$post = $repo->findById($id);

if (!$post)
{
    flash_set('error', 'Post not found.');
    redirect('index.php');
}

$ok = $repo->delete($post);
flash_set($ok ? 'success' : 'error', $ok ? 'Post deleted.' : 'Delete failed.');

redirect('index.php');

