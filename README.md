# Student Task Manager (PHP CRUD)

**Student Name:** Kyaw Zay Aung
**Student ID:** 202400184

## Setup Instructions
1. Install Laragon and start Apache and MySQL.
2. Copy the `task_manager` folder into `laragon/www`.
3. Open HeidiSQL (or phpMyAdmin) and import `database.sql`.
4. Open `db.php` and check the database username and password.
5. Open http://localhost/task_manager/index.php in the browser.

## Features
- Create, Read, Update, Delete tasks using PDO and prepared statements
- Server-side validation (title, category, priority, due date)
- Success/error flash messages using PHP sessions
- Safe output using htmlspecialchars
- Search by title and filter by priority (GET request)
- Overdue tasks are highlighted

## Assigned Challenge
**Filtering incomplete tasks.**
In `index.php`, the "Incomplete only" dropdown sends `status=incomplete` using GET.
The code adds `AND completed = 0` to the SQL query, so only pending tasks are shown.

## Files
- `index.php` - list tasks, search, filters (READ)
- `create.php` - add a task (CREATE)
- `edit.php` - update a task (UPDATE)
- `delete.php` - delete a task (DELETE)
- `db.php` - PDO database connection
- `functions.php` - reusable functions
- `style.css` - styling
- `database.sql` - database export

## AI-Use Reflection
**Student Name:** Kyaw Zay Aung
**Student ID:** 202400184

**AI tool(s) used:** Claude

**Three examples of how AI helped me:**
1. Helped me create the database table and SQL structure.
2. Explained how PDO prepared statements prevent SQL injection.
3. Helped me fix my database connection problem in Laragon.

**One AI-generated suggestion I changed or rejected:**
- What was it? The AI suggested using an empty password in db.php, and it also suggested the database name task_manager.
- Why did I change/reject it? My Laragon MySQL needed my own password, so the empty password gave an "Access denied" error and I changed it. I also tried the name task_db first, but I went back to task_manager so it matches the code in db.php.

**The part of this application I understand least:**
I understand least why WHERE 1=1 is used in the SQL query in index.php. I know it makes it easy to add AND conditions for search and filters, but I need more practice explaining it.