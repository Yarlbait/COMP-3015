<?php require_once __DIR__ . '/header.php'; ?>

<h1 class="text-2xl font-semibold mb-6">Submit a New Article</h1>

<?php if ($msg = flash('error')): ?>
  <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700"><?= e($msg) ?></div>
<?php endif; ?>

<form method="post" action="/articles" class="space-y-5 max-w-lg"
      onsubmit="this.querySelector('button[type=submit]').disabled=true;">
  <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="form_token" value="<?= e($_SESSION['article_form_token'] ?? '') ?>">

  <label class="block">
    <span class="mb-1 block text-sm font-medium text-gray-700">Title</span>
    <input type="text" name="title" required
           value="<?= e($_SESSION['old']['title'] ?? '') ?>"
           class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-gray-400"
           placeholder="e.g. PHP Tips for Beginners">
  </label>

  <label class="block">
    <span class="mb-1 block text-sm font-medium text-gray-700">Article URL</span>
    <input type="url" name="url" required
           value="<?= e($_SESSION['old']['url'] ?? '') ?>"
           class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-gray-400"
           placeholder="https://example.com/article">
  </label>

  <div class="pt-2 flex gap-3">
    <button type="submit"
            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
      Submit Article
    </button>
    <a href="/"
       class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300">
      Cancel
    </a>
  </div>
</form>

<?php unset($_SESSION['old']); ?>
<?php require_once __DIR__ . '/footer.php'; ?>
