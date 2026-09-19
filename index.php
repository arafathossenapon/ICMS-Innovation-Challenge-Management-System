<?php

require_once __DIR__ . '/app/core/Auth.php';

Auth::start();

if (Auth::check()) {
    header("Location: app/views/dashboard.php");
} else {
    header("Location: app/controllers/LoginController.php");
}

exit();

?>
