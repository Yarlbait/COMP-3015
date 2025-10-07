<?php
require_once __DIR__ . '/helpers/bootstrap.php';
require_once __DIR__ . '/helpers/auth.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex flex-col">
  <!-- Header -->
  <header class="bg-white border-b border-gray-200">
    <div class="mx-auto max-w-4xl px-4 py-4 flex items-center justify-between">
      <h1 class="text-xl font-semibold text-gray-900">COMP 3015 – Lab 3</h1>
      <nav class="flex items-center gap-4">
        <?php if (is_authenticated()): ?>
          <span class="text-sm text-gray-600">Signed in as <strong class="text-gray-900"><?php echo e($_SESSION['user_email']); ?></strong></span>
          <a href="dashboard.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Dashboard</a>
          <a href="logout.php" class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">Logout</a>
        <?php else: ?>
          <a href="login.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Login</a>
          <a href="register.php" class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- main -->
  <main class="flex-1 flex items-center justify-center px-4">
    <div class="max-w-lg w-full bg-white shadow-xl rounded-2xl p-8 text-center">
      <h2 class="text-2xl font-bold text-gray-900 mb-3">Welcome to Your PHP Lab App</h2>
      <p class="text-gray-600 mb-6">
        This lab presentation demonstrates session-based authentication, redirects, and flash validation using PHP.
      </p>

      <?php if (is_authenticated()): ?>
        <a href="dashboard.php" class="inline-block rounded-lg bg-indigo-600 px-4 py-2.5 text-white font-medium shadow hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">
          Go to Dashboard
        </a>
      <?php else: ?>
        <div class="flex flex-col sm:flex-row justify-center gap-3">
          <a href="login.php" class="inline-block rounded-lg bg-indigo-600 px-4 py-2.5 text-white font-medium shadow hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">
            Sign In
          </a>
          <a href="register.php" class="inline-block rounded-lg bg-white px-4 py-2.5 text-indigo-600 font-medium border border-indigo-200 shadow hover:bg-indigo-50 focus:outline-none focus:ring-4 focus:ring-indigo-200">
            Create Account
          </a>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <!-- footer -->
  <footer class="py-6 text-center text-xs text-gray-500">
    &copy; <?php echo date('Y'); ?> BCIT • COMP 3015 • Lab 3 – PHP Sessions and Validation
  </footer>
</body>
</html>
