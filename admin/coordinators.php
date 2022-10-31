<?php 
session_start();
$app_title = "Coordinators";
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
                        <span>Coordinator</span>
                     </h1>
                     <a href="coordinator_create.php" title="Create Coordinator" class="btn btn-white">
                        <div class="page-header-icon">
                           <i class="fas fa-plus"></i>
                           Create
                        </div>
                     </a>
                  </div>
               </div>
            </div>

            <!--Table-->
            <div class="container-fluid mt-n10">

               <div class="card mb-4">
                  <div class="card-header">Coordinator List</div>
                  <div class="card-body">
                     <div class="datatable table-responsive">
                        <?php
                           $sql = "SELECT * FROM tbl_coordinator";
                           $stmt = $conn->prepare($sql);
                           $stmt->execute();
                           $coordinators = $stmt->fetchAll(PDO::FETCH_ASSOC);
                           $count = $stmt->rowCount();
                        ?>
                        <?php if ($count > 0) : ?>
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                 <th>Name</th>
                                 <th>Email</th>
                                 <th>Status</th>
                                 <th>Organization</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($coordinators as $coordinator) : ?>
                              <?php
                                    $sql = "SELECT * FROM tbl_user WHERE user_id = :user_id";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([
                                       ':user_id' => $coordinator['coordinator_id']
                                    ]);
                                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                                    $sql = "SELECT * FROM tbl_organization WHERE organization_id = :organization_id";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([
                                       ':organization_id' => $coordinator['organization_id']
                                    ]);
                                    $organization = $stmt->fetch(PDO::FETCH_ASSOC);
                              ?>
                              <tr>
                                 <td>
                                    <?php echo $coordinator['first_name'] . ' ' . $coordinator['last_name']; ?>
                                 </td>
                                 <td>
                                    <?php echo $user['email']; ?>
                                 </td>
                                 <td>
                                    <?php
                                       if ($user['user_status'] == 'Active') {
                                          echo '<span class="badge badge-success">Active</span>';
                                       } else {
                                          echo '<span class="badge badge-danger">Inactive</span>';
                                       }
                                    ?>
                                 </td>
                                 <td>
                                    <?php echo $organization['organization_name']; ?>
                                 </td>
                                 <td>
                                    <!-- view -->
                                    <a href="coordinator_view.php?view_id=<?php echo $coordinator['coordinator_id']; ?>"
                                       class="btn btn-info btn-icon">
                                       <i class="fas fa-eye"></i>
                                    </a>
                                 </td>
                              </tr>
                              <?php endforeach; ?>
                           </tbody>
                        </table>
                        <?php else : ?>
                        <div class="h4 alert alert-light text-center" role="alert">
                           No Coordinator Found
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