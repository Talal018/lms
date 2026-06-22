<?php
session_start();
session_destroy();
header('Location: /lms/login.php');
exit;
