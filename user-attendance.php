<?php
session_start();
include('db.php');

$user_id = $_SESSION['user_id']; // Assume the user ID is stored in session

// Define the total required working days per month
$total_required_days = 24; // Assuming 24 working days per month

// Fetch user attendance record for the month
$month = date('m');
$year = date('Y');
$query = "SELECT * FROM attendance WHERE user_id = $user_id AND MONTH(date) = $month AND YEAR(date) = $year";
$result = mysqli_query($conn, $query);
$attendance = [];
while ($row = mysqli_fetch_assoc($result)) {
    $attendance[] = $row;
}

// Fetch leave applications
$leave_query = "SELECT * FROM leave_applications WHERE user_id = $user_id";
$leave_result = mysqli_query($conn, $leave_query);
$leaves = []; // Initialize $leaves as an empty array
if ($leave_result) {
    while ($row = mysqli_fetch_assoc($leave_result)) {
        $leaves[] = $row;
    }
}

// Calculate used and available leaves
$used_leaves = count(array_filter($leaves, function($leave) { return $leave['status'] == 'approved'; }));
$pending_leaves = count(array_filter($leaves, function($leave) { return $leave['status'] == 'pending'; }));
$denied_leaves = count(array_filter($leaves, function($leave) { return $leave['status'] == 'denied'; }));
$available_leaves = 20 - $used_leaves; // Assuming 20 leaves available annually

// Calculate days attended
$days_attended = $total_required_days - $used_leaves; // Total required days minus used leaves

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Attendance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        .div2{

            margin-left: 200px;
            margin-right: 200px;
        }
        h1, h2 {
            color: black;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }
        .records {
            background-color: white;
            padding: 0px;
            margin-bottom: 0px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .attendance-summary, .leave-application,  {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .leave-applications{
            background-color: white;
            padding: 0px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 0px;
        }
        .attendance-summary p, .leave-application p {
            font-size: 16px;
            margin: 10px 0;
        }
        .leave-application form {
            display: flex;
            flex-direction: column;
        }
        .leave-application label {
            font-weight: bold;
            margin-top: 10px;
        }
        .leave-application input[type="date"],
        .leave-application select,
        .leave-application textarea {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .leave-application input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .leave-application input[type="submit"]:hover {
            background-color: blueviolet;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Styling for status background colors */
        td.approved .fa-circle {
            color: green;
        }
        td.pending .fa-circle {
            color: yellow ;
        }
        td.denied .fa-circle{
            color: orangered ;
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
            <a href="user-dashboard.php">Dashboard</a>
        </div>
        <div>
            <a href="user-logout.php" class="logout">Logout</a>
        </div>
    </div>
    <?php if (isset($_SESSION['error'])) { ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php } ?>
    <div class="div2">

    <h1>Attendance Record for <?php echo date('F Y'); ?></h1>
    <!-- <div class="attendance-summary">
        <p>Required Working Days: <?php echo $total_required_days; ?></p>
        <p>Used Leaves (Approved): <?php echo $used_leaves; ?></p>
        <p>Pending Leaves: <?php echo $pending_leaves; ?></p>
        <p>Denied Leaves: <?php echo $denied_leaves; ?></p>
        <p>Available Leaves: <?php echo $available_leaves; ?></p>
        <p>Days Attended: <?php echo $days_attended; ?></p>
    </div> -->
    <div class="records">
    <table>
        <tr>
            <th>Required Working Days</th>
            <th>Used Leaves (Approved)</th>
            <th>Pending Leaves</th>
            <th>Denied Leaves</th>
            <th>Available Leaves</th>
            <th>Days Attended</th>
           
        </tr>
      
        <tr>
            
            <td><?php echo $total_required_days; ?></td>
            <td><?php echo $used_leaves; ?></td>
            <td><?php echo $pending_leaves; ?></td>
            <td><?php echo $denied_leaves; ?></td>
            <td><?php echo $available_leaves; ?></td>
            <td><?php echo $days_attended; ?></td>
        </tr>
    </table>
</div>



    <h2>Leave Application</h2>
    <div class="leave-application">
        <?php if ($available_leaves > 0) { ?>
            <form action="apply_leave.php" method="post">
                <label for="leave_type">Leave Type:</label>
                <select name="leave_type" id="leave_type">
                    <option value="sick">Sick Leave</option>
                    <option value="casual">Casual Leave</option>
                    <option value="earned">Earned Leave</option>
                </select>
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" required>
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" required>
                <label for="comments">Comments:</label>
                <textarea name="comments" id="comments"></textarea>
                <input type="submit" value="Apply">
            </form>
        <?php } else { ?>
            <p>You have exhausted your leave quota for the year.</p>
        <?php } ?>
    </div>

    <h2>Leave Applications</h2>
    <div class="leave-applications">
        <table>
            <tr>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
            <?php foreach ($leaves as $leave) { ?>
            <tr>
                <td><?php echo $leave['leave_type']; ?></td>
                <td><?php echo $leave['start_date']; ?></td>
                <td><?php echo $leave['end_date']; ?></td>
                <td class="<?php echo $leave['status']; ?>"><i class="fa-solid fa-circle "></i>&nbsp; <?php echo ucfirst($leave['status']); ?> </td>
            </tr>
            <?php } ?>
        </table>
    </div>
    </div>
</body>
</html>
