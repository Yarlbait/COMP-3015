<?php
declare(strict_types=1);

$base = dirname(__DIR__, 2);

require_once $base . '/helpers/session.php';
require_once $base . '/src/Repositories/PostRepository.php';
require_once $base . '/src/Repositories/Repository.php';
require_once $base . '/src/Models/Post.php';

use Repositories\PostRepository;

$repo  = new PostRepository();
$posts = $repo->getAllPosts();

$success = flash_get('success');
$error   = flash_get('error');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Posts</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
    .flash-success { background:#e6ffed; border:1px solid #b7f5c1; padding:.5rem .75rem; margin:.5rem 0; }
    .flash-error { background:#ffecec; border:1px solid #ffc2c2; padding:.5rem .75rem; margin:.5rem 0; }
    .btn { padding:.35rem .6rem; border:1px solid #aaa; background:#f9f9f9; border-radius:8px; text-decoration:none; cursor:pointer; }
    form.inline { display:inline; }
    .card { border:1px solid #ddd; border-radius:12px; padding:1rem; margin:.75rem 0; }
  </style>
</head>
<body>
  <h1>Posts</h1>

  <?php if ($success): ?>
    <div class="flash-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="flash-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <p><a class="btn" href="add.php">+ New Post</a></p>

  <?php if (empty($posts)): ?>
    <p>No posts yet.</p>
  <?php else: ?>
    <?php foreach ($posts as $post): ?>
      <article class="card">
        <h3><?= htmlspecialchars($post->getTitle()) ?></h3>
        <p><?= nl2br(htmlspecialchars($post->getBody())) ?></p>
        <p><small>
          <?php if ($post->getUpdatedAt()): ?>
            Updated: <?= htmlspecialchars($post->getUpdatedAt()) ?>
          <?php else: ?>
            Created: <?= htmlspecialchars($post->getCreatedAt() ?? '') ?>
          <?php endif; ?>
        </small></p>

        <a class="btn" href="post.php?id=<?= (int)$post->getId() ?>">View</a>
        <a class="btn" href="update.php?id=<?= (int)$post->getId() ?>">Edit</a>

        <!-- DELETE via POST -->
        <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this post?');">
          <input type="hidden" name="id" value="<?= (int)$post->getId() ?>">
          <button class="btn" type="submit">Delete</button>
        </form>
      </article>
    <?php endforeach; ?>
  <?php endif; ?>
</body>
</html>
