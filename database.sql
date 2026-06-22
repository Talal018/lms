-- ============================================================
--  LearnFlow LMS — Database Schema + Seed Data
--  Run this in your MySQL/MariaDB client or phpMyAdmin
-- ============================================================

CREATE DATABASE IF NOT EXISTS lms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lms_db;

-- ------------------------------------------------------------
-- USERS
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Demo student (password: student123)
INSERT INTO users (name, email, password) VALUES
('Demo Student', 'student@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ------------------------------------------------------------
-- COURSES
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS courses (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO courses (title, description) VALUES
('HTML & CSS Fundamentals',
 'Learn the building blocks of the web. Understand HTML structure, CSS styling, and how to build clean, responsive web pages from scratch.'),

('JavaScript for Beginners',
 'Master the JavaScript programming language. From variables and functions to DOM manipulation and event handling — your path to interactive web apps.'),

('PHP & MySQL: Back-End Basics',
 'Get started with server-side development. Build dynamic web applications with PHP and learn how to store and query data using MySQL.'),

('Git & Version Control',
 'Learn how to manage your code professionally using Git. Understand branching, merging, pull requests, and team collaboration workflows.');

-- ------------------------------------------------------------
-- LESSONS
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lessons (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id   INT UNSIGNED NOT NULL,
    title       VARCHAR(255) NOT NULL,
    summary     VARCHAR(255) DEFAULT NULL,   -- short subtitle shown in list
    content     LONGTEXT,                   -- full HTML content
    sort_order  INT UNSIGNED DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Course 1: HTML & CSS ─────────────────────────────────────

INSERT INTO lessons (course_id, title, summary, content, sort_order) VALUES

(1, 'Introduction to HTML',
 'What is HTML and how browsers read it',
'<h2>What is HTML?</h2>
<p>HTML stands for <strong>HyperText Markup Language</strong>. It is the standard language used to create and structure content on the web. Every webpage you visit is built on an HTML foundation.</p>
<h2>Basic Structure</h2>
<p>Every HTML document follows this skeleton:</p>
<pre><code>&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
  &lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;title&gt;My Page&lt;/title&gt;
  &lt;/head&gt;
  &lt;body&gt;
    &lt;h1&gt;Hello, World!&lt;/h1&gt;
  &lt;/body&gt;
&lt;/html&gt;</code></pre>
<h2>Key Tags to Know</h2>
<ul>
  <li><code>&lt;h1&gt;</code> to <code>&lt;h6&gt;</code> — Headings</li>
  <li><code>&lt;p&gt;</code> — Paragraph</li>
  <li><code>&lt;a href=""&gt;</code> — Hyperlink</li>
  <li><code>&lt;img src=""&gt;</code> — Image</li>
  <li><code>&lt;div&gt;</code> — Generic container block</li>
  <li><code>&lt;span&gt;</code> — Inline container</li>
</ul>
<blockquote>HTML gives your content <em>meaning</em>. CSS gives it <em>style</em>. JavaScript gives it <em>behaviour</em>.</blockquote>',
1),

(1, 'CSS Selectors & Properties',
 'How to target and style HTML elements',
'<h2>What is CSS?</h2>
<p><strong>Cascading Style Sheets (CSS)</strong> control the visual presentation of HTML elements — colours, fonts, spacing, layout, and more.</p>
<h2>Linking CSS to HTML</h2>
<pre><code>&lt;link rel="stylesheet" href="styles.css"&gt;</code></pre>
<h2>Selector Types</h2>
<ul>
  <li><code>p</code> — Selects all &lt;p&gt; elements (element selector)</li>
  <li><code>.card</code> — Selects elements with class="card" (class selector)</li>
  <li><code>#header</code> — Selects the element with id="header" (ID selector)</li>
</ul>
<h2>Common Properties</h2>
<pre><code>body {
  font-family: Arial, sans-serif;
  background-color: #f8f9fa;
  color: #333;
}

h1 {
  font-size: 2rem;
  color: #4F46E5;
  margin-bottom: 1rem;
}</code></pre>',
2),

(1, 'Building a Responsive Layout',
 'Flexbox, Grid, and media queries',
'<h2>Responsive Design</h2>
<p>Responsive design means your website looks good on all screen sizes — from mobile phones to large desktop monitors.</p>
<h2>Flexbox</h2>
<p>Flexbox is a one-dimensional layout method perfect for aligning items in a row or column.</p>
<pre><code>.container {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.card {
  flex: 1 1 250px;
}</code></pre>
<h2>CSS Grid</h2>
<p>Grid is a two-dimensional layout system — great for full page layouts.</p>
<pre><code>.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}</code></pre>
<h2>Media Queries</h2>
<pre><code>@media (max-width: 768px) {
  .grid {
    grid-template-columns: 1fr;
  }
}</code></pre>',
3);

-- ── Course 2: JavaScript ────────────────────────────────────

INSERT INTO lessons (course_id, title, summary, content, sort_order) VALUES

(2, 'Variables & Data Types',
 'let, const, var and the six JS types',
'<h2>Declaring Variables</h2>
<p>JavaScript has three ways to declare variables:</p>
<ul>
  <li><code>let</code> — block-scoped, can be reassigned</li>
  <li><code>const</code> — block-scoped, cannot be reassigned</li>
  <li><code>var</code> — function-scoped (legacy, avoid it)</li>
</ul>
<pre><code>let age = 25;
const name = "Hassan";
var oldStyle = true; // avoid this</code></pre>
<h2>Data Types</h2>
<ul>
  <li><strong>String</strong> — <code>"Hello"</code></li>
  <li><strong>Number</strong> — <code>42</code>, <code>3.14</code></li>
  <li><strong>Boolean</strong> — <code>true</code>, <code>false</code></li>
  <li><strong>Null</strong> — intentional absence of value</li>
  <li><strong>Undefined</strong> — variable declared but not assigned</li>
  <li><strong>Object</strong> — <code>{ key: "value" }</code></li>
</ul>',
1),

(2, 'Functions & Scope',
 'Writing reusable blocks of code',
'<h2>Declaring Functions</h2>
<pre><code>// Function declaration
function greet(name) {
  return "Hello, " + name + "!";
}

// Arrow function (ES6)
const greet = (name) => `Hello, ${name}!`;

console.log(greet("World")); // Hello, World!</code></pre>
<h2>Scope</h2>
<p>Scope determines where a variable is accessible in your code.</p>
<ul>
  <li><strong>Global scope</strong> — accessible everywhere</li>
  <li><strong>Function scope</strong> — only inside the function</li>
  <li><strong>Block scope</strong> — only inside <code>{ }</code> blocks (let/const)</li>
</ul>
<pre><code>const x = 10; // global

function example() {
  const y = 20; // function scope
  console.log(x); // ✓ accessible
}

console.log(y); // ✗ ReferenceError</code></pre>',
2),

(2, 'DOM Manipulation',
 'Selecting and modifying HTML with JavaScript',
'<h2>What is the DOM?</h2>
<p>The <strong>Document Object Model (DOM)</strong> is a tree-like representation of your HTML that JavaScript can read and modify in real time.</p>
<h2>Selecting Elements</h2>
<pre><code>const title = document.querySelector("h1");
const buttons = document.querySelectorAll(".btn");
const byId = document.getElementById("myDiv");</code></pre>
<h2>Modifying Elements</h2>
<pre><code>title.textContent = "New Title!";
title.style.color = "purple";
title.classList.add("highlight");</code></pre>
<h2>Event Listeners</h2>
<pre><code>const btn = document.querySelector("#myBtn");

btn.addEventListener("click", () => {
  alert("Button clicked!");
});</code></pre>',
3);

-- ── Course 3: PHP & MySQL ───────────────────────────────────

INSERT INTO lessons (course_id, title, summary, content, sort_order) VALUES

(3, 'PHP Syntax & Variables',
 'Getting started with server-side PHP',
'<h2>What is PHP?</h2>
<p>PHP is a server-side scripting language designed for web development. Unlike HTML/CSS/JS which run in the browser, PHP runs on the <strong>web server</strong> before the page is sent to the user.</p>
<h2>Basic Syntax</h2>
<pre><code>&lt;?php
  $name = "Hassan";
  $age = 28;
  echo "Hello, " . $name . "!";
  // Output: Hello, Hassan!
?&gt;</code></pre>
<h2>Variable Types</h2>
<ul>
  <li>String: <code>$str = "hello";</code></li>
  <li>Integer: <code>$num = 42;</code></li>
  <li>Float: <code>$price = 9.99;</code></li>
  <li>Boolean: <code>$active = true;</code></li>
  <li>Array: <code>$items = ["a", "b", "c"];</code></li>
</ul>',
1),

(3, 'Connecting to MySQL with PDO',
 'Secure database connections in PHP',
'<h2>Why PDO?</h2>
<p>PDO (PHP Data Objects) is the recommended way to connect to databases in PHP. It supports prepared statements which protect against SQL injection.</p>
<h2>Making a Connection</h2>
<pre><code>&lt;?php
$host = "localhost";
$db   = "my_database";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "Connected successfully!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?&gt;</code></pre>
<h2>Running a Query</h2>
<pre><code>$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);</code></pre>',
2);

-- ── Course 4: Git ───────────────────────────────────────────

INSERT INTO lessons (course_id, title, summary, content, sort_order) VALUES

(4, 'What is Git & Why Use It',
 'Version control fundamentals explained',
'<h2>The Problem Git Solves</h2>
<p>Imagine working on a project and accidentally deleting important code. Without version control, it is gone forever. Git keeps a full history of every change, so you can always go back.</p>
<h2>Key Concepts</h2>
<ul>
  <li><strong>Repository (Repo)</strong> — A folder tracked by Git</li>
  <li><strong>Commit</strong> — A saved snapshot of your code</li>
  <li><strong>Branch</strong> — An independent line of development</li>
  <li><strong>Merge</strong> — Combining two branches together</li>
</ul>
<h2>Installing Git</h2>
<p>Download from <code>https://git-scm.com</code> and verify with:</p>
<pre><code>git --version</code></pre>',
1),

(4, 'Core Git Commands',
 'init, add, commit, push — the daily workflow',
'<h2>Starting a Project</h2>
<pre><code># Initialize a new repo
git init

# Clone an existing repo
git clone https://github.com/user/repo.git</code></pre>
<h2>The Daily Workflow</h2>
<pre><code># Check what has changed
git status

# Stage files for commit
git add .           # all files
git add index.php   # specific file

# Commit with a message
git commit -m "Add login page"

# Push to remote (GitHub)
git push origin main</code></pre>
<h2>Branching</h2>
<pre><code># Create and switch to a new branch
git checkout -b feature/login

# Switch back to main
git checkout main

# Merge feature branch into main
git merge feature/login</code></pre>',
2);
