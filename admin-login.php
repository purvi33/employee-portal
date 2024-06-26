<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            header("Location: admin-dashboard.php");
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No admin found with that username.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        nav {
            background-color: #333;
            width: 100%;
            padding: 10px 0;
            position: fixed;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            height: 40px;
            margin-left: 20px;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            margin-right: 20px;
        }
        nav ul li {
            margin: 0 10px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
        }
        nav ul li a:hover {
            background-color: #575757;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-top: 80px;
        }
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            width: 100%;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Admin Login</h2>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    
</body>
</html>
