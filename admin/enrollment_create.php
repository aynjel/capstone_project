<?php 
session_start();
$app_title = "Enroll Student";
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


if(isset($_POST['enroll_student'])){
   if(empty($_POST['student_id'])){
      $_SESSION['error'] = 'Please select a student';
      header('location:enrollment_create.php');
      exit();
   }else{
      $student_id = $_POST['student_id'];
   }

   if(empty($_POST['coordinator_id'])){
      $_SESSION['error'] = 'Please select a coordinator';
      header('location:enrollment_create.php');
      exit();
   }else{
      $coordinator_id = $_POST['coordinator_id'];
   }

   if(empty($_POST['school_year'])){
      $_SESSION['error'] = 'Please select a school year';
      header('location:enrollment_create.php');
      exit();
   }else{
      $school_year = $_POST['school_year'];
   }

   if(empty($_POST['status'])){
      $_SESSION['error'] = 'Please select a status';
      header('location:enrollment_create.php');
      exit();
   }else{
      $status = $_POST['status'];
   }

   if(empty($_POST['date_enrolled'])){
      $_SESSION['error'] = 'Please select a date enrolled';
      header('location:enrollment_create.php');
      exit();
   }else{
      $date_enrolled = $_POST['date_enrolled'];
   }

   $sql = "INSERT INTO tbl_enrollment (school_year, student_id, coordinator_id, status, date_enrolled) VALUES (:school_year, :student_id, :coordinator_id, :status, :date_enrolled)";
   $stmt = $conn->prepare($sql);
   $stmt->execute(['school_year' => $school_year, 'student_id' => $student_id, 'coordinator_id' => $coordinator_id, 'status' => $status, 'date_enrolled' => $date_enrolled]);

   if($stmt->rowCount()){
      $_SESSION['success'] = 'Student enrolled successfully';
      header('location:enrollments.php');
      exit();
   }else{
      $_SESSION['error'] = 'Something went wrong. Please try again';
      header('location:enrollment_create.php');
      exit();
   }
}

?>

<body class="nav-fixed">

   <?php require_once('./includes/nav.php'); ?>

   <!--Side Nav-->
   <div id="layoutSidenav">
      <?php $curr_page = basename(__FILE__); ?>
      <?php require_once './includes/sidebar.php'; ?>

      <div id="layoutSidenav_content">
         <div class="container-fluid">

            <?php if(isset($_SESSION['success'])) : ?>
            <div class="alert alert-success" role="alert">
               <strong>Success!</strong> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger" role="alert">
               <strong>Error!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

         </div>
         <main>
            <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
               <div class="container-fluid">
                  <div class="page-header-content">
                     <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-tie"></i></div>
                        <span>Enroll Student</span>
                     </h1>
                  </div>
               </div>
            </div>

            <div class="container-fluid mt-n10">
               <div class="card mb-4">
                  <div class="card-header">Enroll Student</div>
                  <div class="card-body">
                     <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="form-group">
                           <label class="small mb-1" for="date_enrolled">Date Enrolled</label>
                           <?php // display current date ?>
                           <?php //format date?>
                           <?php //philippine time zone?>
                           <input class="form-control" id="date_enrolled" name="date_enrolled" type="text" value="<?php
                              date_default_timezone_set('Asia/Manila');
                              echo date('F d, Y h:i A');
                           ?>" readonly />
                        </div>
                        <div class="form-group">
                           <label class="small mb-1" for="school_year">School Year</label>
                           <select class="form-control" id="school_year" name="school_year">
                              <option selected hidden disabled>Select School Year</option>
                              <option value="2020-2021">2020-2021</option>
                              <option value="2021-2022">2021-2022</option>
                              <option value="2022-2023">2022-2023</option>
                              <option value="2023-2024">2023-2024</option>
                              <option value="2024-2025">2024-2025</option>
                              <option value="2025-2026">2025-2026</option>
                              <option value="2026-2027">2026-2027</option>
                              <option value="2027-2028">2027-2028</option>
                              <option value="2028-2029">2028-2029</option>
                              <option value="2029-2030">2029-2030</option>
                           </select>
                        </div>
                        <div class="form-group">
                           <label class="small mb-1" for="student_id">Student</label>
                           <select class="form-control" id="student_id" name="student_id">
                              <option selected hidden disabled>Select Student</option>
                              <?php
                                 $sql = "SELECT * FROM tbl_student WHERE student_id NOT IN (SELECT student_id FROM tbl_enrollment)";
                                 $stmt = $conn->prepare($sql);
                                 $stmt->execute();
                                 $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                 foreach($students as $student) :
                              ?>
                              <option value="<?php echo $student['student_id']; ?>">
                                 <?php echo $student['first_name'] . ' ' . $student['last_name']; ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                        <div class="form-group">
                           <label class="small mb-1" for="coordinator_id">Coordinator</label>
                           <select class="form-control" id="coordinator_id" name="coordinator_id">
                              <option selected hidden disabled>Select Coordinator</option>
                              <?php
                                 $sql = "SELECT * FROM tbl_coordinator";
                                 $stmt = $conn->prepare($sql);
                                 $stmt->execute();
                                 $coordinators = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                 foreach($coordinators as $coordinator) :
                                    // get organization name
                                    $sql = "SELECT * FROM tbl_organization WHERE organization_id = :organization_id";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute(['organization_id' => $coordinator['organization_id']]);
                                    $organization = $stmt->fetch(PDO::FETCH_ASSOC);
                              ?>
                              <option value="<?php echo $coordinator['coordinator_id']; ?>">
                                 <?php echo $coordinator['first_name'] . ' ' . $coordinator['last_name']; ?>
                                 (<?php echo $organization['organization_name']; ?>)
                              </option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                        <div class="form-group">
                           <label class="small mb-1" for="status">Status</label>
                           <input type="text" value="Enrolled" class="form-control" id="status" name="status" readonly>
                        </div>
                        <div class="form-group mt-2 mb-0">
                           <button type="submit" name="enroll_student" class="btn btn-primary btn-block">Enroll
                              Student</button>
                        </div>
                     </form>
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