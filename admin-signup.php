<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = uniqid('ADM');
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile_number'];
    $dob = $_POST['dob'];
    $pob = $_POST['pob'];
    $department = $_POST['department'];
    $role = $_POST['role'];

 
    $sql = "INSERT INTO admins (employee_id, username, password, email, mobile_number, dob, pob, department, role) 
            VALUES ('$employee_id', '$username', '$password', '$email', '$mobile_number', '$dob', '$pob', '$department', '$role')";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin-login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <div class="heading">
    <title>Admin Signup</title></div>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h2 {
            text-align: center;
            color: #333;
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            width: 250px;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"],

        button {
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            color: #4CAF50;
            margin-top: 10px;
            width: 100%;
        }
        form {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
    <h2>Admin Signup</h2>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        <label for="mobile_number">Mobile Number:</label>
        <input type="text" name="mobile_number" required><br>
        <label for="dob">Birthdate:</label>
        <input type="date" name="dob" required><br>
        <label for="pob">Place of Birth:</label>
        <input type="text" name="pob" required><br>
        <label for="department">Department:</label>
        <input type="text" name="department" required><br>
        <label for="role">Role:</label>
        <input type="text" name="role" required><br>
        <button type="submit">Signup</button>
    </form></div>
</body>
</html>
