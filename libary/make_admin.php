<?php
require 'config.php';

$email = "mostofa@gmail.com";
$username = "admin";
$password_plain = "admin123";

// hash create
$hash = password_hash($password_plain, PASSWORD_DEFAULT);

// old admin delete
$conn->query("DELETE FROM users WHERE email='$email' OR username='$username'");

// insert new admin
$stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
$stmt->bind_param("sss", $username, $email, $hash);
$stmt->execute();

echo "✅ Admin created: $email / $password_plain";
