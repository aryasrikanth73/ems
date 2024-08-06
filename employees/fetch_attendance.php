<?php
session_start();
include('../includes/dbconn.php');

$empid = $_SESSION['eid'];

$sql = "SELECT date, status FROM attendance WHERE employee_id = :empid";
$query = $dbh->prepare($sql);
$query->bindParam(':empid', $empid, PDO::PARAM_INT);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);

$events = [];
foreach ($results as $row) {
    $events[] = [
        'title' => ucfirst($row['status']), // Capitalize the status
        'start' => $row['date'],
        'color' => $row['status'] == 'present' ? 'green' : ($row['status'] == 'absent' ? 'red' : 'blue')
    ];
}

echo json_encode($events);
?>