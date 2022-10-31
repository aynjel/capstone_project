<?php
   session_start();
   require '../config/config.php';

   if(isset($_POST['reset'])) {
   $nickname = $_POST['nickname'];
   $email = $_POST['email'];

   $sql = "SELECT * FROM tbl_user WHERE nickname = :nickname AND email = :email";
   $stmt = $conn->prepare($sql);
   $stmt->execute([
         ':nickname' => $nickname,
         ':email' => $email
      ]);
   $count = $stmt->rowCount();
   if($count == 1) {
         $user = $stmt->fetch(PDO::FETCH_ASSOC);
         $user_id = $user['user_id'];
         $success = 'You can now reset your password';
   } else {
         $error = 'Invalid credentials';
   }
}

if(isset($_POST['update'])) {
   $password = $_POST['password'];
   $confirm_password = $_POST['confirm-password'];
   $user_id = $_POST['id'];
   $success = 'You can now reset your password';
   if($password == $confirm_password) {
         $hash_password = password_hash($password, PASSWORD_BCRYPT, ['cost'=>10]);
         $sql = "UPDATE tbl_user SET password = :password WHERE user_id = :user_id";
         $stmt = $conn->prepare($sql);
         $stmt->execute([
            ':password' => $hash_password,
            ':user_id' => $user_id
         ]);
         $success = 'Password updated successfully <a href="signin.php">Sign in</a>';
         $_POST['password'] = '';
         $_POST['confirm-password'] = '';
   } else {
         $error = 'Password does not match';
   }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
   <title>Forgot Password</title>
   <link href="css/styles.css" rel="stylesheet" />
   <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
   <script data-search-pseudo-elements defer src="js/all.min.js"></script>
   <script src="js/feather.min.js"></script>
</head>

<body class="bg-primary">
   <div id="layoutAuthentication">
      <div id="layoutAuthentication_content">
         <main>
            <div class="container">
               <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
                  <div class="card shadow-lg border-0 rounded-lg mt-5" style="width: 400px;">
                     <div class="card-header justify-content-center">
                        <h3 class="font-weight-light my-2">Password Recovery</h3>
                     </div>
                     <div class="card-body">

                        <?php  if(!isset($success)) : ?>
                        <?php if(isset($error)) : ?>
                        <div class="alert alert-danger" role="alert">
                           <strong>Error!</strong> <?php echo $error; unset($error); ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                           <div class="form-group">
                              <label class="small mb-1" for="Nickname">Nickname</label>
                              <input name="nickname" class="form-control py-4" id="Nickname" type="text"
                                 placeholder="Enter Nickname..." />
                           </div>
                           <div class="form-group">
                              <label class="small mb-1" for="inputEmailAddress">Email</label>
                              <input name="email" class="form-control py-4" id="inputEmailAddress" type="email"
                                 aria-describedby="emailHelp" placeholder="Enter email address..." />
                           </div>
                           <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                              <a class="small" href="signin.php">Return to signin</a>
                              <button name="reset" class="btn btn-primary" type="submit">Reset Password</button>
                           </div>
                        </form>

                        <?php else : ?>
                        <div class="alert alert-success" role="alert">
                           <?php echo $success; unset($success); ?>
                        </div>

                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                           <div class="form-group">
                              <input name="id" value="<?php echo $user_id; ?>" type="hidden" />
                              <label class="small mb-1" for="passowrd">New Password</label>
                              <input name="password" class="form-control py-4" id="passowrd" type="password"
                                 placeholder="New Password" required="true" />
                           </div>
                           <div class="form-group">
                              <label class="small mb-1" for="confirmpassword">Confirm New Password</label>
                              <input name="confirm-password" class="form-control py-4" type="password"
                                 placeholder="Confirm New Password" required="true" />
                           </div>
                           <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                              <button name="update" class="btn btn-primary" type="submit">Update Password</button>
                           </div>
                        </form>

                        <?php endif; ?>

                     </div>
                     <div class="card-footer text-center">
                        <div class="small"><a href="signup.php">Need an account? Sign up!</a></div>
                     </div>
                  </div>
               </div>
            </div>
         </main>
      </div>
   </div>

   <!--Script JS-->
   <script src="js/jquery-3.4.1.min.js"></script>
   <script src="js/bootstrap.bundle.min.js"></script>
   <script src="js/scripts.js"></script>
</body>

</html>