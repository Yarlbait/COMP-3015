<?php
declare(strict_types=1);

$base = dirname(__DIR__, 2);

require_once $base . '/helpers/session.php';
require_once $base . '/helpers/redirect.php';
require_once $base . '/src/Repositories/PostRepository.php';
require_once $base . '/src/Models/Post.php';

use Repositories\PostRepository;
use Models\Post;

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $title = trim((string)($_POST['title'] ?? ''));
    $body  = trim((string)($_POST['body'] ?? ''));

    if ($title === '' || $body === '')
    {
        flash_set('error', 'Title and body are required.');
        redirect('add.php');
    }

    $post = new Post();
    $post->setTitle($title);
    $post->setBody($body);

    try
    {
        (new PostRepository())->save($post);
        flash_set('success', 'Post created.');
        redirect('post.php?id=' . (int)$post->getId());
    }
    catch (\Throwable $e)
    {
        flash_set('error', 'Failed to create post.');
        redirect('add.php');
    }
}

$success = flash_get('success');
$error   = flash_get('error');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>New Post</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 700px; margin: 2rem auto; padding: 0 1rem; }
    .flash-success { background:#e6ffed; border:1px solid #b7f5c1; padding:.5rem .75rem; margin:.5rem 0; }
    .flash-error { background:#ffecec; border:1px solid #ffc2c2; padding:.5rem .75rem; margin:.5rem 0; }
    label { display:block; margin:.5rem 0 .25rem; }
    input, textarea { width:100%; padding:.5rem; }
    .row { display:flex; gap:.5rem; margin-top:.75rem; }
    .btn { padding:.5rem .8rem; border:1px solid #aaa; background:#f9f9f9; border-radius:8px; text-decoration:none; cursor:pointer; }
  </style>
</head>
<body>
  <h1>New Post</h1>

  <?php if ($success): ?><div class="flash-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="flash-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post" action="add.php">
    <label>Title</label>
    <input name="title" required>

    <label>Body</label>
    <textarea name="body" rows="8" required></textarea>

    <div class="row">
      <button class="btn" type="submit">Create</button>
      <a class="btn" href="index.php">Cancel</a>
    </div>
  </form>
</body>
</html>
