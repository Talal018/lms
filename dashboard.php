<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

requireLogin();
$user = currentUser();

// Fetch all courses with lesson count
$courses = $pdo->query("
    SELECT c.*, COUNT(l.id) AS lesson_count
    FROM courses c
    LEFT JOIN lessons l ON l.course_id = c.id
    GROUP BY c.id
    ORDER BY c.id ASC
")->fetchAll();

renderHead('Dashboard');
renderNav(true, $user['name']);
?>

<main class="max-w-6xl mx-auto px-6 py-12">

  <!-- Welcome banner -->
  <div class="mb-10 bg-gradient-to-r from-brand to-brand-light rounded-2xl p-8 text-white flex items-center justify-between">
    <div>
      <p class="text-brand-light/80 text-sm font-medium mb-1">Good to see you 👋</p>
      <h1 class="text-3xl font-bold"><?= htmlspecialchars($user['name']) ?></h1>
      <p class="mt-2 text-white/70 text-sm">Pick up where you left off, or start something new.</p>
    </div>
    <div class="hidden md:block opacity-20">
      <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
      </svg>
    </div>
  </div>

  <!-- Stats row -->
  <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-10">
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
      <p class="text-xs text-gray-400 uppercase tracking-widest">Available Courses</p>
      <p class="text-3xl font-bold text-brand mt-1"><?= count($courses) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
      <p class="text-xs text-gray-400 uppercase tracking-widest">Total Lessons</p>
      <p class="text-3xl font-bold text-brand mt-1">
        <?= array_sum(array_column($courses, 'lesson_count')) ?>
      </p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm col-span-2 md:col-span-1">
      <p class="text-xs text-gray-400 uppercase tracking-widest">Your Email</p>
      <p class="text-sm font-medium text-gray-700 mt-1 truncate"><?= htmlspecialchars($user['email']) ?></p>
    </div>
  </div>

  <!-- Courses grid -->
  <h2 class="text-xl font-bold text-gray-900 mb-5">All Courses</h2>

  <?php if (!$courses): ?>
    <div class="text-center py-20 text-gray-400">
      <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <p>No courses available yet.</p>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($courses as $course):
        // Pick a color accent per course based on id
        $accents = ['bg-violet-100 text-violet-700', 'bg-sky-100 text-sky-700', 'bg-emerald-100 text-emerald-700',
                    'bg-amber-100 text-amber-700', 'bg-rose-100 text-rose-700', 'bg-indigo-100 text-indigo-700'];
        $accent = $accents[($course['id'] - 1) % count($accents)];
      ?>
      <a href="/lms/course.php?id=<?= $course['id'] ?>"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 card-hover flex flex-col group">

        <div class="mb-4">
          <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full <?= $accent ?>">
            Course #<?= $course['id'] ?>
          </span>
        </div>

        <h3 class="text-base font-bold text-gray-900 group-hover:text-brand transition leading-snug mb-2">
          <?= htmlspecialchars($course['title']) ?>
        </h3>

        <p class="text-sm text-gray-500 flex-1 line-clamp-2">
          <?= htmlspecialchars($course['description']) ?>
        </p>

        <div class="mt-5 flex items-center justify-between text-xs text-gray-400 border-t border-gray-50 pt-4">
          <span class="flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <?= $course['lesson_count'] ?> lessons
          </span>
          <span class="text-brand font-medium group-hover:underline">View Course →</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</main>

<?php renderFooter(); ?>
