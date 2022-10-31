<?php 
session_start();
$app_title =  "Coordinator Details";
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

// redirect to organization list if no id is set
if(!isset($_GET['view_id']) || $_GET['view_id'] == NULL){
   $_SESSION['error'] = 'No Coordinator selected';
   header('location:coordinators.php');
}

$user_id = $_GET['view_id'];
$sql = "SELECT * FROM tbl_user WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
   ':user_id' => $user_id
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM tbl_coordinator WHERE coordinator_id = :coordinator_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
   ':coordinator_id' => $user['user_id']
]);
$coordinator = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM tbl_organization WHERE organization_id = :organization_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
   ':organization_id' => $coordinator['organization_id']
]);
$organization = $stmt->fetch(PDO::FETCH_ASSOC);

// display error if coordinator is not found
if(!$coordinator){
   $_SESSION['error'] = 'Coordinator not found';
   header('location:coordinators.php');
   exit();
}

?>

<body class="nav-fixed">
   <?php $curr_page = basename(__FILE__); ?>
   <?php require_once('./includes/nav.php'); ?>

   <!--Side Nav-->
   <div id="layoutSidenav">

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
                  <div class="page-header-content d-flex align-items-center justify-content-between text-white">
                     <h1 class="page-header-title">
                        <div class="page-header-icon">
                           <i class="fas fa-user-tie"></i>
                        </div>
                        <span><?php echo $coordinator['first_name'] . ' ' . $coordinator['last_name']; ?></span>
                     </h1>
                     <div>
                        <a href="coordinator_edit.php?edit_id=<?php echo $coordinator['coordinator_id']; ?>"
                           title="Edit Coordinator" class="btn btn-white">
                           <div class="page-header-icon">
                              <i class="fas fa-pen-to-square"></i>
                              Edit
                           </div>
                        </a>
                        <a href="coordinator_delete.php?delete_id=<?php echo $coordinator['coordinator_id']; ?>"
                           onclick="return confirm('Are you sure you want to delete this Coordinator?')"
                           title="Delete Coordinator" class="btn btn-danger">
                           <div class="page-header-icon">
                              <i class="fas fa-trash"></i>
                              Delete
                           </div>
                        </a>
                     </div>
                  </div>
               </div>
            </div>
            <div class="container-fluid mt-n10">
               <div class="card mb-4">
                  <div class="card-header">Coordinator Details</div>
                  <div class="card-body">
                     <div class="form-group">
                        <label for="coordinator_id">Coordinator ID</label>
                        <input type="text" class="form-control" id="coordinator_id" name="coordinator_id"
                           value="<?php echo $coordinator['coordinator_id']; ?>" readonly>
                        <div class="form-row">
                           <div class="col-md-6">
                              <label for="first_name">First Name</label>
                              <input type="text" class="form-control" id="first_name" name="first_name"
                                 value="<?php echo $coordinator['first_name']; ?>" readonly>
                           </div>
                           <div class="col-md-6">
                              <label for="last_name">Last Name</label>
                              <input type="text" class="form-control" id="last_name" name="last_name"
                                 value="<?php echo $coordinator['last_name']; ?>" readonly>
                           </div>
                        </div>
                        <div class="form-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="contact_number">Contact Number:</label>
                                 <input type="text" class="form-control" id="contact_number" name="contact_number"
                                    value="<?php echo $coordinator['contact_number']; ?>" readonly>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="organization_name">Organization:</label>
                                 <input type="text" class="form-control" id="organization_name" name="organization_name"
                                    value="<?php echo $organization['organization_name']; ?>" readonly>
                              </div>
                           </div>
                        </div>
                        <div class="form-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="nickname">Nickname:</label>
                                 <input type="text" class="form-control" id="nickname" name="nickname"
                                    value="<?php echo $user['nickname']; ?>" readonly>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="email">Email Address:</label>
                                 <input type="text" class="form-control" id="coordinator_email" name="coordinator_email"
                                    value="<?php echo $user['email']; ?>" readonly>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="password">Password:</label>
                           <input type="text" class="form-control" id="password" name="password"
                              value="<?php echo $user['password']; ?>" readonly>
                        </div>
                        <div class="form-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="user_status">User Status:</label>
                                 <input type="text" class="form-control" id="user_status" name="user_status"
                                    value="<?php echo $user['user_status']; ?>" readonly>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="user_role">User Role:</label>
                                 <input type="text" class="form-control" id="user_role" name="user_role"
                                    value="<?php echo $user['user_role']; ?>" readonly>
                              </div>
                           </div>
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