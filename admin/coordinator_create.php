<?php 
session_start();
$app_title = "Create Coordinators";
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


if(isset($_POST['create'])){
   if(empty($_POST['email']) || empty($_POST['nickname']) || empty($_POST['organization_id']) || empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['contact_number'])){
      $_SESSION['error'] = 'All fields are required';
   } else if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
      $_SESSION['error'] = 'Invalid email address';
   } else if(!isset($error)){
      $sql = "SELECT * FROM tbl_user WHERE email = :email";
      $stmt = $conn->prepare($sql);
      $stmt->execute([
            ':email' => $_POST['email']
      ]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if($user){
            $_SESSION['error'] = 'Email already exists';
      } else if(!isset($error)){
            $role = 'Coordinator';
            $nickname = $_POST['nickname'];
            $password = $nickname;
            $sql = "INSERT INTO tbl_user (email, password, nickname, user_role, user_status) VALUES (:email, :password, :nickname, :user_role, :user_status)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
               ':email' => $_POST['email'],
               ':password' => $nickname,
               ':nickname' => $nickname,
               ':user_role' => $role,
               ':user_status' => 'Active'
            ]);

            if($stmt){
               $_SESSION['success'] = 'Coordinator created successfully';
               $user_id = $conn->lastInsertId();

               $sql = "INSERT INTO tbl_coordinator (coordinator_id, organization_id, first_name, last_name, contact_number) VALUES (:coordinator_id, :organization_id, :first_name, :last_name, :contact_number)";
               $stmt = $conn->prepare($sql);
               $stmt->execute([
                  ':coordinator_id' => $user_id,
                  ':organization_id' => $_POST['organization_id'],
                  ':first_name' => $_POST['first_name'],
                  ':last_name' => $_POST['last_name'],
                  ':contact_number' => $_POST['contact_number']
               ]);

               if($stmt){
                  $_SESSION['success'] = 'Coordinator created successfully';
                  header('location: coordinators.php');
               } else {
                  $_SESSION['error'] = 'Something went wrong. Please try again';
               }
            } else {
               $_SESSION['error'] = 'Something went wrong. Please try again';
            }
            exit();
      }
   } else {
      $_SESSION['error'] = 'Something went wrong. Please try again';
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
                        <span>Add Coordinator</span>
                     </h1>
                  </div>
               </div>
            </div>

            <div class="container-fluid mt-n10">
               <div class="card mb-4">
                  <div class="card-header">Create New Coordinator</div>
                  <div class="card-body">
                     <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="form-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="first_name">Firstname</label>
                                 <input class="form-control py-3" id="first_name" type="text" name="first_name"
                                    value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name']; ?>"
                                    placeholder="Enter Coordinator Firstname" />
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="last_name">Lastname</label>
                                 <input class="form-control py-3" id="last_name" type="text" name="last_name"
                                    value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name']; ?>"
                                    placeholder="Enter Coordinator Lastname" />
                              </div>
                           </div>
                        </div>
                        <div class="form-row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="small mb-1" for="nickname">Nickname</label>
                                 <input class="form-control py-3" id="nickname" type="text" name="nickname"
                                    value="<?php if(isset($_POST['nickname'])) echo $_POST['nickname']; ?>"
                                    placeholder="Enter Coordinator Nickname" />
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="small mb-1" for="contact_number">Contact Number</label>
                                 <input class="form-control py-3" id="contact_number" type="number"
                                    name="contact_number"
                                    value="<?php if(isset($_POST['contact_number'])) echo $_POST['contact_number']; ?>"
                                    placeholder="Enter Coordinator Contact Number" />
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="small mb-1" for="organization_id">Organization</label>
                                 <select class="form-control" id="organization_id" name="organization_id">
                                    <option selected hidden disabled>Select Organization</option>
                                    <?php
                                    $sql = "SELECT * FROM tbl_organization";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $organizations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    ?>
                                    <?php foreach($organizations as $organization) : ?>
                                    <option value="<?php echo $organization['organization_id']; ?>">
                                       <?php echo $organization['organization_name']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="form-row">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label class="small mb-1" for="email">Email</label>
                                 <input class="form-control py-3" id="email" type="email" name="email"
                                    value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"
                                    aria-describedby="emailHelp" placeholder="Enter Coordinator email address" />
                              </div>
                           </div>
                        </div>
                        <div class="form-group mt-2 mb-0">
                           <button type="submit" name="create" class="btn btn-primary">Create</button>
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