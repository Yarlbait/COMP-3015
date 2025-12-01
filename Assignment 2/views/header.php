<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Article Aggregator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="/favicon.ico">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#1e88e5',
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
<header class="bg-gray-900 text-white">
  <nav class="mx-auto max-w-5xl flex items-center justify-between px-4 py-3">
    <div class="flex items-center gap-4">
      <a class="font-semibold tracking-wide hover:text-indigo-300" href="/">Article Aggregator</a>
      <a class="text-sm text-gray-300 hover:text-white" href="/articles/create">Submit</a>
      <?php if (auth_id()): ?>
        <a class="text-sm text-gray-300 hover:text-white" href="/settings">Settings</a>
      <?php endif; ?>
    </div>
    <div class="flex items-center gap-3">
      <?php if (auth_id()): ?>
        <form action="/logout" method="post">
          <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
          <button type="submit" class="rounded-md bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700">Logout</button>
        </form>
      <?php else: ?>
        <a class="rounded-md bg-white/10 px-3 py-1.5 text-sm font-medium text-white hover:bg-white/20" href="/login">Login</a>
        <a class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700" href="/register">Register</a>
      <?php endif; ?>
    </div>
  </nav>
</header>

<?php if ($msg = flash('success')): ?>
  <div class="mx-auto max-w-5xl px-4 mt-4">
    <div class="rounded-md bg-green-100 text-green-800 px-4 py-2 shadow-sm">
      <?= e($msg) ?>
    </div>
  </div>
<?php endif; ?>
<?php if ($msg = flash('error')): ?>
  <div class="mx-auto max-w-5xl px-4 mt-4">
    <div class="rounded-md bg-red-100 text-red-800 px-4 py-2 shadow-sm">
      <?= e($msg) ?>
    </div>
  </div>
<?php endif; ?>

<main class="mx-auto max-w-5xl px-4 py-6">


