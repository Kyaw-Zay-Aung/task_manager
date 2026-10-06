<?php
require 'db.php';
require 'functions.php';

$errors = [];
$title = '';
$description = '';
$category = '';
$priority = 'Medium';
$dueDate = '';

// POST request = form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $priority = $_POST['priority'] ?? '';
    $dueDate = $_POST['due_date'] ?? '';

    $errors = validateTask($title, $category, $priority, $dueDate);

    // Save only if there are no errors
    if (empty($errors)) {
        $sql = "INSERT INTO tasks (title, description, category, priority, due_date)
                VALUES (:title, :description, :category, :priority, :due_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category' => $category,
            ':priority' => $priority,
            ':due_date' => $dueDate
        ]);

        setFlash('success', 'Task created successfully!');
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Add New Task</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="create.php">
        <label>Title</label>
        <input type="text" name="title" value="<?= e($title) ?>">

        <label>Description</label>
        <textarea name="description" rows="4"><?= e($description) ?></textarea>

        <label>Category</label>
        <input type="text" name="category" value="<?= e($category) ?>">

        <label>Priority</label>
        <select name="priority">
            <?php foreach (['Low', 'Medium', 'High'] as $p): ?>
                <option value="<?= $p ?>" <?= $priority === $p ? 'selected' : '' ?>><?= $p ?></option>
            <?php endforeach; ?>
        </select>

        <label>Due Date</label>
        <input type="date" name="due_date" value="<?= e($dueDate) ?>">

        <button type="submit" class="btn">Save Task</button>
        <a href="index.php" class="btn gray">Cancel</a>
    </form>
</div>
</body>
</html>