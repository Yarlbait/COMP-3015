<?php
require_once __DIR__ . '/helpers/bootstrap.php';
require_once __DIR__ . '/helpers/auth.php';

auth_require();
$email = $_SESSION['user_email'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50">
  <!-- Header bar -->
  <header class="bg-white border-b border-gray-200">
    <div class="mx-auto max-w-4xl px-4 py-4 flex items-center justify-between">
      <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
      <nav class="flex items-center gap-4">
        <span class="text-sm text-gray-600">Signed in as <strong class="text-gray-900"><?php echo e($email); ?></strong></span>
        <a href="index.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Home</a>
        <a href="logout.php" class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">Logout</a>
      </nav>
    </div>
  </header>

  <!-- Main -->
  <main class="mx-auto max-w-4xl px-4 py-10">
    <div class="bg-white rounded-2xl shadow p-8">
      <h2 class="text-lg font-semibold text-gray-900">Welcome</h2>
      <p class="mt-2 text-gray-700">
        You’re logged in as <strong><?php echo e($email); ?></strong>. This page is only accessible to authenticated users.
      </p>

      <!-- Cards -->
      <div class="mt-8 grid gap-6 sm:grid-cols-2">
        <div class="rounded-xl border border-gray-200 p-5">
          <h3 class="font-medium text-gray-900">Next steps</h3>
          <p class="mt-1 text-sm text-gray-600">There are no Next Steps!.</p>
        </div>
        <div class="rounded-xl border border-gray-200 p-5">
          <h3 class="font-medium text-gray-900">Quick actions</h3>
          <ul class="mt-2 list-disc ml-5 text-sm text-gray-700">
            <li><a class="text-indigo-600 hover:text-indigo-700" href="index.php">Back to Home</a></li>
            <li><a class="text-indigo-600 hover:text-indigo-700" href="logout.php">Sign out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="mt-10 py-6 text-center text-xs text-gray-500">
    COMP 3015 • Lab 3 •
  </footer>
</body>
</html>