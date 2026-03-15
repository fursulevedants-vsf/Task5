<?php

/**
 * RBAC Auth Helper
 */
class Auth {
    private static $roles = [
        'admin'  => ['create', 'read', 'update', 'delete', 'manage_users'],
        'editor' => ['create', 'read', 'update', 'delete'],
        'viewer' => ['read']
    ];

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function getRole() {
        return $_SESSION['role'] ?? 'editor';
    }

    /**
     * Check if current user has a specific permission
     */
    public static function can($permission) {
        $currentRole = self::getRole();
        $permissions = self::$roles[$currentRole] ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Middleware-like check for permissions
     */
    public static function guard($permission = null) {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }

        if ($permission && !self::can($permission)) {
            http_response_code(403);
            include __DIR__ . '/../error_403.php';
            exit;
        }
    }

    public static function csrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
    }
}
