<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

requireLogin();
$user = currentUser();

$lessonId = (int)($_GET['id'] ?? 0);

// Fetch lesson with its course
$stmt = $pdo->prepare("
    SELECT l.*, c.title AS course_title, c.id AS course_id
    FROM lessons l
    JOIN courses c ON c.id = l.course_id
    WHERE l.id = ?
");
$stmt->execute([$lessonId]);
$lesson = $stmt->fetch();

if (!$lesson) {
    header('Location: /dashboard.php');
    exit;
}

// Fetch prev/next lessons for navigation
$nav = $pdo->prepare("
    SELECT id, title, sort_order FROM lessons
    WHERE course_id = ?
    ORDER BY sort_order ASC, id ASC
");
$nav->execute([$lesson['course_id']]);
$allLessons = $nav->fetchAll();

$currentIndex = array_search($lessonId, array_column($allLessons, 'id'));
$prevLesson   = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
$nextLesson   = $currentIndex < count($allLessons) - 1 ? $allLessons[$currentIndex + 1] : null;

renderHead(htmlspecialchars($lesson['title']));
renderNav(true, $user['name']);
?>

<main class="max-w-6xl mx-auto px-6 py-10">

  <!-- Breadcrumb -->
  <nav class="mb-6 text-sm text-gray-400 flex items-center gap-2 flex-wrap">
    <a href="/dashboard.php" class="hover:text-brand transition">Dashboard</a>
    <span>›</span>
    <a href="/course.php?id=<?= $lesson['course_id'] ?>" class="hover:text-brand transition">
      <?= htmlspecialchars($lesson['course_title']) ?>
    </a>
    <span>›</span>
    <span class="text-gray-700 font-medium"><?= htmlspecialchars($lesson['title']) ?></span>
  </nav>

  <div class="flex gap-8">

    <!-- Sidebar: lesson list -->
    <aside class="hidden lg:block w-64 flex-shrink-0">
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sticky top-20">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 px-2">
          Course Lessons
        </h3>
        <ul class="space-y-1">
          <?php foreach ($allLessons as $i => $l): ?>
            <li>
              <a href="/lesson.php?id=<?= $l['id'] ?>"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                  <?= $l['id'] == $lessonId
                    ? 'bg-brand text-white font-semibold'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-brand' ?>">
                <span class="w-5 h-5 rounded-full text-xs flex items-center justify-center flex-shrink-0
                  <?= $l['id'] == $lessonId ? 'bg-white/20' : 'bg-gray-100' ?>">
                  <?= $i + 1 ?>
                </span>
                <span class="truncate"><?= htmlspecialchars($l['title']) ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </aside>

    <!-- Main lesson content -->
    <div class="flex-1 min-w-0">
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6 pb-5 border-b border-gray-100">
          <?= htmlspecialchars($lesson['title']) ?>
        </h1>

        <!-- Lesson body — rendered as HTML -->
        <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed
            [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-gray-900 [&_h2]:mt-6 [&_h2]:mb-3
            [&_h3]:text-base [&_h3]:font-semibold [&_h3]:text-gray-800 [&_h3]:mt-4 [&_h3]:mb-2
            [&_p]:mb-4
            [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:mb-4 [&_ul]:space-y-1
            [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:mb-4
            [&_code]:bg-gray-100 [&_code]:text-brand [&_code]:px-1 [&_code]:rounded [&_code]:text-sm
            [&_pre]:bg-gray-900 [&_pre]:text-gray-100 [&_pre]:p-4 [&_pre]:rounded-xl [&_pre]:overflow-x-auto [&_pre]:mb-4
            [&_blockquote]:border-l-4 [&_blockquote]:border-brand/40 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-gray-500">
          <?= $lesson['content'] ?>
        </div>
      </div>

      <!-- Prev / Next navigation -->
      <div class="flex items-center justify-between gap-4">
        <div class="flex-1">
          <?php if ($prevLesson): ?>
            <a href="/lesson.php?id=<?= $prevLesson['id'] ?>"
              class="flex items-center gap-3 bg-white border border-gray-100 rounded-xl px-5 py-3 shadow-sm hover:border-brand hover:shadow-md transition group">
              <svg class="w-4 h-4 text-gray-400 group-hover:text-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
              <div>
                <p class="text-xs text-gray-400">Previous</p>
                <p class="text-sm font-semibold text-gray-700 group-hover:text-brand truncate max-w-[180px]">
                  <?= htmlspecialchars($prevLesson['title']) ?>
                </p>
              </div>
            </a>
          <?php endif; ?>
        </div>

        <div class="flex-1 flex justify-end">
          <?php if ($nextLesson): ?>
            <a href="/lesson.php?id=<?= $nextLesson['id'] ?>"
              class="flex items-center gap-3 bg-white border border-gray-100 rounded-xl px-5 py-3 shadow-sm hover:border-brand hover:shadow-md transition group text-right">
              <div>
                <p class="text-xs text-gray-400">Next</p>
                <p class="text-sm font-semibold text-gray-700 group-hover:text-brand truncate max-w-[180px]">
                  <?= htmlspecialchars($nextLesson['title']) ?>
                </p>
              </div>
              <svg class="w-4 h-4 text-gray-400 group-hover:text-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
          <?php elseif (!$nextLesson && !$prevLesson === false): ?>
            <a href="/course.php?id=<?= $lesson['course_id'] ?>"
              class="text-sm text-brand hover:underline flex items-center gap-1">
              Course complete! Back to overview →
            </a>
          <?php endif; ?>
        </div>
      </div>

      <div class="mt-6">
        <a href="/course.php?id=<?= $lesson['course_id'] ?>" class="text-sm text-gray-400 hover:text-brand transition flex items-center gap-1">
          ← Back to <?= htmlspecialchars($lesson['course_title']) ?>
        </a>
      </div>
    </div>
  </div>

</main>

<?php renderFooter(); ?>
