<?php require_once __DIR__ . '/header.php'; ?>

<div class="max-w-3xl mx-auto">
  <article class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">
    <h1 class="text-3xl font-semibold text-gray-900 mb-2"><?= e($article['title']) ?></h1>

    <a href="<?= e($article['url']) ?>"
       target="_blank"
       class="text-indigo-600 hover:text-indigo-800 break-all">
      <?= e($article['url']) ?>
    </a>

    <div class="mt-6 flex items-center gap-3 text-sm text-gray-600">
      <?php if (!empty($article['profile_picture'])): ?>
        <img src="/uploads/<?= e($article['profile_picture']) ?>"
             alt="<?= e($article['author_name']) ?>"
             class="h-10 w-10 rounded-full object-cover border border-gray-300">
      <?php else: ?>
        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-medium">
          <?= strtoupper(substr($article['author_name'], 0, 1)) ?>
        </div>
      <?php endif; ?>

      <div>
        <div class="font-medium"><?= e($article['author_name']) ?></div>
        <?php if (!empty($article['created_at'])): ?>
          <div class="text-xs text-gray-500"><?= date('M j, Y', strtotime($article['created_at'])) ?></div>
        <?php endif; ?>
      </div>
    </div>

    <?php if ((int)auth_id() === (int)$article['author_id']): ?>
      <div class="mt-6 flex gap-3">
        <a href="/articles/<?= (int)$article['id'] ?>/edit"
           class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
          Edit
        </a>

        <form method="post"
              action="/articles/<?= (int)$article['id'] ?>/delete"
              onsubmit="return confirm('Are you sure you want to delete this article?');">
          <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
          <button type="submit"
                  class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
            Delete
          </button>
        </form>
      </div>
    <?php endif; ?>
  </article>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
