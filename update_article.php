<?php
require_once __DIR__ . '/helpers/helpers.php';
require_once __DIR__ . '/src/ArticleRepository.php';
require_once __DIR__ . '/src/Models/Article.php';

$repo = new ArticleRepository(__DIR__ . '/articles.json');
$errors = [];

// Handle POST (save changes)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $url   = trim($_POST['url'] ?? '');

    if ($id <= 0) {
        $errors[] = 'Invalid article ID.';
    }
    if ($title === '' || strlen($title) > 256) {
        $errors[] = 'Title must be between 1 and 256 characters.';
    }
    if ($url === '' || filter_var($url, FILTER_VALIDATE_URL) === false) {
        $errors[] = 'Please enter a valid URL.';
    }

    if (empty($errors)) {
        $updated = new Article($id);
        $updated->fill(['title' => $title, 'url' => $url]);
        $repo->updateArticle($id, $updated);
        redirect('index.php?msg=' . urlencode('Article updated successfully!'));
    } else {
        redirect('update_article.php?id=' . $id . '&errors=' . urlencode(json_encode($errors)));
    }
}

// Handle GET (load article for editing)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$article = $id > 0 ? $repo->getArticleById($id) : null;

if (isset($_GET['errors'])) {
    $decoded = json_decode($_GET['errors'], true);
    if (is_array($decoded)) {
        $errors = $decoded;
    }
}
?>

<!doctype html>
<html lang="en">

<?php require_once 'layout/header.php'; ?>

<body>
  <?php require_once 'layout/navigation.php'; ?>

  <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
    <h2 class="text-xl text-center font-semibold text-indigo-700 mt-10">
      Edit Article
    </h2>

    <?php if ($errors): ?>
      <div class="alert alert-error shadow-lg my-6">
        <div>
          <span>
            <?php foreach ($errors as $error): ?>
              <?php echo e($error); ?><br>
            <?php endforeach; ?>
          </span>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!$article): ?>
      <div class="alert alert-warning shadow-lg my-6">
        <div><span>Article not found or invalid ID.</span></div>
      </div>
      <a href="index.php" class="btn btn-outline">Back to list</a>
    <?php else: ?>
      <form method="post" class="mt-6 space-y-6">
        <input type="hidden" name="id" value="<?php echo e($article->getId()); ?>">

        <div class="form-control">
          <label class="label">
            <span class="label-text font-semibold">Title</span>
          </label>
          <input type="text"
                 name="title"
                 maxlength="256"
                 value="<?php echo e($article->getTitle()); ?>"
                 class="input input-bordered w-full"
                 required>
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-semibold">Link (URL)</span>
          </label>
          <input type="url"
                 name="url"
                 value="<?php echo e($article->getUrl()); ?>"
                 class="input input-bordered w-full"
                 required>
        </div>

        <div class="form-control mt-6 flex gap-2">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <a href="index.php" class="btn btn-outline">Cancel</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
