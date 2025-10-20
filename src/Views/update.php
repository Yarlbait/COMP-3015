<?php
declare(strict_types=1);

$base = dirname(__DIR__, 2);

require_once $base . '/helpers/session.php';
require_once $base . '/helpers/redirect.php';
require_once $base . '/src/Repositories/PostRepository.php';
require_once $base . '/src/Models/Post.php';

use Repositories\PostRepository;

$repo = new PostRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $id    = (int)($_POST['id'] ?? 0);
    $title = trim((string)($_POST['title'] ?? ''));
    $body  = trim((string)($_POST['body'] ?? ''));

    if ($id <= 0)
    {
        flash_set('error', 'Invalid post id.');
        redirect('index.php');
    }

    $post = $repo->findById($id);
    if (!$post)
    {
        flash_set('error', 'Post not found.');
        redirect('index.php');
    }

    if ($title === '' || $body === '')
    {
        flash_set('error', 'Title and body are required.');
        redirect('update.php?id=' . $id);
    }

    $post->setTitle($title);
    $post->setBody($body);

    $ok = $repo->update($post);
    flash_set($ok ? 'success' : 'error', $ok ? 'Post updated.' : 'Update failed.');

    redirect('post.php?id=' . $id);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = $id > 0 ? $repo->findById($id) : null;

if (!$post)
{
    flash_set('error', 'Post not found.');
    redirect('index.php');
}

$success = flash_get('success');
$error   = flash_get('error');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Post</title>
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
  <h1>Edit Post</h1>

  <?php if ($success): ?><div class="flash-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="flash-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post" action="update.php">
    <input type="hidden" name="id" value="<?= (int)$post->getId() ?>">

    <label>Title</label>
    <input name="title" value="<?= htmlspecialchars($post->getTitle()) ?>" required>

    <label>Body</label>
    <textarea name="body" rows="8" required><?= htmlspecialchars($post->getBody()) ?></textarea>

    <div class="row">
      <button class="btn" type="submit">Save</button>
      <a class="btn" href="post.php?id=<?= (int)$post->getId() ?>">Cancel</a>
    </div>
  </form>
</body>
</html>

