<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/UserModel.php';

Auth::start();

// Already logged in? Go straight to dashboard.
if (Auth::check()) {
    header("Location: ../views/dashboard.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = isset($_POST['username']) ? trim($_POST['username']) : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    if ($username === "" || $password === "") {

        $error = "Username and password are required.";

    } else {

        $userModel = new UserModel();
        $user = $userModel->login($username);

        if (!$user) {

            $error = "Invalid username or password.";

        } elseif ($user['STATUS'] !== 'Active') {

            $error = "This account is inactive. Contact an administrator.";

        } elseif ($password !== $user['PASSWORD_HASH']) {

            // Plain-text password comparison
            $error = "Invalid username or password.";

        } else {

            // Login successful
            session_regenerate_id(true);

            $_SESSION['user_id']  = $user['USER_ID'];
            $_SESSION['username'] = $user['USERNAME'];
            $_SESSION['role']     = $user['ROLE'];

            header("Location: ../views/dashboard.php");
            exit();
        }
    }
}

// Show login page
require_once __DIR__ . '/../views/login.php';

?>