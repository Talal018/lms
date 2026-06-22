<?php
function renderHead(string $title = 'LearnFlow LMS') {
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$title} · LearnFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: { DEFAULT: '#4F46E5', light: '#818CF8', dark: '#3730A3' },
            surface: '#F8F7FF',
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui'],
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { background-color: #F8F7FF; }
    .card-hover { transition: box-shadow 0.2s, transform 0.2s; }
    .card-hover:hover { box-shadow: 0 8px 30px rgba(79,70,229,0.15); transform: translateY(-2px); }
  </style>
</head>
<body class="font-sans text-gray-800 min-h-screen">
HTML;
}

function renderNav(bool $loggedIn = false, string $userName = '') {
    $right = $loggedIn
        ? '<span class="text-sm text-gray-500">Hi, <strong class="text-brand">' . htmlspecialchars($userName) . '</strong></span>
           <a href="/logout.php" class="text-sm px-4 py-2 rounded-lg border border-brand text-brand hover:bg-brand hover:text-white transition">Logout</a>'
        : '<a href="/login.php" class="text-sm text-gray-600 hover:text-brand transition">Login</a>
           <a href="/register.php" class="text-sm px-4 py-2 bg-brand text-white rounded-lg hover:bg-brand-dark transition">Register</a>';

    echo <<<HTML
<nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
  <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
    <a href="/" class="flex items-center gap-2">
      <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
      </div>
      <span class="font-bold text-gray-900 text-lg tracking-tight">LearnFlow</span>
    </a>
    <div class="flex items-center gap-4">{$right}</div>
  </div>
</nav>
HTML;
}

function renderFooter() {
    echo <<<HTML
<footer class="mt-20 py-8 border-t border-gray-100 bg-white">
  <div class="max-w-6xl mx-auto px-6 text-center text-sm text-gray-400">
    &copy; 2025 LearnFlow LMS · Built with PHP & Tailwind CSS
  </div>
</footer>
</body>
</html>
HTML;
}
