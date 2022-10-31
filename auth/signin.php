<?php
   session_start();
   require '../config/config.php';

   // If the user is already logged in, redirect to the home page
   if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'Admin') {
      $_SESSION['success'] = 'You are already logged in as an Admin';
      header('refresh:2;url=../admin/index.php');
      exit();
   }
   if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'Coordinator') {
      $_SESSION['success'] = 'You are already logged in as a Coordinator';
      header('refresh:2;url=../coordinator/index.php');
      exit();
   }
   if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'Student') {
      $_SESSION['success'] = 'You are already logged in as a Student';
      header('refresh:2;url=../student/index.php');
      exit();
   }

   if(isset($_POST['sign_in'])){
      if(empty($_POST['email']) || empty($_POST['password'])){
         $_SESSION['error'] = 'All fields are required';
      } else if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
         $_SESSION['error'] = 'Invalid email address';
   } else if (!isset($error)) {
      $sql = "SELECT * FROM tbl_user WHERE email = :email";
      $stmt = $conn->prepare($sql);
      $stmt->execute([
         ':email' => $_POST['email']
      ]);
      $count = $stmt->rowCount();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if($count > 0){
         if(password_verify($_POST['password'], $user['password'])){
            if(isset($_POST['remember'])){
               setcookie('email', $_POST['email'], time() + 86400);
               setcookie('password', $_POST['password'], time() + 86400);
            }
            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['user_status'] = $user['user_status'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nickname'] = $user['nickname'];
            
            if($user['user_role'] == 'Admin'){
               $_SESSION['success'] = 'You are now logged in as an Admin';
               header('refresh:2;url=../admin/index.php');
            } else if($user['user_role'] == 'Coordinator'){
               $_SESSION['success'] = 'You are now logged in as a Coordinator';
               header('refresh:2;url=../coordinator/index.php');
            } else if($user['user_role'] == 'Student'){
               $_SESSION['success'] = 'You are now logged in as a Student';
               header('refresh:2;url=../student/index.php');
            }else{
               $_SESSION['error'] = 'Invalid login credentials';
            }
         } else if($_POST['password'] == $user['password']){
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['user_status'] = $user['user_status'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nickname'] = $user['nickname'];
            
            if($user['user_role'] == 'Admin'){
               $_SESSION['success'] = 'You are now logged in as an Admin';
               header('refresh:2;url=../admin/index.php');
            } else if($user['user_role'] == 'Coordinator'){
               $_SESSION['success'] = 'You are now logged in as a Coordinator';
               header('refresh:2;url=../coordinator/index.php');
            } else if($user['user_role'] == 'Student'){
               $_SESSION['success'] = 'You are now logged in as a Student';
               header('refresh:2;url=../student/index.php');
            }else{
               $_SESSION['error'] = 'Invalid login credentials';
            }
         } else {
            $_SESSION['error'] = 'Invalid login credentials';
         }
      } else {
         $_SESSION['error'] = 'Invalid login credentials';
      }
      
   }else{
      $_SESSION['error'] = 'Invalid login credentials';
   }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
   <title>Sign In</title>
   <link href="css/styles.css" rel="stylesheet" />
   <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
   <script data-search-pseudo-elements defer src="js/all.min.js"></script>
   <script src="js/feather.min.js"></script>
</head>

<body class="bg-primary">
   <div id="layoutAuthentication">
      <div id="layoutAuthentication_content">
         <div class="container">
            <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
               <div class="card shadow-lg border-0 rounded-lg mt-5" style="width: 400px;">
                  <div class="card-header justify-content-center">
                     <h3 class="font-weight-light my-1 font-weight-bold text-uppercase">SIGN IN</h3>
                  </div>
                  <div class="card-body">

                     <?php if(isset($_SESSION['error'])): ?>
                     <div class="alert alert-danger" role="alert">
                        <strong><?php echo $_SESSION['error']; ?></strong>
                     </div>
                     <?php unset($_SESSION['error']); endif; ?>

                     <?php if(isset($_SESSION['success'])): ?>
                     <div class="alert alert-success" role="alert">
                        <strong><?php echo $_SESSION['success']; ?></strong>
                     </div>
                     <?php unset($_SESSION['success']); endif; ?>

                     <form action="signin.php" method="POST">
                        <div class="form-group">
                           <label class="small mb-1" for="email">Email</label>
                           <input class="form-control py-4" id="email" type="email" name="email"
                              value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"
                              aria-describedby="emailHelp" placeholder="Enter email address" />
                           <div class="form-group"><label class="small mb-1" for="password">Password</label>
                              <input class="form-control py-4" id="password" type="password" name="password"
                                 placeholder="Enter password" />
                           </div>
                        </div>
                        <div class="form-group form-row">
                           <div class="col-md-6">
                              <div class="custom-control custom-checkbox">
                                 <input class="custom-control-input" id="rememberPasswordCheck" type="checkbox"
                                    name="remember">
                                 <label class="custom-control-label" for="rememberPasswordCheck">Remember Me</label>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="text-right"><a href="forgot_password.php">Forgot Password?</a></div>
                           </div>
                        </div>
                        <div class="form-group mt-4 mb-0">
                           <button type="submit" name="sign_in" class="btn btn-primary btn-block">
                              Sign In
                           </button>
                        </div>
                     </form>
                  </div>
                  <div class="card-footer text-center">
                     <div class="small"><a href="signup.php">Need an account? Sign up!</a></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!--Script JS-->
   <script src="js/jquery-3.4.1.min.js"></script>
   <script src="js/bootstrap.bundle.min.js"></script>
   <script src="js/scripts.js"></script>
</body>

</html>