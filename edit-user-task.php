<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$message = '';

// Check if form is submitted for task update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['task_id'];
    $project_name = $_POST['project_name'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $task_assigned_by = $_POST['task_assigned_by'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $priority = $_POST['priority'];

    // Update task in the database
    $sql = "UPDATE tasks SET 
            project_name = '$project_name',
            task_name = '$task_name',
            task_description = '$task_description',
            task_assigned_by = '$task_assigned_by',
            start_date = '$start_date',
            end_date = '$end_date',
            priority = '$priority'
            WHERE id = '$task_id' AND user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Task updated successfully!";
    } else {
        $message = "Error updating task: " . $conn->error;
    }
}

// Fetch the task details to populate the form
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $sql = "SELECT * FROM tasks WHERE id = '$task_id' AND user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        echo "Task not found.";
        exit();
    }
} else {
    echo "Task ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            text-align: center;
            margin-top: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        form {
            text-align: left;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            color: #4CAF50;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Task</h1>
        <?php if (!empty($message)) : ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
            
            <label for="project_name">Project Name:</label>
            <input type="text" name="project_name" value="<?php echo htmlspecialchars($task['project_name']); ?>" required>
            
            <label for="task_name">Task Name:</label>
            <input type="text" name="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" required>
            
            <label for="task_description">Task Description:</label>
            <textarea name="task_description" rows="4"><?php echo htmlspecialchars($task['task_description']); ?></textarea>
            
            <label for="task_assigned_by">Task Assigned By:</label>
            <input type="text" name="task_assigned_by" value="<?php echo htmlspecialchars($task['task_assigned_by']); ?>" required>
            
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" value="<?php echo $task['start_date']; ?>" required>
            
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" value="<?php echo $task['end_date']; ?>" required>
            
            <label for="priority">Priority:</label>
            <select name="priority" required>
                <option value="important" <?php if ($task['priority'] === 'important') echo 'selected'; ?>>Important</option>
                <option value="urgent" <?php if ($task['priority'] === 'urgent') echo 'selected'; ?>>Urgent</option>
                <option value="low priority" <?php if ($task['priority'] === 'low priority') echo 'selected'; ?>>Low Priority</option>
            </select>
            
            <button type="submit">Update Task</button>
        </form>
    </div>
</body>
</html>
