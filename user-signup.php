<?php
include 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = uniqid('EMP');
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile_number'];
    $dob = $_POST['dob'];
    $pob = $_POST['pob'];
    $department = $_POST['department'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (employee_id, username, password, email, mobile_number, dob, pob, department, role) 
            VALUES ('$employee_id', '$username', '$password', '$email', '$mobile_number', '$dob', '$pob', '$department', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "Admin registered successfully!";
        header("Location: user-login.php");
        exit(); // Ensure no further code is executed
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Signup</title>
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
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Signup</h2>
        <?php if (!empty($message)) : ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
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
        </form>
    </div>
</body>
</html>
