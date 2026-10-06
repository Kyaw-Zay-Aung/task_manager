<?php
// Function 1: safe output (prevents XSS)
function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Function 2: save a flash message in session
function setFlash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

// Function 3: get the flash message once, then remove it
function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Function 4: server-side validation, returns an array of errors
function validateTask($title, $category, $priority, $dueDate)
{
    $errors = [];
    $allowedPriorities = ['Low', 'Medium', 'High'];

    if ($title === '') {
        $errors[] = 'Title is required.';
    } elseif (strlen($title) > 150) {
        $errors[] = 'Title must be 150 characters or less.';
    }

    if ($category === '') {
        $errors[] = 'Category is required.';
    } elseif (strlen($category) > 50) {
        $errors[] = 'Category must be 50 characters or less.';
    }

    if (!in_array($priority, $allowedPriorities)) {
        $errors[] = 'Priority must be Low, Medium or High.';
    }

    if ($dueDate === '') {
        $errors[] = 'Due date is required.';
    } else {
        $d = DateTime::createFromFormat('Y-m-d', $dueDate);
        if (!$d || $d->format('Y-m-d') !== $dueDate) {
            $errors[] = 'Due date is not a valid date.';
        }
    }

    return $errors;
}

// Function 5: check if a task is overdue
function isOverdue($dueDate, $completed)
{
    return $completed == 0 && $dueDate < date('Y-m-d');
}