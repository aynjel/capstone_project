<?php 
session_start();
$app_title =  "Enrollment Details";
require_once('./includes/header.php'); 
require_once('../config/config.php');

// If the user is not logged in, redirect to the login page
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'Admin') {
   $user_id = $_SESSION['user_id'];
   $user_role = $_SESSION['user_role'];
   $user_status = $_SESSION['user_status'];
   $email = $_SESSION['email'];
   $nickname = $_SESSION['nickname'];
}else{
   $_SESSION['error'] = 'You are not logged in as an Admin';
   header('refresh:2;url=../auth/signin.php');
   exit();
}

// redirect to enrollment list if no id is set
if(!isset($_GET['view_id']) || $_GET['view_id'] == NULL){
   header('location:enrollments.php');
}

$enrollment_id = $_GET['view_id'];
$sql = "SELECT * FROM tbl_enrollment WHERE enrollment_id = :enrollment_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
   ':enrollment_id' => $enrollment_id
]);
$enrollment = $stmt->fetch(PDO::FETCH_ASSOC);

// redirect to enrollment list if enrollment is not found
if(!$enrollment){
   $_SESSION['error'] = 'Enrollment not found';
   header('location:enrollments.php');
}

//get student details
$sql = "SELECT * FROM tbl_student WHERE student_id = :student_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
   ':student_id' => $enrollment['student_id']
]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

//get coordinator details
$sql = "SELECT * FROM tbl_coordinator WHERE coordinator_id = :coordinator_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
   ':coordinator_id' => $enrollment['coordinator_id']
]);
$coordinator = $stmt->fetch(PDO::FETCH_ASSOC);

//get organization details
$sql = "SELECT * FROM tbl_organization WHERE organization_id = :organization_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
   ':organization_id' => $coordinator['organization_id']
]);
$organization = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<body class="nav-fixed">

   <?php require_once('./includes/nav.php'); ?>

   <!--Side Nav-->
   <div id="layoutSidenav">
      <?php $curr_page = basename(__FILE__); ?>
      <?php require_once './includes/sidebar.php'; ?>

      <div id="layoutSidenav_content">
         <div class="container-fluid">
            <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" role="alert">
               <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
               <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>
         </div>
         <main>
            <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
               <div class="container-fluid">
                  <div class="page-header-content text-white">
                     <h1 class="page-header-title d-inline-block">
                        <div class="page-header-icon">
                           <i class="fas fa-building"></i>
                        </div>
                        <span>Enrollment Details</span>
                     </h1>
                     <div class="float-right">
                        <a href="enrollment_edit.php?edit_id=<?php echo $enrollment['enrollment_id']; ?>"
                           title="Edit Organization" class="btn btn-white d-inline-block">
                           <div class="page-header-icon">
                              <i class="fas fa-pen-to-square"></i>
                              Update
                           </div>
                        </a>
                        <form method="POST" class="d-inline-block"
                           action="enrollment_delete.php?delete_id=<?php echo $enrollment['enrollment_id']; ?>">
                           <input type="hidden" name="enrollment_id"
                              value="<?php echo $enrollment['enrollment_id']; ?>">
                           <button type="submit" name="remove" class="btn btn-danger"
                              onclick="return confirm('Are you sure you want to remove this enrollment?');">
                              <div class="page-header-icon">
                                 <i class="fas fa-trash-can"></i>
                                 Remove
                              </div>
                           </button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <div class="container-fluid mt-n10">
               <div class="card mb-4">
                  <div class="card-header">Enrollment Details</div>
                  <div class="card-body">
                     <?php
                        if($enrollment['status'] == 'Dropped'){
                           echo '<div class="alert alert-danger" role="alert">
                              <h4 class="alert-heading">Dropped!</h4>
                              <p>This student has dropped out of the program.</p>
                              <hr>
                              <p class="mb-0">Please contact the coordinator for more information.</p>
                           </div>';
                        }

                        if($enrollment['status'] == 'Resigned'){
                           echo '<div class="alert alert-danger" role="alert">
                              <h4 class="alert-heading">Resigned!</h4>
                              <p>This student has resigned from the program.</p>
                              <hr>
                              <p class="mb-0">Please contact the coordinator for more information.</p>
                           </div>';
                        }

                        if($enrollment['status'] == 'Graduated'){
                           echo '<div class="alert alert-success" role="alert">
                              <h4 class="alert-heading">Graduated!</h4>
                              <p>This student has graduated from the program.</p>
                              <hr>
                              <p class="mb-0">Please contact the coordinator for more information.</p>
                           </div>';
                        }
                     ?>
                     <div class="form-group">
                        <label for="enrollment_id">Enrollment ID</label>
                        <input type="text" class="form-control" id="enrollment_id" name="enrollment_id"
                           value="<?php echo $enrollment['enrollment_id']; ?>" readonly>
                     </div>
                     <div class="form-group">
                        <label for="student_id">Student</label>
                        <input type="text" class="form-control" id="student_id" name="student_id"
                           value="<?php echo $student['first_name'] . ' ' . $student['last_name']; ?>" readonly>
                     </div>
                     <div class="form-group">
                        <label for="coordinator_id">Coordinator</label>
                        <input type="text" class="form-control" id="coordinator_id" name="coordinator_id"
                           value="<?php echo $coordinator['first_name'] . ' ' . $coordinator['last_name']; ?>" readonly>
                     </div>
                     <div class="form-group">
                        <label for="organization_id">Organization</label>
                        <input type="text" class="form-control" id="organization_id" name="organization_id"
                           value="<?php echo $organization['organization_name']; ?>" readonly>
                     </div>
                     <div class="form-group">
                        <label for="school_year">School Year</label>
                        <input type="text" class="form-control" id="school_year" name="school_year"
                           value="<?php echo $enrollment['school_year']; ?>" readonly>
                     </div>
                     <div class="form-group">
                        <label for="date_enrolled">Date Enrolled</label>
                        <input type="text" class="form-control" id="date_enrolled" name="date_enrolled"
                           value="<?php echo $enrollment['date_enrolled']; ?>" readonly>
                     </div>
                     <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" class="form-control" id="status" name="status"
                           value="<?php echo $enrollment['status']; ?>" readonly>
                     </div>
                  </div>
               </div>
            </div>
         </main>
         <!--start footer-->
         <footer class="footer mt-auto footer-light">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-md-6 small">
                     Copyright &#xA9; Group 9
                  </div>
                  <div class="col-md-6 text-md-right small">
                     <a href="#!">Privacy Policy</a>
                     &#xB7;
                     <a href="#">Terms &amp; Conditions</a>
                  </div>
               </div>
            </div>
         </footer>
         <!--end footer-->
      </div>
   </div>

   <?php require_once('./includes/footer.php'); ?>