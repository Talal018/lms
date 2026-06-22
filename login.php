<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

redirectIfLoggedIn();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: /dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

renderHead('Login');
renderNav(false);
?>

<main class="max-w-md mx-auto px-6 py-16">
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="mb-8 text-center">
      <div class="w-14 h-14 bg-brand/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-gray-900">Welcome back</h1>
      <p class="text-gray-500 text-sm mt-1">Log in to continue learning</p>
    </div>

    <?php if ($error): ?>
      <div class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-600">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="space-y-4">

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition"
            placeholder="john@example.com" autofocus>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" name="password"
            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition"
            placeholder="Your password">
        </div>

      </div>

      <button type="submit"
        class="mt-6 w-full bg-brand hover:bg-brand-dark text-white font-semibold py-3 rounded-lg transition text-sm">
        Log In
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
      Don't have an account?
      <a href="/register.php" class="text-brand font-medium hover:underline">Register free</a>
    </p>
  </div>
</main>

<?php renderFooter(); ?>
