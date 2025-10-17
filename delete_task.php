<?php include 'database.php';

// Step 2: Get the 'id' from the URL (like delete_task.php?id=3)
$id = $_GET['id'] ?? null;

// Step 3: Check if the ID is actually given
if ($id) {
    // Step 4: Write the SQL query to delete a specific task
    $sql = "DELETE FROM tasks WHERE id=?";
    // Step 5: Prepare the query — this creates a statement object ($stmt)
    $stmt = $conn->prepare($sql);
    // Step 6: Bind the 'id' variable into the SQL query
    // "i" means integer type (since id is a number)
    $stmt->bind_param("i", $id);
    // Step 7: Run (execute) the query to delete the task
    $stmt->execute();
}

// Step 8: After deletion, go back to the main page (index.php)
header("Location: index.php");
exit;
