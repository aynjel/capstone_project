<?php
session_start();
require_once('../config/config.php');

if (!isset($_SESSION['user_id']) && $_SESSION['user_role'] != 'Admin') {
   $_SESSION['error'] = 'You are not logged in as an Admin';
   header('location:../auth/signin.php');
   exit();
}

if(!isset($_GET['delete_id']) || $_GET['delete_id'] == NULL){
   header('location:enrollments.php');
}

if(isset($_POST['remove'])){
   $enrollment_id = $_POST['enrollment_id'];
   $sql = "DELETE FROM tbl_enrollment WHERE enrollment_id = :enrollment_id";
   $stmt = $conn->prepare($sql);
   $stmt->execute([
      ':enrollment_id' => $enrollment_id
   ]);
   $_SESSION['success'] = 'Enrollment removed successfully';
   header('location:enrollments.php');
}else{
   $enrollment_id = $_GET['delete_id'];
   $sql = "SELECT * FROM tbl_enrollment WHERE enrollment_id = :enrollment_id";
   $stmt = $conn->prepare($sql);
   $stmt->execute([
      ':enrollment_id' => $enrollment_id
   ]);
   $enrollment = $stmt->fetch(PDO::FETCH_ASSOC);
   if(!$enrollment){
      $_SESSION['error'] = 'Enrollment not found';
      header('location:enrollments.php');
   }
}