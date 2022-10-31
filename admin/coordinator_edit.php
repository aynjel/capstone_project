<?php 
session_start();
$app_title = "Update Coordinators";
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

$coordinator_id = $_GET['edit_id'];
$sql = "SELECT * FROM tbl_coordinator WHERE coordinator_id = :coordinator_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
   ':coordinator_id' => $coordinator_id
]);
$coordinator = $stmt->fetch(PDO::FETCH_ASSOC);

// display error if coordinator is not found
if(!$coordinator){
   $error = "Coordinator not found";
   $_SESSION['error'] = $error;
   header('location:coordinators.php');
   exit();
}

$sql = "SELECT * FROM tbl_user WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
   ':user_id' => $coordinator_id
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM tbl_organization WHERE organization_id = :organization_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
   ':organization_id' => $coordinator['organization_id']
]);
$organization = $stmt->fetch(PDO::FETCH_ASSOC);

// If the form is submitted
if(isset($_POST['update'])){
   $coordinator_first_name = $_POST['coordinator_first_name'];
   $coordinator_last_name = $_POST['coordinator_last_name'];
   $coordinator_nickname = $_POST['coordinator_nickname'];
   $user_status = $_POST['user_status'];
   $coordinator_contact_number = $_POST['coordinator_contact_number'];
   $organization_id = $_POST['organization_id'];
   
   $coordinator_email = $_POST['coordinator_email'];
   $coordinator_password = $coordinator_nickname;
   // Validate form inputs
   if(empty($coordinator_first_name) || empty($coordinator_last_name) || empty($coordinator_email) || empty($coordinator_contact_number) || empty($coordinator_password) || empty($organization_id)){
      $_SESSION['error'] = 'All fields are required';
   } else {
      // Update coordinator
      $sql = "UPDATE tbl_user SET email = :user_email, password = :user_password, nickname = :user_nickname, user_status = :user_status WHERE user_id = :user_id";
      $stmt = $conn->prepare($sql);
      $stmt->execute([
         ':user_email' => $coordinator_email,
         ':user_password' => $coordinator_password,
         ':user_nickname' => $coordinator_nickname,
         ':user_id' => $coordinator_id,
         ':user_status' => $user_status
      ]);

      // Update coordinator organization
      $sql = "UPDATE tbl_coordinator SET first_name = :first_name, last_name = :last_name, contact_number = :contact_number, organization_id = :organization_id WHERE coordinator_id = :coordinator_id";
      $stmt = $conn->prepare($sql);
      $stmt->execute([
         ':first_name' => $coordinator_first_name,
         ':last_name' => $coordinator_last_name,
         ':contact_number' => $coordinator_contact_number,
         ':organization_id' => $organization_id,
         ':coordinator_id' => $coordinator_id
      ]);
      
      $_SESSION['success'] = 'Coordinator updated successfully';
      header('location:coordinator_view.php?view_id='.$coordinator_id);
   }
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
                  <div class="page-header-content">
                     <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-tie"></i></div>
                        <span>Update Coordinator</span>
                     </h1>
                  </div>
               </div>
            </div>

            <div class="container-fluid mt-n10">
               <div class="card mb-4">
                  <div class="card-header">Update Coordinator</div>
                  <div class="card-body">
                     <form method="POST"
                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit_id=<?php echo $coordinator_id; ?>">
                        <div class="form-group">
                           <label class="small mb-1" for="coordinator_id">Coordinator ID</label>
                           <input class="form-control" id="coordinator_id" type="text" name="coordinator_id"
                              value="<?php echo $coordinator['coordinator_id']; ?>" readonly />
                        </div>
                        <div class="form-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="first_name">Firstname</label>
                                 <input class="form-control py-3" id="first_name" type="text"
                                    name="coordinator_first_name"
                                    value="<?php echo $_POST['first_name'] ?? $coordinator['first_name'] ?>"
                                    placeholder="Enter Coordinator Firstname" />
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="last_name">Lastname</label>
                                 <input class="form-control py-3" id="last_name" type="text"
                                    name="coordinator_last_name"
                                    value="<?php echo $_POST['last_name'] ?? $coordinator['last_name'] ?>"
                                    placeholder="Enter Coordinator Lastname" />
                              </div>
                           </div>
                        </div>
                        <div class="form-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="contact_number">Contact Number</label>
                                 <input class="form-control py-3" id="contact_number" type="number"
                                    name="coordinator_contact_number"
                                    value="<?php echo $_POST['contact_number'] ?? $coordinator['contact_number'] ?>"
                                    placeholder="Enter Coordinator Contact Number" />
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="organization_id">Organization</label>
                                 <select class="form-control" id="organization_id" name="organization_id">
                                    <option selected value="<?php echo $coordinator['organization_id'] ?>">
                                       <?php echo $organization['organization_name'] ?>
                                    </option>
                                    <?php
                                    $sql = "SELECT * FROM tbl_organization";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $organizations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    ?>
                                    <?php foreach($organizations as $organization) : ?>
                                    <?php if($organization['organization_id'] != $coordinator['organization_id']) : ?>
                                    <option value="<?php echo $organization['organization_id'] ?>">
                                       <?php echo $organization['organization_name'] ?>
                                    </option>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="form-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="email">Email</label>
                                 <input class="form-control py-3" id="email" type="email" name="coordinator_email"
                                    value="<?php echo $_POST['email'] ?? $user['email'] ?>" aria-describedby="emailHelp"
                                    placeholder="Enter Coordinator email address" />
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="nickname">Nickname</label>
                                 <input class="form-control py-3" id="nickname" type="text" name="coordinator_nickname"
                                    value="<?php echo $_POST['nickname'] ?? $user['nickname'] ?>"
                                    placeholder="Enter Coordinator Nickname" />
                              </div>
                           </div>
                        </div>
                        <div class="form-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="user_status">Status</label>
                                 <select class="form-control" id="user_status" name="user_status">
                                    <option selected value="<?php echo $user['user_status'] ?>">
                                       <?php echo $user['user_status'] ?>
                                    </option>
                                    <?php if($user['user_status'] == 'Active') : ?>
                                    <option value="Inactive">Inactive</option>
                                    <?php else : ?>
                                    <option value="Active">Active</option>
                                    <?php endif; ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="user_role">Role</label>
                                 <input class="form-control py-3" id="user_role" type="text" name="user_role"
                                    value="<?php echo $user['user_role'] ?>" readonly />
                              </div>
                           </div>
                        </div>
                        <div class="form-group mt-2 mb-0">
                           <!-- back button -->
                           <a href="coordinator_view.php?view_id=<?php echo $coordinator_id; ?>"
                              class="btn btn-dark">Back</a>
                           <button type="submit" name="update" class="btn btn-primary">Update</button>
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