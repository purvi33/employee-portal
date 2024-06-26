<?php
session_start();
include('db.php');

// Fetch all leave applications
$query = "SELECT leave_applications.*, users.username FROM leave_applications JOIN users ON leave_applications.user_id = users.id";
$result = mysqli_query($conn, $query);
$leave_applications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $leave_applications[] = $row;
}

// Update leave status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['leave_id'])) {
    $leave_id = $_POST['leave_id'];
    $status = $_POST['status'];
    $update_query = "UPDATE leave_applications SET status = '$status' WHERE id = $leave_id";
    mysqli_query($conn, $update_query);
    header("Location: admin-attendance.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .div2{
            margin-right: 200px;
            margin-left: 200px;

        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-pending {
            background-color: lightcyan ;
        }
        .status-approved {
            background-color: lightgreen;
        }
        .status-denied {
            background-color: lightsalmon; /* light orange */
        }
        select {
            padding: 4px;
            margin-right: 8px;
        }
        input[type="submit"] {
            padding: 4px 8px;
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
    <div class="navbar">
    <div class="logo">
        <img src="dgtra-logo.png" alt="Company Logo">
        <a href="admin-dashboard.php">Dashboard</a>
    </div>
    <div>
        <a href="admin-logout.php" class="logout">Logout</a>
    </div>
</div>
</head>
<body>
<div class="div2">
    <h1>Leave Applications</h1>
    <table>
        <tr>
            <th>Employee</th>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($leave_applications as $application) { ?>
        <tr>
            <td><?php echo $application['username']; ?></td>
            <td><?php echo $application['leave_type']; ?></td>
            <td><?php echo $application['start_date']; ?></td>
            <td><?php echo $application['end_date']; ?></td>
            <td class="status-<?php echo strtolower($application['status']); ?>">
                <?php echo $application['status']; ?>
            </td>
            <td>
                <form action="admin-attendance.php" method="post">
                    <input type="hidden" name="leave_id" value="<?php echo $application['id']; ?>">
                    <select name="status">
                        <option value="pending" <?php if ($application['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                        <option value="approved" <?php if ($application['status'] == 'approved') echo 'selected'; ?>>Approved</option>
                        <option value="denied" <?php if ($application['status'] == 'denied') echo 'selected'; ?>>Denied</option>
                    </select>
                    <input type="submit" value="Update">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    </div>
</body>
</html>
