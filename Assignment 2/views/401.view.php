<?php require_once __DIR__ . '/header.php'; ?>

<main class="flex flex-col items-center justify-center text-center min-h-[70vh] px-4">
  <div class="max-w-lg">
    <h1 class="text-6xl font-bold text-indigo-600 mb-4">401</h1>
    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Unauthorized Access</h2>
    <p class="text-gray-600 mb-6">
      You don’t have permission to view this page or perform this action.
      Please log in with the correct account to continue.
    </p>

    <div class="flex flex-wrap justify-center gap-3">
      <?php if (!auth_id()): ?>
        <a href="/login" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
          Login
        </a>
        <a href="/register" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
          Register
        </a>
      <?php else: ?>
        <a href="/" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
          Go Back Home
        </a>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
