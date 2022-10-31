<?php
   session_start();
   require '../config/config.php';

   // If the user is already logged in, redirect to the home page
   if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'Admin') {
      $_SESSION['error'] = 'You are already logged in as an Admin';
      header('refresh:2;url=../admin/index.php');
      exit();
   }
   if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'Coordinator') {
      $_SESSION['error'] = 'You are already logged in as a Coordinator';
      header('refresh:2;url=../coordinator/index.php');
      exit();
   }
   if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'Student') {
      $_SESSION['error'] = 'You are already logged in as a Student';
      header('refresh:2;url=../student/index.php');
      exit();
   }

   if(isset($_POST['sign_up'])){
      if(empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password']) || empty($_POST['nickname'])){
         $_SESSION['error'] = 'All fields are required';
      } else if($_POST['password'] != $_POST['confirm_password']){
         $_SESSION['error'] = 'Password does not match';
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
               $role = 'Student';

               $sql = "INSERT INTO tbl_user (email, password, nickname, user_role, user_status) VALUES (:email, :password, :nickname, :user_role, :user_status)";
               $stmt = $conn->prepare($sql);
               $stmt->execute([
                  ':email' => $_POST['email'],
                  ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                  ':nickname' => $_POST['nickname'],
                  ':user_role' => $role,
                  ':user_status' => 'Active'
               ]);
               $sql = "INSERT INTO tbl_student (student_id, school_id_number, first_name, last_name, course_id, contact_number) VALUES (:student_id, :school_id_number, :first_name, :last_name, :course_id, :contact_number)";
               $stmt = $conn->prepare($sql);
               $stmt->execute([
                  ':student_id' => $conn->lastInsertId(),
                  ':school_id_number' => $_POST['school_id_number'],
                  ':first_name' => $_POST['first_name'],
                  ':last_name' => $_POST['last_name'],
                  ':course_id' => $_POST['course_id'],
                  ':contact_number' => $_POST['contact_number']
               ]);
               $_SESSION['success'] = 'Student account created successfully <a href="signin.php">Login Now</a>';
               $_POST['email'] = '';
               $_POST['password'] = '';
               $_POST['confirm_password'] = '';
               $_POST['nickname'] = '';
               $_POST['school_id_number'] = '';
               $_POST['first_name'] = '';
               $_POST['last_name'] = '';
               $_POST['course_id'] = '';
               $_POST['contact_number'] = '';
         }
      } else {
         $_SESSION['error'] = 'Something went wrong';
      }
   }
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
   <title>Sign Up</title>
   <link href="css/styles.css" rel="stylesheet" />
   <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
   <script data-search-pseudo-elements defer src="js/all.min.js"></script>
   <script src="js/feather.min.js"></script>
</head>

<body class="bg-primary">
   <div id="layoutAuthentication">
      <div id="layoutAuthentication_content">
         <div class="container">
            <div class="d-flex justify-content-center align-items-center">
               <div class="card shadow-lg border-0 rounded-lg mt-5">
                  <div class="card-header justify-content-center">
                     <h3 class="font-weight-light font-weight-bold text-uppercase">Create Account</h3>
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

                     <form action="signup.php" method="POST">
                        <div class="form-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="first_name">Firstname</label>
                                 <input class="form-control py-3" id="first_name" type="text" name="first_name"
                                    value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name']; ?>"
                                    placeholder="Enter Firstname" />
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="last_name">Lastname</label>
                                 <input class="form-control py-3" id="last_name" type="text" name="last_name"
                                    value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name']; ?>"
                                    placeholder="Enter Lastname" />
                              </div>
                           </div>
                        </div>
                        <div class="form-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="nickname">Nickname</label>
                                 <input class="form-control py-3" id="nickname" type="text" name="nickname"
                                    value="<?php if(isset($_POST['nickname'])) echo $_POST['nickname']; ?>"
                                    placeholder="Enter Nickname" />
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small mb-1" for="contact_number">Contact Number</label>
                                 <input class="form-control py-3" id="contact_number" type="number"
                                    name="contact_number"
                                    value="<?php if(isset($_POST['contact_number'])) echo $_POST['contact_number']; ?>"
                                    placeholder="Enter Contact Number" />
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="small mb-1" for="school_id_number">School ID Number</label>
                           <input class="form-control py-3" id="school_id_number" type="text" name="school_id_number"
                              value="<?php if(isset($_POST['school_id_number'])) echo $_POST['school_id_number']; ?>"
                              placeholder="Enter School ID Number" />
                        </div>
                        <div class="form-group">
                           <label class="small mb-1" for="course_id">Course</label>
                           <select class="form-control" id="course_id" name="course_id">
                              <option selected hidden disabled>Select Course</option>
                              <?php
                                       $sql = "SELECT * FROM tbl_course";
                                       $stmt = $conn->prepare($sql);
                                       $stmt->execute();
                                       $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                       foreach($courses as $course) :
                                    ?>
                              <option value="<?php echo $course['course_id']; ?>">
                                 <?php echo $course['course_name']; ?>
                              </option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                        <div class="form-row">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label class="small mb-1" for="email">Email</label>
                                 <input class="form-control py-3" id="email" type="email" name="email"
                                    value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"
                                    aria-describedby="emailHelp" placeholder="Enter email address" />
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small" for="password">Password</label>
                                 <input class="form-control py-3" id="password" type="password" name="password"
                                    placeholder="Enter password" />
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="small" for="confirm_password">Confirm
                                    Password</label>
                                 <input class="form-control py-3" id="confirm_password" type="password"
                                    name="confirm_password" placeholder="Confirm password" />
                              </div>
                           </div>
                        </div>
                        <div class="form-group mb-0">
                           <button type="submit" name="sign_up" class="btn btn-primary btn-block">Create
                              Account</button>
                        </div>
                     </form>
                  </div>
                  <div class="card-footer text-center">
                     <div class="small"><a href="signin.php">Have an account? Go to signin</a></div>
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