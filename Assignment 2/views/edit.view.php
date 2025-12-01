<?php require_once __DIR__ . '/header.php'; ?>

<h1 class="text-2xl font-semibold mb-4">Edit Article</h1>

<form method="post" action="/articles/<?= (int)$article['id'] ?>/update" class="space-y-4 max-w-lg">
  <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

  <label class="block">
    <span class="mb-1 block text-sm font-medium">Title</span>
    <input
      type="text"
      name="title"
      value="<?= e($article['title'] ?? '') ?>"
      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
      required
    >
  </label>

  <label class="block">
    <span class="mb-1 block text-sm font-medium">URL</span>
    <input
      type="url"
      name="url"
      value="<?= e($article['url'] ?? '') ?>"
      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
      required
    >
  </label>

  <div class="pt-2 flex gap-3">
    <button
      type="submit"
      class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
    >
      Save Changes
    </button>
    <a
      href="/articles/<?= (int)$article['id'] ?>"
      class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
    >
      Cancel
    </a>
  </div>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
