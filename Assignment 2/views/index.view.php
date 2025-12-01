<?php require_once __DIR__ . '/header.php'; ?>

<?php if ($ok = flash('success')): ?>
  <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">
    <?= e($ok) ?>
  </div>
<?php endif; ?>

<h1 class="text-2xl font-semibold mb-4">Articles</h1>

<form class="mb-6 flex items-center gap-2" method="get" action="/">
  <input
    type="text"
    name="q"
    value="<?= e($q ?? ($_GET['q'] ?? '')) ?>"
    placeholder="Search title or URL"
    class="w-full max-w-md rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
  >
  <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Search</button>
</form>

<p class="mb-4">
  <a class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700" href="/articles/create">
    Submit Article
  </a>
</p>

<?php if (empty($articles)) : ?>
  <p class="text-gray-600">No articles yet.</p>
<?php else : ?>

  <ul class="space-y-3">
    <?php foreach ($articles as $a): ?>
      <li class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex justify-between items-start">
          <div>
            <a class="text-lg font-medium text-gray-900 hover:text-indigo-700" href="/articles/<?= (int)$a['id'] ?>">
              <?= e($a['title']) ?>
            </a>

            <div class="mt-1 flex items-center gap-2 text-sm text-gray-500">
              <?php if (!empty($a['profile_picture'])): ?>
                <img src="/uploads/<?= e($a['profile_picture']) ?>"
                     alt="<?= e($a['author_name']) ?>"
                     class="h-6 w-6 rounded-full object-cover border border-gray-300">
              <?php else: ?>
                <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-medium">
                  <?= strtoupper(substr($a['author_name'], 0, 1)) ?>
                </div>
              <?php endif; ?>
              <span><?= e($a['author_name']) ?></span>
            </div>

            <!-- ✨ Added: created/updated meta line -->
            <div class="mt-1 text-xs text-gray-500">
              Created: <?= e($a['created_at'] ?? '') ?>
              <?php if (!empty($a['updated_at'])): ?>
                | Updated: <?= e($a['updated_at']) ?>
              <?php endif; ?>
            </div>
            <!-- ✨ /Added -->

          </div>
          <a class="text-sm text-indigo-600 hover:text-indigo-800" href="<?= e($a['url']) ?>" target="_blank" rel="noopener">↗</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>

  <nav class="mt-6 flex items-center gap-3 text-sm justify-center">
    <?php if (($page ?? 1) > 1): ?>
      <a class="rounded-md border px-3 py-1 hover:bg-gray-50"
         href="?q=<?= urlencode($q ?? '') ?>&page=<?= (int)(($page ?? 1) - 1) ?>">Prev</a>
    <?php else: ?>
      <span class="rounded-md border px-3 py-1 opacity-50 cursor-not-allowed">Prev</span>
    <?php endif; ?>

    <span class="text-gray-600">Page <?= (int)($page ?? 1) ?> of <?= (int)($totalPages ?? 1) ?></span>

    <?php if (($page ?? 1) < ($totalPages ?? 1)): ?>
      <a class="rounded-md border px-3 py-1 hover:bg-gray-50"
         href="?q=<?= urlencode($q ?? '') ?>&page=<?= (int)(($page ?? 1) + 1) ?>">Next</a>
    <?php else: ?>
      <span class="rounded-md border px-3 py-1 opacity-50 cursor-not-allowed">Next</span>
    <?php endif; ?>
  </nav>

<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>

