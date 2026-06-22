<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /lms/dashboard.php');
} else {
    header('Location: /lms/login.php');
}
exit;
