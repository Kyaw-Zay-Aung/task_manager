<?php
require 'db.php';
require 'functions.php';

$errors = [];

// GET: which task to edit? (edit.php?id=3)
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

// Load the existing task
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id");
$stmt->execute([':id' => $id]);
$task = $stmt->fetch();

if (!$task) {
    setFlash('error', 'Task not found.');
    header('Location: index.php');
    exit;
}

// Form values start with the database values
$title = $task['title'];
$description = $task['description'];
$category = $task['category'];
$priority = $task['priority'];
$dueDate = $task['due_date'];
$completed = $task['completed'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $priority = $_POST['priority'] ?? '';
    $dueDate = $_POST['due_date'] ?? '';
    $completed = isset($_POST['completed']) ? 1 : 0;

    $errors = validateTask($title, $category, $priority, $dueDate);

    if (empty($errors)) {
        $sql = "UPDATE tasks
                SET title = :title, description = :description, category = :category,
                    priority = :priority, due_date = :due_date, completed = :completed
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category' => $category,
            ':priority' => $priority,
            ':due_date' => $dueDate,
            ':completed' => $completed,
            ':id' => $id
        ]);

        setFlash('success', 'Task updated successfully!');
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Task</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?= (int)$id ?>">

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

        <label>
            <input type="checkbox" name="completed" value="1" <?= $completed ? 'checked' : '' ?>>
            Completed
        </label>

        <button type="submit" class="btn">Update Task</button>
        <a href="index.php" class="btn gray">Cancel</a>
    </form>
</div>
</body>
</html>