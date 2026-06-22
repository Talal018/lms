<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

requireLogin();
$user = currentUser();

$courseId = (int)($_GET['id'] ?? 0);

// Fetch course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$courseId]);
$course = $stmt->fetch();

if (!$course) {
    header('Location: /lms/dashboard.php');
    exit;
}

// Fetch lessons
$lessonStmt = $pdo->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY sort_order ASC, id ASC");
$lessonStmt->execute([$courseId]);
$lessons = $lessonStmt->fetchAll();

renderHead(htmlspecialchars($course['title']));
renderNav(true, $user['name']);
?>

<main class="max-w-4xl mx-auto px-6 py-12">

  <!-- Breadcrumb -->
  <nav class="mb-6 text-sm text-gray-400 flex items-center gap-2">
    <a href="/lms/dashboard.php" class="hover:text-brand transition">Dashboard</a>
    <span>›</span>
    <span class="text-gray-700 font-medium"><?= htmlspecialchars($course['title']) ?></span>
  </nav>

  <!-- Course header -->
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-8">
    <div class="flex items-start gap-5">
      <div class="w-14 h-14 rounded-xl bg-brand/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
      </div>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($course['title']) ?></h1>
        <p class="text-gray-500 leading-relaxed"><?= htmlspecialchars($course['description']) ?></p>
        <div class="mt-4 flex items-center gap-3">
          <span class="text-xs bg-brand/10 text-brand font-semibold px-3 py-1 rounded-full">
            <?= count($lessons) ?> Lessons
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Lessons list -->
  <h2 class="text-lg font-bold text-gray-800 mb-4">Course Lessons</h2>

  <?php if (!$lessons): ?>
    <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 text-gray-400">
      <p>No lessons added to this course yet.</p>
    </div>
  <?php else: ?>
    <div class="space-y-3">
      <?php foreach ($lessons as $i => $lesson): ?>
      <a href="/lms/lesson.php?id=<?= $lesson['id'] ?>"
        class="flex items-center gap-4 bg-white border border-gray-100 rounded-xl px-6 py-4 shadow-sm card-hover group">

        <!-- Number badge -->
        <div class="w-9 h-9 rounded-full bg-brand/10 text-brand text-sm font-bold flex items-center justify-center flex-shrink-0 group-hover:bg-brand group-hover:text-white transition">
          <?= $i + 1 ?>
        </div>

        <div class="flex-1 min-w-0">
          <p class="font-semibold text-gray-900 group-hover:text-brand transition truncate">
            <?= htmlspecialchars($lesson['title']) ?>
          </p>
          <?php if (!empty($lesson['summary'])): ?>
            <p class="text-xs text-gray-400 mt-0.5 truncate"><?= htmlspecialchars($lesson['summary']) ?></p>
          <?php endif; ?>
        </div>

        <svg class="w-5 h-5 text-gray-300 group-hover:text-brand transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="mt-8">
    <a href="/lms/dashboard.php" class="text-sm text-brand hover:underline flex items-center gap-1">
      ← Back to Dashboard
    </a>
  </div>

</main>

<?php renderFooter(); ?>
