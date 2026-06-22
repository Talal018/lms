<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

redirectIfLoggedIn();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!$name)                        $errors[] = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 6)         $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)        $errors[] = 'Passwords do not match.';

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'This email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)")
                ->execute([$name, $email, $hash]);
            $success = 'Account created! You can now log in.';
        }
    }
}

renderHead('Register');
renderNav(false);
?>

<main class="max-w-md mx-auto px-6 py-16">
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="mb-8 text-center">
      <div class="w-14 h-14 bg-brand/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-gray-900">Create your account</h1>
      <p class="text-gray-500 text-sm mt-1">Start learning today — it's free</p>
    </div>

    <?php if ($errors): ?>
      <div class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4">
        <?php foreach ($errors as $e): ?>
          <p class="text-sm text-red-600">• <?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="mb-5 bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-700">
        <?= htmlspecialchars($success) ?>
        <a href="/lms/login.php" class="font-semibold underline ml-1">Login now →</a>
      </div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="space-y-4">

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
          <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition"
            placeholder="John Doe">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition"
            placeholder="john@example.com">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" name="password"
            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition"
            placeholder="Min 6 characters">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input type="password" name="confirm"
            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition"
            placeholder="Repeat password">
        </div>

      </div>

      <button type="submit"
        class="mt-6 w-full bg-brand hover:bg-brand-dark text-white font-semibold py-3 rounded-lg transition text-sm">
        Create Account
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
      Already have an account?
      <a href="/lms/login.php" class="text-brand font-medium hover:underline">Log in</a>
    </p>
  </div>
</main>

<?php renderFooter(); ?>
