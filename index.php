<?php
require_once __DIR__ . '/helpers/helpers.php';
require_once __DIR__ . '/src/ArticleRepository.php';
require_once __DIR__ . '/src/Models/Article.php';

$articleRepository = new ArticleRepository(__DIR__ . '/articles.json');
$articles = $articleRepository->getAllArticles();

// Optional success message
$msg = isset($_GET['msg']) ? e($_GET['msg']) : '';
?>

<!doctype html>
<html lang="en">

<?php require_once 'layout/header.php'; ?>

<body>
  <?php require_once 'layout/navigation.php'; ?>

  <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
    <h2 id="page-title" class="text-xl text-center font-semibold text-indigo-700 mt-10">
      Articles
    </h2>

    <?php if ($msg): ?>
      <div class="alert alert-success shadow-lg my-6">
        <span><?php echo $msg; ?></span>
      </div>
    <?php endif; ?>

    <div class="overflow-hidden mt-6">
      <ul role="list" class="divide-y divide-gray-200">
        <?php if (empty($articles)): ?>
          <li class="p-4 text-gray-500">No articles yet. Add one below!</li>
        <?php else: ?>
          <?php foreach ($articles as $article): ?>
            <li class="p-4 flex items-center justify-between">
              <div>
                <a href="<?php echo e($article->getUrl()); ?>" 
                   class="font-semibold text-indigo-600 hover:underline"
                   target="_blank">
                  <?php echo e($article->getTitle()); ?>
                </a>
              </div>

              <div class="flex gap-2">
                <a href="update_article.php?id=<?php echo e($article->getId()); ?>" class="btn btn-outline btn-sm">Edit</a>
                <form action="delete_article.php" method="post" 
                      onsubmit="return confirm('Are you sure you want to delete this article?');">
                  <input type="hidden" name="id" value="<?php echo e($article->getId()); ?>">
                  <button type="submit" class="btn btn-error btn-sm">Delete</button>
                </form>
              </div>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>

    <div class="mt-6 text-center">
      <a href="new_article.php" class="btn btn-primary">Add New Article</a>
    </div>
  </div>
</body>
</html>
