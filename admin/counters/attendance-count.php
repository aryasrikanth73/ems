<?php
// attendance-count.php
include('../includes/dbconn.php');
date_default_timezone_set('Asia/Kolkata'); // Set the timezone if needed
$today = date('Y-m-d'); // Get today's date

try {
    $sql = "SELECT COUNT(*) as present_count FROM attendance WHERE date = :today AND status = 'Present'";
    $query = $dbh->prepare($sql);
    $query->bindParam(':today', $today, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    echo $result['present_count'];
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
