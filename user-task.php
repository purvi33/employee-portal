<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$message = '';

// Handle task creation/update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        // Handle task edit
        $task_id = $_POST['task_id'];
        $project_name = $_POST['project_name'];
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];
        $task_assigned_by = $_POST['task_assigned_by'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $priority = $_POST['priority'];

        $sql_update = "UPDATE tasks SET 
                       project_name = '$project_name',
                       task_name = '$task_name',
                       task_description = '$task_description',
                       task_assigned_by = '$task_assigned_by',
                       start_date = '$start_date',
                       end_date = '$end_date',
                       priority = '$priority'
                       WHERE id = '$task_id' AND user_id = '$user_id'";

        if ($conn->query($sql_update) === TRUE) {
            $message = "Task updated successfully!";
        } else {
            $message = "Error updating task: " . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'create') {
        // Handle task creation
        $project_name = $_POST['project_name'];
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];
        $task_assigned_by = $_POST['task_assigned_by'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $priority = $_POST['priority'];
        $admin_comments = '';

        $sql_insert = "INSERT INTO tasks (user_id, project_name, task_name, task_description, task_assigned_by, start_date, end_date, priority, admin_comments) 
                       VALUES ('$user_id', '$project_name', '$task_name', '$task_description', '$task_assigned_by', '$start_date', '$end_date', '$priority', '$admin_comments')";

        if ($conn->query($sql_insert) === TRUE) {
            $message = "Task created successfully!";
        } else {
            $message = "Error creating task: " . $conn->error;
        }
    }
}

// Fetch user's tasks
$sql = "SELECT * FROM tasks WHERE user_id='$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Task Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        .navbar {
            width: 100%;
            background-color: #333;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar .logo {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }
        .navbar .logo img {
            height: 40px;
            margin-right: 10px;
        }
        .navbar a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            display: inline-block;
        }
        .navbar a:hover {
            background-color: #575757;
        }
        .navbar .logout {
            background-color: #f44336;
            border-radius: 5px;
        }
        .navbar .logout:hover {
            background-color: #e53935;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
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
            background-color: blue;
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
            background-color: blueviolet;
        }
        .message {
            text-align: center;
            color: #4CAF50;
            margin-top: 10px;
        }
        .task-list {
            text-align: left;
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .task {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            position: relative;
            width: calc(50% - 10px); /* Adjust width to fit two tasks per line */
        }
        .task h3 {
            margin: 0 0 10px;
        }
        .task p {
            margin: 5px 0;
            color: #555;
        }
        .task.important {
            background-color: #d0e9f6; /* Light blue */
        }
        .task.urgent {
            background-color: #fde8d7; /* Light orange */
        }
        .task.low-priority {
            background-color: #d9fdd3; /* Light green */
        }
        .task .edit-task {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: green;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 12px;
            cursor: pointer;
            border-radius: 3px;
        }
        .task .edit-task:hover {
            background-color: greenyellow;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="dgtra-logo.png" alt="Company Logo">
            <a href="user-dashboard.php">Dashboard</a>
        </div>
        <div>
            <a href="user-logout.php" class="logout">Logout</a>
        </div>
    </div>
    <div class="container">
        <h1>Manage Your Tasks</h1>
        <?php if (!empty($message)) : ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="action" value="create">
            <label for="project_name">Project Name:</label>
            <input type="text" name="project_name" required>
            
            <label for="task_name">Task Name:</label>
            <input type="text" name="task_name" required>
            
            <label for="task_description">Task Description:</label>
            <textarea name="task_description" rows="4"></textarea>
            
            <label for="task_assigned_by">Task Assigned By:</label>
            <input type="text" name="task_assigned_by" required>
            
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>
            
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>
            
            <label for="priority">Priority:</label>
            <select name="priority" required>
                <option value="important">Important</option>
                <option value="urgent">Urgent</option>
                <option value="low priority">Low Priority</option>
            </select>
            
            <button type="submit">Create Task</button>
        </form>
        <h2>Your Tasks</h2><br>
        <div class="task-list">
            
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="task <?php echo strtolower(str_replace(' ', '-', $row['priority'])); ?>">
                    <h3><?php echo htmlspecialchars($row['task_name']); ?></h3>
                    <p><strong>Project:</strong> <?php echo htmlspecialchars($row['project_name']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($row['task_description']); ?></p>
                    <p><strong>Assigned By:</strong> <?php echo htmlspecialchars($row['task_assigned_by']); ?></p>
                    <p><strong>Start Date:</strong> <?php echo htmlspecialchars($row['start_date']); ?></p>
                    <p><strong>End Date:</strong> <?php echo htmlspecialchars($row['end_date']); ?></p>
                    <p><strong>Priority:</strong> <?php echo htmlspecialchars($row['priority']); ?></p>
                    <p><strong>Admin Comments:</strong> <?php echo htmlspecialchars($row['admin_comments']); ?></p>
                    <form method="post">
                        <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="edit">
                        <button type="submit" class="edit-task">Edit</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
