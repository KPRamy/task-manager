<?php include 'database.php';

// HANDLE SEARCH & FILTER INPUTS:
// Get the search keyword from URL (if any)
$search = trim($_GET['search'] ?? '');

// Get the selected filter (In progress / Completed / All)
$filter = $_GET['filter'] ?? 'all';

// BUILD SQL QUERY DYNAMICALLY
$sql = "SELECT * FROM tasks WHERE 1=1"; // Start with a base query

// Add filter condition based on selected option
if ($filter === 'In progress') {
    $sql .= " AND status='In progress'";
} elseif ($filter === 'Completed') {
    $sql .= " AND status='Completed'";
}

// Add search condition if user entered a keyword
if ($search !== '') {
    // Escape special characters to prevent SQL injection
    $search = $conn->real_escape_string($search);

    // Match keyword in title or description fields
    $sql .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
}

// Sort results by due date (ascending)
$sql .= " ORDER BY due_date ASC";

// Execute the final query
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>📝 Task Manager</h2>

        <!-- TOP BAR: ADD + SEARCH + FILTER -->
        <div class="top-bar">
            <!-- Add New Task button -->
            <a href="add_task.php" class="btn add">➕ Add New Task</a>

            <!-- Search & Filter Form -->
            <form method="GET" class="search-filter">
                <!-- Search input -->
                <input type="text" name="search" placeholder="Search tasks"
                    value="<?= htmlspecialchars($search); ?>">

                <!-- Filter dropdown -->
                <select name="filter">
                    <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="In progress" <?= $filter === 'In progress' ? 'selected' : '' ?>>In progress</option>
                    <option value="Completed" <?= $filter === 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>

                <!-- Search button -->
                <button type="submit" class="btn search">Search</button>
            </form>
        </div>


        <!-- TASK TABLE SECTION -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <!-- Add class 'completed' if the task is completed -->
                            <tr class="<?= $row['status'] == 'Completed' ? 'completed' : '' ?>">
                                <td><?= htmlspecialchars($row['title']); ?></td>
                                <td><?= htmlspecialchars($row['description']); ?></td>
                                <td><?= htmlspecialchars($row['due_date']); ?></td>

                                <!-- Display task status with color -->
                                <td>
                                    <!-- Make sure 'In progress' becomes 'inprogress' for CSS -->
                                    <span class="status <?= strtolower(str_replace(' ', '', $row['status'])); ?>">
                                        <?= htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>

                                <!-- ACTION BUTTONS -->
                                <td class="action-buttons">
                                    <!-- Show complete button only if task is In progress -->
                                    <?php if ($row['status'] == 'In progress'): ?>
                                        <a href="update_task.php?id=<?= $row['id']; ?>&action=complete" class="btn complete">✅</a>
                                    <?php endif; ?>

                                    <!-- Show edit button only if task is In progress -->
                                    <?php if ($row['status'] != 'Completed'): ?>
                                        <a href="edit_task.php?id=<?= $row['id']; ?>" class="btn edit">✏️</a>
                                    <?php endif; ?>


                                    <!-- Delete task button with confirmation -->
                                    <a href="delete_task.php?id=<?= $row['id']; ?>"
                                        class="btn delete"
                                        onclick="return confirm('Delete this task?');">🗑️</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- Message when no tasks are found -->
                        <tr>
                            <td colspan="5" class="no-data">No tasks found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div><!-- TASK TABLE SECTION END -->

    </div>
</body>

</html>