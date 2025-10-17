<?php include 'database.php';

// Initialize variables
$title = $description = $due_date = "";
$error = "";

// CHECK IF THE FORM HAS BEEN SUBMITTED
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $due_date = $_POST["due_date"];

    if (empty($title)) {
        $error = "Task title is required!";
    } elseif (empty($description)) {
        $error = "Task description is required!";
    } elseif (empty($due_date)) {
        $error = "Due date is required!";
    } elseif ($due_date < date('Y-m-d')) {
        $error = "Due date cannot be in the past.";
    } else {
        // Insert into database
        $sql = "INSERT INTO tasks (title, description, due_date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $description, $due_date); // "s" = string type

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Error adding task: " . $stmt->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Task</title>
    <!-- Link CSS for styling -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="add-bg">
    <div class="form-container">
        <h2> Create New Task</h2>

        <!-- Show validation or error message if any -->
        <?php if ($error): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>

        <!-- ADD TASK FORM -->
        <form method="POST">
            <!-- Title -->
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="Enter task title"
                value="<?= htmlspecialchars($title); ?>" >

            <!-- Description -->
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Write details here"><?= htmlspecialchars($description); ?></textarea>

            <!-- Due date -->
            <label for="due_date">Due Date</label>
            <input type="date" id="due_date" name="due_date"
                value="<?= htmlspecialchars($due_date); ?>"
                min="<?= date('Y-m-d'); ?>">

            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn add">Save Task</button>
                <a href="index.php" class="btn back">Back</a>
            </div>
        </form>

    </div>
</body>

</html>