<?php require __DIR__ . '/header.php'; ?>
<h1 class="text-2xl font-semibold mb-4">Login</h1>

<?php if ($msg = flash('error')): ?>
  <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700"><?= e($msg) ?></div>
<?php endif; ?>

<form method="post" action="/login" class="space-y-4 max-w-md">
  <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
  <label class="block">
    <span class="mb-1 block text-sm font-medium">Email</span>
    <input type="email" name="email" required
      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
  </label>
  <label class="block">
    <span class="mb-1 block text-sm font-medium">Password</span>
    <input type="password" name="password" required
      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
  </label>
  <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Login</button>
</form>
<?php require __DIR__ . '/footer.php'; ?>
