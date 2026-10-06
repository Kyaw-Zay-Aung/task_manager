<?php
require 'db.php';
require 'functions.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        setFlash('success', 'Task deleted successfully!');
    } else {
        setFlash('error', 'Task not found.');
    }
}

header('Location: index.php');
exit;