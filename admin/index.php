<?php 
session_start();
$app_title = "Dashboard";
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
               <strong>Success!</strong> <?php echo $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger" role="alert">
               <strong>Error!</strong> <?php echo $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
         </div>
         <main>
            <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
               <div class="container-fluid">
                  <div class="page-header-content d-flex align-items-center justify-content-between text-white">
                     <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="activity"></i></div>
                        <span>Dashboard</span>
                     </h1>
                     <a href="enrollment_create.php" title="Enroll a Student" class="btn btn-white">
                        <div class="page-header-icon">
                           <i class="fas fa-plus"></i>
                           Enroll
                        </div>
                     </a>
                  </div>
               </div>
            </div>

            <!--Table-->
            <div class="container-fluid mt-n10">

               <!--Card Primary-->
               <div class="row">
                  <div class="col-xl-3 col-md-6">
                     <div class="card bg-primary text-white mb-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                           <p>Organization</p>
                           <?php
                           $sql = "SELECT * FROM tbl_organization";
                           $stmt = $conn->prepare($sql);
                           $stmt->execute();
                           $org_count = $stmt->rowCount();
                           ?>
                           <p>
                              <?php echo $org_count; ?>
                           </p>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                           <a class="small text-white stretched-link" href="organizations.php">View Details</a>
                           <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="card bg-primary text-white mb-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                           <p>Total Coordinator</p>
                           <?php
                           $sql = "SELECT * FROM tbl_user WHERE user_role = 'Coordinator'";
                           $stmt = $conn->prepare($sql);
                           $stmt->execute();
                           $coordinator_count = $stmt->rowCount();
                           ?>
                           <p>
                              <?php echo $coordinator_count; ?>
                           </p>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                           <a class="small text-white stretched-link" href="coordinators.php">View Details</a>
                           <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="card bg-primary text-white mb-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                           <p>Total Student</p>
                           <?php
                           $sql = "SELECT * FROM tbl_user WHERE user_role = 'Student'";
                           $stmt = $conn->prepare($sql);
                           $stmt->execute();
                           $student_count = $stmt->rowCount();
                           ?>
                           <p>
                              <?php echo $student_count; ?>
                           </p>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                           <a class="small text-white stretched-link" href="students.php">View Details</a>
                           <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="card bg-primary text-white mb-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                           <p>All Users</p>
                           <?php
                           // get all users except admin
                           $sql = "SELECT * FROM tbl_user WHERE user_role != 'Admin'";
                           $stmt = $conn->prepare($sql);
                           $stmt->execute();
                           $user_count = $stmt->rowCount();
                           ?>
                           <p>
                              <?php echo $user_count; ?>
                           </p>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                           <a class="small text-white stretched-link" href="users.php">View Details</a>
                           <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                     </div>
                  </div>
               </div>
               <!--Card Primary-->

               <div class="card mb-4">
                  <div class="card-header">Recent Activity</div>
                  <div class="card-body">
                     <div class="datatable table-responsive">
                        <?php
                        $sql = "SELECT * FROM tbl_user WHERE user_role != 'Admin' ORDER BY user_id DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $count = $stmt->rowCount();
                        if($count > 0):
                        ?>
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                 <th>Full Name</th>
                                 <th>Email</th>
                                 <th>Role</th>
                                 <th>Status</th>
                                 <th>Created At</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($users as $user) : ?>
                              <tr>
                                 <td><?php echo $user['nickname']; ?></td>
                                 <td><?php echo $user['email']; ?></td>
                                 <td><?php echo $user['user_role']; ?></td>
                                 <td>
                                    <?php
                                    if($user['user_status'] == 'Active') {
                                       echo '<span class="badge badge-success">Active</span>';
                                    } else {
                                       echo '<span class="badge badge-danger">Inactive</span>';
                                    }
                                    ?>
                                 </td>
                                 <td>
                                    <?php 
                                    $date = date_create($user['created_at']);
                                    echo date_format($date, 'M d, Y h:i A');
                                    ?>
                                 </td>
                              </tr>
                              <?php endforeach; ?>
                           </tbody>
                        </table>
                        <?php else: ?>
                        <div class="alert alert-danger" role="alert">
                           <strong>Sorry!</strong> No user found.
                        </div>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
            </div>
            <!--End Table-->

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