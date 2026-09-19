<?php

// ================================
// AUTH HELPER
// Central place for session + role checks so every
// controller doesn't repeat the same logic.
// ================================

class Auth
{
    // Starts the session if it hasn't been started yet.
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check()
    {
        self::start();
        return isset($_SESSION['user_id']);
    }

    public static function user()
    {
        self::start();

        if (!self::check()) {
            return null;
        }

        return array(
            'user_id'  => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role'     => $_SESSION['role'],
        );
    }

    // Redirects to login if not authenticated.
    // Pass a role (or array of roles) to also enforce access control.
    public static function requireLogin($role = null)
    {
        self::start();

        if (!self::check()) {
            header("Location: LoginController.php");
            exit();
        }

        if ($role !== null) {

            $allowed = is_array($role) ? $role : array($role);

            if (!in_array($_SESSION['role'], $allowed, true)) {
                http_response_code(403);
                die("Access Denied! This page requires: " . implode(", ", $allowed));
            }
        }
    }
}

?>
