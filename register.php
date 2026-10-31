<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['fname']);
    $lastName = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email, $password]);
        
        header("Location: index.php?action=login&registered=1");
        exit();
    } catch (PDOException $e) {
        if ($e->errorInfo[1] === 1062) {
            header("Location: index.php?action=register&error=email_exists");
            exit();
        } else {
            die("Registration failed: " . $e->getMessage());
        }
    }
}
?>