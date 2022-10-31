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
   $sql = "DELETE FROM tbl_course WHERE course_id = :course_id";
   $stmt = $conn->prepare($sql);
   $stmt->execute(['course_id' => $_GET['delete_id']]);
   if ($stmt) {
      $_SESSION['success'] = 'Course deleted successfully';
      header('location:courses.php');
   } else {
      $_SESSION['error'] = 'Something went wrong. Please try again.';
      header('location:courses.php');
   }
}