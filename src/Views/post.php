<?php
declare(strict_types=1);

$base = dirname(__DIR__, 2);

require_once $base . '/helpers/session.php';
require_once $base . '/src/Repositories/PostRepository.php';
require_once $base . '/src/Models/Post.php';

use Repositories\PostRepository;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$repo = new PostRepository();
$post = $id > 0 ? $repo->findById($id) : null;

if (!$post)
{
    flash_set('error', 'Post not found.');
    header('Location: index.php');
    exit;
}

$success = flash_get('success');
$error   = flash_get('error');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($post->getTitle()) ?></title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
    .flash-success { background:#e6ffed; border:1px solid #b7f5c1; padding:.5rem .75rem; margin:.5rem 0; }
    .flash-error { background:#ffecec; border:1px solid #ffc2c2; padding:.5rem .75rem; margin:.5rem 0; }
    .btn { padding:.35rem .6rem; border:1px solid #aaa; background:#f9f9f9; border-radius:8px; text-decoration:none; cursor:pointer; }
    form.inline { display:inline; }
  </style>
</head>
<body>
  <h1><?= htmlspecialchars($post->getTitle()) ?></h1>

  <?php if ($success): ?>
    <div class="flash-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="flash-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <p><?= nl2br(htmlspecialchars($post->getBody())) ?></p>
  <p><small>
    Created: <?= htmlspecialchars($post->getCreatedAt() ?? '') ?><br>
    Updated: <?= htmlspecialchars($post->getUpdatedAt() ?? '—') ?>
  </small></p>

  <p>
    <a class="btn" href="index.php">All Posts</a>
    <a class="btn" href="update.php?id=<?= (int)$post->getId() ?>">Edit</a>

    <!-- DELETE via POST -->
    <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this post?');">
      <input type="hidden" name="id" value="<?= (int)$post->getId() ?>">
      <button class="btn" type="submit">Delete</button>
    </form>
  </p>
</body>
</html>
