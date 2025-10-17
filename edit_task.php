<?php
// Include the database connection file
include 'database.php';


// STEP 1: GET TASK ID FROM URL
$id = $_GET['id'] ?? null; // Get the task ID passed through the URL (example: edit_task.php?id=3)

if (!$id) {
    // If no ID found, redirect to the main page
    header("Location: index.php");
    exit;
}


// STEP 2: FETCH TASK DATA BY ID
$sql = "SELECT * FROM tasks WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // "i" = integer type
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc(); // Fetch the single task data

// If task doesn’t exist, stop execution
if (!$task) {
    die("Task not found!");
}


// STEP 3: HANDLE FORM SUBMISSION (UPDATE)
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated data from the form
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $due_date = $_POST["due_date"];

    // Validate title
    if (empty($title)) {
        $error = "Task title is required!";
    } else {
        // Prepare update query
        $sql = "UPDATE tasks SET title=?, description=?, due_date=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $description, $due_date, $id);

        // Execute the update query
        $stmt->execute();

        // Redirect back to main task list after updating
        header("Location: index.php");
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

<body class="add-bg">
    <div class="form-container">
        <h2>Edit Task</h2>

        <!-- Show validation error message -->
        <?php if ($error): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>

    
        <!-- EDIT TASK FORM -->
        <form method="POST">
            <!-- Title field -->
            <label>Title</label>
            <input type="text" name="title"
                value="<?= htmlspecialchars($task['title']); ?>" required>

            <!-- Description field -->
            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($task['description']); ?></textarea>

            <!-- Due date field -->
            <label>Due Date</label>
            <input type="date" name="due_date"
                value="<?= htmlspecialchars($task['due_date']); ?>">

            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn edit">Update Task</button>
                <a href="index.php" class="btn back">Back</a>
            </div>
        </form>
    </div>
</body>

</html>