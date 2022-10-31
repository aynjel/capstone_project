<?php 
session_start();
$app_title = "Update Course";
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

// redirect to course list if no id is set
if(!isset($_GET['edit_id']) || $_GET['edit_id'] == NULL){
   header('location:courses.php');
}

$course_id = $_GET['edit_id'];
$sql = "SELECT * FROM tbl_course WHERE course_id = :course_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['course_id' => $course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['update'])){
   $course_name = $_POST['course_name'];
   $course_code = $_POST['course_code'];

   if(empty($course_name) || empty($course_code)){
      $_SESSION['error'] = "All fields are required";
   } else {
      $sql1 = "UPDATE tbl_course SET course_code = :course_code, course_name = :course_name WHERE course_id = :course_id";
      $stmt1 = $conn->prepare($sql1);
      $stmt1->execute([
         'course_code' => $course_code,
         'course_name' => $course_name,
         'course_id' => $course_id
      ]);
      
      $_SESSION['success'] = 'Course updated successfully';
      header('Location: course_view.php?view_id='.$course_id);
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

            <?php if(isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger" role="alert">
               <strong>Error!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])) : ?>
            <div class="alert alert-success" role="alert">
               <strong>Success!</strong> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

         </div>
         <main>
            <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
               <div class="container-fluid">
                  <div class="page-header-content">
                     <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-school"></i></div>
                        <span>Update Course</span>
                     </h1>
                  </div>
               </div>
            </div>

            <div class="container-fluid mt-n10">
               <div class="card mb-4">
                  <div class="card-header">Course Details</div>
                  <div class="card-body">
                     <form method="POST"
                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit_id=<?php echo $course_id; ?>">
                        <div class="form-group">
                           <label for="course_id">Course ID</label>
                           <input class="form-control" id="course_id" type="text" name="course_id"
                              value="<?php echo $course['course_id']; ?>" readonly />
                        </div>
                        <div class="form-group">
                           <label for="course_name">Course Name</label>
                           <input class="form-control" id="course_name" type="text" name="course_name"
                              value="<?php echo $course['course_name']; ?>" />
                        </div>
                        <div class="form-group">
                           <label for="course_code">Course Code</label>
                           <input class="form-control" id="course_code" type="text" name="course_code"
                              value="<?php echo $course['course_code']; ?>" />
                        </div>
                        <a href="course_view.php?view_id=<?php echo $course['course_id']; ?>"
                           class="btn btn-dark">Back</a>
                        <button class="btn btn-primary mr-2 my-1" type="submit" name="update">Save</button>
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