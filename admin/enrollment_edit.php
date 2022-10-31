<?php 
session_start();
$app_title =  "Update Enrollment Details";
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
if(!isset($_GET['edit_id']) || $_GET['edit_id'] == NULL){
   header('location:enrollments.php');
}

$enrollment_id = $_GET['edit_id'];
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

$student_id = @$_POST['student_id'];
$coordinator_id = @$_POST['coordinator_id'];
$status = @$_POST['status'];
$date_enrolled = @$_POST['date_enrolled'];
$school_year = @$_POST['school_year'];

if(isset($_POST['update_enrollment'])){
   
   if(empty($_POST['school_year'])){
      $_SESSION['error'] = 'School Year is required';
   }else{
      $sql = "UPDATE tbl_enrollment SET student_id = :student_id, coordinator_id = :coordinator_id, status = :status, date_enrolled = :date_enrolled, school_year = :school_year WHERE enrollment_id = :enrollment_id";
      $stmt = $conn->prepare($sql);
      $stmt->execute([
         ':student_id' => $student_id,
         ':coordinator_id' => $coordinator_id,
         ':status' => $status,
         ':date_enrolled' => $date_enrolled,
         ':school_year' => $school_year,
         ':enrollment_id' => $enrollment_id
      ]);
      
      if(isset($stmt)){
         $_SESSION['success'] = 'Enrollment updated successfully';
         header('location:enrollment_view.php?view_id='.$enrollment_id);
      }else{
         $_SESSION['error'] = 'Enrollment not updated';
      }
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
                     <h1 class="page-header-title">
                        <div class="page-header-icon">
                           <i class="fas fa-building"></i>
                        </div>
                        <span>Update Enrollment Details</span>
                     </h1>
                  </div>
               </div>
            </div>
            <div class="container-fluid mt-n10">
               <div class="card mb-4">
                  <div class="card-header">Update Enrollment Details</div>
                  <div class="card-body">
                     <form method="POST"
                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit_id=<?php echo $enrollment_id; ?>">
                        <div class="form-group">
                           <label for="enrollment_id">Enrollment ID</label>
                           <input class="form-control" id="enrollment_id" type="text" name="enrollment_id"
                              value="<?php echo $enrollment['enrollment_id']; ?>" readonly />
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="first_name">First Name</label>
                                 <input class="form-control" id="first_name" type="text" name="first_name"
                                    value="<?php echo $student['first_name']; ?>" readonly />
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="last_name">Last Name</label>
                                 <input class="form-control" id="last_name" type="text" name="last_name"
                                    value="<?php echo $student['last_name']; ?>" readonly />
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="student_id">Student ID</label>
                           <input class="form-control" id="student_id" type="text" name="student_id"
                              value="<?php echo $student['student_id']; ?>" readonly />
                        </div>
                        <div class="form-group">
                           <label for="coordinator_id">Coordinator ID</label>
                           <input class="form-control" id="coordinator_id" type="text" name="coordinator_id"
                              value="<?php echo $coordinator['coordinator_id']; ?>" readonly />
                        </div>
                        <div class="form-group">
                           <label for="school_year">School Year</label>
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
                           <label for="date_enrolled">Date Enrolled</label>
                           <input class="form-control" id="date_enrolled" type="text" name="date_enrolled"
                              value="<?php echo $enrollment['date_enrolled']; ?>" readonly />
                        </div>
                        <div class="form-group">
                           <label for="status">Status</label>
                           <input type="text" class="form-control" id="status" name="status"
                              value="<?php echo $enrollment['status']; ?>" readonly>
                        </div>
                        <a href="enrollment_view.php?view_id=<?php echo $enrollment['enrollment_id']; ?>"
                           class="btn btn-dark">Back</a>
                        <button type="submit" class="btn btn-primary" name="update_enrollment">Update</button>
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