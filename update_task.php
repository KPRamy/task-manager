<?php include 'database.php';

// Get the task ID from the URL (e.g., update_task.php?id=3)
// If 'id' is not found, set it to null
$id = $_GET['id'] ?? null;

// Get the 'action' value from the URL (e.g., action=complete)
$action = $_GET['action'] ?? '';

// If an ID exists and the action is 'complete', then update the status
if ($id && $action === 'complete') {

    // Prepare SQL statement to mark the task as Completed
    $sql = "UPDATE tasks SET status='Completed' WHERE id=?";
    // Prepare the SQL query to prevent SQL injection
    $stmt = $conn->prepare($sql);
    // Bind the 'id' value to the query (i = integer)
    $stmt->bind_param("i", $id);
    // Execute the SQL query
    $stmt->execute();
}

// After updating, redirect user back to index.php (task list page)
header("Location: index.php");
exit;
