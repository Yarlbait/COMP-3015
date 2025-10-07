<?php
require_once __DIR__ . '/helpers/bootstrap.php';
require_once __DIR__ . '/helpers/flash.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/validation.php';

guest_require();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email     = trim($_POST['email'] ?? '');
    $password  = (string)($_POST['password'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $errors = [];

    if ($msg = validate_email($email))         { $errors['email']     = $msg; }
    if ($msg = validate_password($password))   { $errors['password']  = $msg; }
    if ($msg = validate_telephone($telephone)) { $errors['telephone'] = $msg; }

    if ($errors) {
        flash_set('errors', $errors);
        old_set($_POST, ['password']);
        redirect('register.php');
    }

    if (is_bcit_email($email)) {
        auth_login($email);
        redirect('dashboard.php');
    }

    flash_set('errors', ['email' => 'Only @bcit.ca emails are allowed for access in this lab.']);
    old_set($_POST, ['password']);
    redirect('register.php');
}

$errors = (array) flash_get('errors', []);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
  <main class="w-full max-w-md">
    <div class="bg-white shadow-xl rounded-2xl p-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">Create an account</h1>

      <?php if (!empty($errors)): ?>
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
          <h2 class="font-semibold text-red-800 mb-2">Please fix the following:</h2>
          <ul class="list-disc ml-5 text-sm text-red-700">
            <?php foreach ($errors as $msg): ?>
              <li><?php echo e($msg); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- email -->
      <form method="post" action="register.php" novalidate class="space-y-5">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            id="email"
            name="email"
            type="email"
            required
            value="<?php echo old('email'); ?>"
            class="mt-1 block w-full rounded-lg border <?php echo isset($errors['email']) ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'; ?> bg-white px-3 py-2 text-gray-900 shadow-sm outline-none"
            aria-invalid="<?php echo isset($errors['email']) ? 'true' : 'false'; ?>"
          />
          <?php if (isset($errors['email'])): ?>
            <p class="mt-1 text-sm text-red-600"><?php echo e($errors['email']); ?></p>
          <?php endif; ?>
        </div>

        <!-- telephpne -->
        <div>
          <label for="telephone" class="block text-sm font-medium text-gray-700">Telephone</label>
          <input
            id="telephone"
            name="telephone"
            type="tel"
            required
            value="<?php echo old('telephone'); ?>"
            placeholder="e.g., 604-555-1234"
            class="mt-1 block w-full rounded-lg border <?php echo isset($errors['telephone']) ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'; ?> bg-white px-3 py-2 text-gray-900 shadow-sm outline-none"
            aria-invalid="<?php echo isset($errors['telephone']) ? 'true' : 'false'; ?>"
          />
          <?php if (isset($errors['telephone'])): ?>
            <p class="mt-1 text-sm text-red-600"><?php echo e($errors['telephone']); ?></p>
          <?php endif; ?>
        </div>

        <!-- password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            id="password"
            name="password"
            type="password"
            required
            class="mt-1 block w-full rounded-lg border <?php echo isset($errors['password']) ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'; ?> bg-white px-3 py-2 text-gray-900 shadow-sm outline-none"
            aria-invalid="<?php echo isset($errors['password']) ? 'true' : 'false'; ?>"
          />
          <p class="mt-1 text-xs text-gray-500">Must be longer than 8 characters and include at least one special character.</p>
          <?php if (isset($errors['password'])): ?>
            <p class="mt-1 text-sm text-red-600"><?php echo e($errors['password']); ?></p>
          <?php endif; ?>
        </div>

        <!-- submit button -->
        <button
          type="submit"
          class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-white font-medium shadow hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300"
        >
          Create account
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-gray-600">
        Already have an account?
        <a href="login.php" class="font-medium text-indigo-600 hover:text-indigo-700">Sign in</a>
      </p>
    </div>

    <p class="mt-6 text-center text-xs text-gray-500">
      For this lab: only <span class="font-semibold">@bcit.ca</span> emails are allowed.
    </p>
  </main>
</body>
</html>

