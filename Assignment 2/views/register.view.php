<?php require_once __DIR__ . '/header.php'; ?> 

<h1 class="text-2xl font-semibold mb-4">Create your account</h1>

<?php if ($err = ($_SESSION['errors'] ?? null)) : ?>
  <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
    <?php foreach ($err as $msg) : ?>
      <div><?= e($msg) ?></div>
    <?php endforeach; ?>
  </div>
  <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form method="post" action="/register" class="space-y-4 max-w-md">
  <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

  <label class="block">
    <span class="mb-1 block text-sm font-medium">Name</span>
    <input
      type="text"
      name="name"
      value="<?= e($_SESSION['old']['name'] ?? '') ?>"
      required
      autocomplete="name"
      autocapitalize="words"
      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
    >
  </label>

  <label class="block">
    <span class="mb-1 block text-sm font-medium">Email</span>
    <input
      type="email"
      name="email"
      value="<?= e($_SESSION['old']['email'] ?? '') ?>"
      required
      autocomplete="email"
      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
    >
  </label>

  <label class="block">
    <span class="mb-1 block text-sm font-medium">Password</span>
    <input
      type="password"
      name="password"
      minlength="8"
      pattern="(?=.*[^a-zA-Z0-9]).+"
      title="Password must be at least 8 characters and include at least one special symbol."
      required
      autocomplete="new-password"
      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
    >
    <span class="mt-1 block text-xs text-gray-500">At least 8 characters and a special character.</span>
  </label>

  <div class="pt-2 flex items-center gap-3">
    <button
      class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
      type="submit"
    >
      Create account
    </button>
    <a href="/login" class="text-sm text-gray-600 hover:text-gray-800">Already have an account? Log in</a>
  </div>
</form>

<?php unset($_SESSION['old']); ?>
<?php require_once __DIR__ . '/footer.php'; ?>



