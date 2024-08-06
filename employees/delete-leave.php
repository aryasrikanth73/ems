<?php
session_start();
include('includes/dbconn.php');

if (strlen($_SESSION['emplogin']) == 0) {
    header('location:../index.php');
} else {
    if (isset($_GET['id'])) {
        $leaveID = intval($_GET['id']);
        
        // Prepare the delete query
        $sql = "DELETE FROM tblleaves WHERE LeaveID = :leaveID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':leaveID', $leaveID, PDO::PARAM_INT);
        
        // Execute the query
        if ($query->execute()) {
            $_SESSION['msg'] = "Leave request deleted successfully!";
        } else {
            $_SESSION['error'] = "Error occurred while deleting the leave request.";
        }
    }
    
    // Redirect to leave history page
    header('location: leave-history.php');
}
?>
