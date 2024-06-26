<?php
session_start();
include('db.php');

$user_id = $_SESSION['user_id'];
$leave_type = $_POST['leave_type'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$comments = $_POST['comments'];

// Fetch user attendance to count used leaves
$query = "SELECT * FROM attendance WHERE user_id = $user_id AND status = 'leave'";
$result = mysqli_query($conn, $query);
$used_leaves = mysqli_num_rows($result);
$available_leaves = 5 - $used_leaves;

if ($available_leaves > 0) {
    $query = "INSERT INTO leave_applications (user_id, leave_type, start_date, end_date, comments) VALUES ($user_id, '$leave_type', '$start_date', '$end_date', '$comments')";
    mysqli_query($conn, $query);

    header("Location: user-attendance.php");
} else {
    $_SESSION['error'] = "You have exhausted your leave quota for the year.";
    header("Location: user-attendance.php");
}
?>
