<?php
include('../includes/dbconn.php');

$period = $_GET['period'];
$date = date('Y-m-d');

if ($period == 'day') {
    $query = "SELECT * FROM leaves WHERE date = :date";
} elseif ($period == 'week') {
    $startDate = date('Y-m-d', strtotime('-1 week', strtotime($date)));
    $query = "SELECT * FROM leaves WHERE date BETWEEN :startDate AND :date";
} elseif ($period == 'month') {
    $startDate = date('Y-m-d', strtotime('-1 month', strtotime($date)));
    $query = "SELECT * FROM leaves WHERE date BETWEEN :startDate AND :date";
}

$stmt = $dbh->prepare($query);

if ($period == 'day') {
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
} else {
    $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
}

$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>
