<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
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
            max-width: 600px;
            text-align: center;
            margin-top: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .welcome {
            margin-bottom: 20px;
        }
        p {
            color: #666;
            line-height: 1.6;
        }
        .info {
            margin: 10px 0;
        }
        a.button {
            display: inline-block;
            margin: 10px 5px;
            padding: 10px 20px;
            background-color: blue;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a.button:hover {
            background-color: #1e88e5;
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
        <h1>User Dashboard</h1>
        <div class="welcome">
            <p>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</p>
        </div>
        <div class="info">
            <p>Employee ID: <?php echo htmlspecialchars($user['employee_id']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Mobile Number: <?php echo htmlspecialchars($user['mobile_number']); ?></p>
            <p>Department: <?php echo htmlspecialchars($user['department']); ?></p>
            <p>Role: <?php echo htmlspecialchars($user['role']); ?></p>
        </div>
        <a href="user-task.php" class="button">Manage Your Tasks</a>
        <a href="user-attendance.php" class="button">Attendance</a>

    </div>
</body>
</html>
