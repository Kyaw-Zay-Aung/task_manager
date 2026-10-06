<?php
require 'db.php';
require 'functions.php';

// GET request = filters from the URL
$search = trim($_GET['search'] ?? '');
$filterPriority = $_GET['priority'] ?? '';
$filterStatus = $_GET['status'] ?? '';

// Build the SQL step by step
$sql = "SELECT * FROM tasks WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND title LIKE :search";
    $params[':search'] = '%' . $search . '%';
}
if ($filterPriority !== '') {
    $sql .= " AND priority = :priority";
    $params[':priority'] = $filterPriority;
}
// Assigned challenge: filter incomplete tasks
if ($filterStatus === 'incomplete') {
    $sql .= " AND completed = 0";
}
$sql .= " ORDER BY due_date ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();

// Count completed tasks using a loop
$totalTasks = count($tasks);
$completedCount = 0;
foreach ($tasks as $task) {
    if ($task['completed'] == 1) {
        $completedCount++;
    }
}

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Student Task Manager</h1>

    <?php if ($flash): ?>
        <div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <p>Total: <strong><?= $totalTasks ?></strong> |
       Completed: <strong><?= $completedCount ?></strong></p>

    <a href="create.php" class="btn">+ Add Task</a>

    <form method="GET" action="index.php" class="filter">
        <input type="text" name="search" placeholder="Search title..." value="<?= e($search) ?>">

        <select name="priority">
            <option value="">All Priorities</option>
            <?php foreach (['Low', 'Medium', 'High'] as $p): ?>
                <option value="<?= $p ?>" <?= $filterPriority === $p ? 'selected' : '' ?>><?= $p ?></option>
            <?php endforeach; ?>
        </select>

        <select name="status">
            <option value="">All Tasks</option>
            <option value="incomplete" <?= $filterStatus === 'incomplete' ? 'selected' : '' ?>>Incomplete only</option>
        </select>

        <button type="submit" class="btn">Filter</button>
        <a href="index.php" class="btn gray">Reset</a>
    </form>

    <table>
        <tr>
            <th>ID</th><th>Title</th><th>Category</th><th>Priority</th>
            <th>Due Date</th><th>Status</th><th>Actions</th>
        </tr>
        <?php if ($totalTasks === 0): ?>
            <tr><td colspan="7">No tasks found.</td></tr>
        <?php endif; ?>

        <?php foreach ($tasks as $task): ?>
            <tr class="<?= isOverdue($task['due_date'], $task['completed']) ? 'overdue' : '' ?>">
                <td><?= e($task['id']) ?></td>
                <td>
                    <?= e($task['title']) ?><br>
                    <small><?= e($task['description']) ?></small>
                </td>
                <td><?= e($task['category']) ?></td>
                <td><?= e($task['priority']) ?></td>
                <td><?= e($task['due_date']) ?></td>
                <td><?= $task['completed'] ? 'Done' : 'Pending' ?></td>
                <td>
                    <a href="edit.php?id=<?= (int)$task['id'] ?>" class="btn small">Edit</a>
                    <form method="POST" action="delete.php" style="display:inline"
                          onsubmit="return confirm('Delete this task?');">
                        <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                        <button type="submit" class="btn small red">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>