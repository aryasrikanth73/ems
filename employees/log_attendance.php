<?php
session_start();
include('../includes/dbconn.php');

$empid = $_SESSION['eid'];
$date = date('Y-m-d');
$time = date('H:i:s'); // Current time in HH:MM:SS format
$status = 'present';

// Check if today is Sunday
if (date('N') == 7) { // 7 represents Sunday
    echo json_encode(['status' => 'error', 'message' => 'Attendance cannot be logged on Sundays']);
    exit;
}

// Check if the attendance for today is already logged
$sql = "SELECT * FROM attendance WHERE employee_id = :empid AND date = :date";
$query = $dbh->prepare($sql);
$query->bindParam(':empid', $empid, PDO::PARAM_INT);
$query->bindParam(':date', $date, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo json_encode(['status' => 'error', 'message' => 'Attendance already logged for today']);
} else {
    $sql = "INSERT INTO attendance (employee_id, date, time, status) VALUES (:empid, :date, :time, :status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':empid', $empid, PDO::PARAM_INT);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':time', $time, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->execute();
    echo json_encode(['status' => 'success', 'message' => 'Attendance logged successfully']);
}
?>
