<?php
/**
 * Run this file ONCE in the browser to create the admin user.
 * URL: http://localhost/Task4/Apex_internship_task3/setup_admin.php
 * DELETE this file after running it.
 */
include "config.php";

$username = 'admin123';
$password = '123456';
$role = 'admin';

// Check if user already exists
$check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
$check->execute(['username' => $username]);

if ($check->fetchColumn() > 0) {
    echo "User '$username' already exists. Updating role to admin...<br>";
    $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE username = :username");
    $stmt->execute(['role' => $role, 'username' => $username]);
    echo "Done! Role updated to admin.";
} else {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->execute([
        'username' => $username,
        'password' => $hashed,
        'role'     => $role
    ]);
    echo "Admin user '$username' created successfully!<br>";
    echo "Username: $username<br>";
    echo "Password: $password<br>";
    echo "<strong>DELETE this file now for security!</strong>";
}
?>
