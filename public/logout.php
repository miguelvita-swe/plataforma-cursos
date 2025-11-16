<?php
require_once __DIR__ . '/../includes/auth-functions.php';
auth_logout_user();
header('Location: /');
exit;
