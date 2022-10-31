<?php
require_once('../config/config.php');
session_start();

if (!isset($_SESSION['user_id']) && $_SESSION['user_role'] != 'Admin') {
   $_SESSION['error'] = 'You are not logged in as an Admin';
   header('location:../auth/signin.php');
   exit();
}

if(!isset($_GET['delete_id']) || $_GET['delete_id'] == NULL){
   header('location:courses.php');
}

if (isset($_GET['delete_id'])) {
   $sql = "DELETE FROM tbl_coordinator WHERE coordinator_id = :coordinator_id";
   $stmt = $conn->prepare($sql);
   $stmt->execute(['coordinator_id' => $_GET['delete_id']]);
   if ($stmt) {
      $_SESSION['success'] = 'Coordinator deleted successfully';
      header('location:coordinators.php');
   } else {
      $_SESSION['error'] = 'Something went wrong. Please try again.';
      header('location:coordinators.php');
   }
}