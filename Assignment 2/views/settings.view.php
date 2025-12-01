<?php require __DIR__ . '/header.php'; ?>
<h1 class="text-2xl font-semibold mb-4">Settings</h1>

<?php if ($err = ($_SESSION['errors'] ?? null)) : ?>
  <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
    <?php foreach ($err as $msg) : ?><div><?= e($msg) ?></div><?php endforeach; ?>
  </div>
  <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form method="post" action="/settings" enctype="multipart/form-data" class="space-y-4 max-w-lg">
  <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
  <label class="block">
    <span class="mb-1 block text-sm font-medium">Name</span>
    <input type="text" name="name" value="<?= e($user['name'] ?? '') ?>"
      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
  </label>

  <label class="block">
    <span class="mb-1 block text-sm font-medium">Profile picture</span>
    <input type="file" name="profile_picture"
      class="block w-full text-sm file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700">
  </label>

  <?php if (!empty($user['profile_picture'])): ?>
    <div class="mt-2">
      <img src="/uploads/<?= e($user['profile_picture']) ?>" alt="avatar" class="h-20 w-20 rounded-full object-cover ring-1 ring-gray-200">
    </div>
  <?php endif; ?>

  <div class="pt-2">
    <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save</button>
  </div>
</form>
<?php require __DIR__ . '/footer.php'; ?>

