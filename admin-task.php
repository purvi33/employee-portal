<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php"); // Redirect if not logged in as admin
    exit();
}

include 'db.php';

$admin_id = $_SESSION['admin_id'];

// Fetch admin details
$sql_admin = "SELECT * FROM admins WHERE id = '$admin_id'";
$result_admin = $conn->query($sql_admin);
$admin = $result_admin->fetch_assoc();

// Fetch all tasks with user details
$sql_tasks = "SELECT tasks.*, users.username AS user_username FROM tasks 
             INNER JOIN users ON tasks.user_id = users.id";
$result_tasks = $conn->query($sql_tasks);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Task Management</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .task {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9; /* Default background color */
        }
        .task.priority-important {
            background-color: #e6f7ff; /* Light blue for important tasks */
        }
        .task.priority-urgent {
            background-color: #ffe8cc; /* Light orange for urgent tasks */
        }
        .task.priority-low {
            background-color: #e8f5e9; /* Light green for low priority tasks */
        }
        .task h3 {
            margin-top: 0;
        }
        .task p {
            margin-bottom: 5px;
        }
        .comment-form {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .comment-form textarea {
            width: 98%;
            height: 80px;
            resize: vertical;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
        }
        .comment-form button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .comment-form button:hover {
            background-color: #45a049;
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
    </style>
</head>
<body>
<div class="navbar">
    <div class="logo">
        <img src="dgtra-logo.png" alt="Company Logo">
        <a href="admin-dashboard.php">Dashboard</a>
    </div>
    <div>
        <a href="admin-logout.php" class="logout">Logout</a>
    </div>
</div>
<div class="container">
    <h1>Admin Task Management</h1>
    <h2>Welcome, <?php echo htmlspecialchars($admin['username']); ?>!</h2>

    <?php while ($row = $result_tasks->fetch_assoc()) : ?>
        <div class="task <?php echo 'priority-' . strtolower($row['priority']); ?>">
            <h3><?php echo htmlspecialchars($row['task_name']); ?></h3>
            <p><strong>Project:</strong> <?php echo htmlspecialchars($row['project_name']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($row['task_description']); ?></p>
            <p><strong>Assigned By:</strong> <?php echo htmlspecialchars($row['task_assigned_by']); ?></p>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($row['start_date']); ?></p>
            <p><strong>End Date:</strong> <?php echo htmlspecialchars($row['end_date']); ?></p>
            <p><strong>Priority:</strong> <?php echo htmlspecialchars($row['priority']); ?></p>
            <p><strong>User:</strong> <?php echo htmlspecialchars($row['user_username']); ?></p>

            <?php if (!empty($row['admin_comments'])) : ?>
                <p><strong>Admin Comments:</strong> <?php echo htmlspecialchars($row['admin_comments']); ?></p>
            <?php else : ?>
                <p><em>No comments yet.</em></p>
            <?php endif; ?>

            <form class="comment-form" method="post">
                <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                <textarea name="admin_comment" placeholder="Add your comment"></textarea><br>
                <button type="submit">Add Comment</button>
            </form>
        </div>
    <?php endwhile; ?>

</div>
</body>
</html>

<?php
// Process form submission to add admin comments
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['task_id'];
    $admin_comment = mysqli_real_escape_string($conn, $_POST['admin_comment']);

    $sql_update = "UPDATE tasks SET admin_comments = '$admin_comment', admin_id = '$admin_id' WHERE id = '$task_id'";
    if ($conn->query($sql_update) === TRUE) {
        // Redirect to refresh the page after adding comment
        header("Location: admin-task.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
