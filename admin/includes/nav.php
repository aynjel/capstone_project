<?php
// if cookie is set, get the value
if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
   $email = $_COOKIE['email'];
   $password = $_COOKIE['password'];

   $sql = "SELECT * FROM tbl_user WHERE email = :email";
   $stmt = $conn->prepare($sql);
   $stmt->execute([
      ':email' => $email
   ]);
   $user_cookie = $stmt->fetch(PDO::FETCH_ASSOC);
   if ($user_cookie['password'] == $password) {
      $_SESSION['user_id'] = $user_cookie['user_id'];
      $_SESSION['user_role'] = $user_cookie['user_role'];
      $_SESSION['user_status'] = $user_cookie['user_status'];
      $_SESSION['email'] = $user_cookie['email'];
      $_SESSION['nickname'] = $user_cookie['nickname'];
   }else{
      $_SESSION['error'] = 'Invalid login credentials';
   }

   $user_id = $_SESSION['user_id'];
   $user_role = $_SESSION['user_role'];
   $user_status = $_SESSION['user_status'];
   $email = $_SESSION['email'];
   $nickname = $_SESSION['nickname'];
}
?>
<nav class="topnav navbar navbar-expand shadow navbar-light bg-white" id="sidenavAccordion">
   <a class="navbar-brand d-none d-sm-block" href="index.php">
      Admin Panel
   </a>
   <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#">
      <i data-feather="menu"></i>
   </button>

   <ul class="navbar-nav align-items-center ml-auto">

      <!--User Registration + New Comment Notification-->
      <li class="nav-item dropdown no-caret mr-3 dropdown-notifications">
         <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts" href="#" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i data-feather="bell"></i>
            <span class="badge badge-red">2</span>
         </a>

         <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up"
            aria-labelledby="navbarDropdownAlerts">
            <h6 class="dropdown-header dropdown-notifications-header">
               <i class="mr-2" data-feather="bell"></i>
               Notification
            </h6>

            <a class="dropdown-item dropdown-notifications-item" href="#!">
               <div class="dropdown-notifications-item-icon bg-warning"><i data-feather="activity"></i></div>
               <div class="dropdown-notifications-item-content">

                  <div class="dropdown-notifications-item-content-details">
                     December 29, 2019
                  </div>
                  <div class="dropdown-notifications-item-content-text">
                     This is an alert message. It&apos;s nothing serious, but it requires your attention.
                  </div>
               </div>
            </a>

            <a class="dropdown-item dropdown-notifications-item" href="#!">
               <div class="dropdown-notifications-item-icon bg-warning"><i data-feather="activity"></i></div>
               <div class="dropdown-notifications-item-content">

                  <div class="dropdown-notifications-item-content-details">
                     December 29, 2019
                  </div>
                  <div class="dropdown-notifications-item-content-text">
                     This is an alert message. It&apos;s nothing serious, but it requires your attention.
                  </div>
               </div>
            </a>

            <a class="dropdown-item dropdown-notifications-footer" href="#">
               View All Alerts
            </a>
         </div>
      </li>
      <!--User Registration + New Comment Notification-->

      <!--Message Notification-->
      <li class="nav-item dropdown no-caret mr-3 dropdown-notifications">
         <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownMessages" href="#" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i data-feather="mail"></i>
            <span class="badge badge-red">1</span>
         </a>
         <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up"
            aria-labelledby="navbarDropdownMessages">
            <h6 class="dropdown-header dropdown-notifications-header">
               <i class="mr-2" data-feather="mail"></i>
               Message Notification
            </h6>

            <a class="dropdown-item dropdown-notifications-item" href="#"><img class="dropdown-notifications-item-img"
                  src="./assets/img/mdabarik.jpg" />
               <div class="dropdown-notifications-item-content">
                  <div class="dropdown-notifications-item-content-text">
                     Lorem ipsum dolor sit amet, consectetur adipisicing.
                  </div>
                  <div class="dropdown-notifications-item-content-details">
                     Md. A. Barik &#xB7; 58m
                  </div>
               </div>
            </a>

            <a class="dropdown-item dropdown-notifications-footer" href="messages">
               Read All Messages
            </a>
         </div>
      </li>
      <!--Message Notification-->

      <li class="nav-item dropdown no-caret mr-3 dropdown-user">
         <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage"
            href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user"></i>
         </a>
         <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up"
            aria-labelledby="navbarDropdownUserImage">
            <h6 class="dropdown-header d-flex align-items-center">
               <?php
               // Get the user details from the database
               ?>
               <div class="dropdown-user-details">
                  <div class="dropdown-user-details-name">
                     <?php echo $nickname; ?>
                  </div>
                  <div class="dropdown-user-details-email">
                     <?php echo $email; ?>
                  </div>
               </div>
            </h6>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="profile.php">
               <div class="dropdown-item-icon">
                  <i data-feather="settings"></i>
               </div>
               Profile
            </a>
            <a class="dropdown-item" href="logout.php" onclick="return confirm('Are you sure?')">
               <div class="dropdown-item-icon">
                  <i data-feather="log-out"></i>
               </div>
               Logout
            </a>
         </div>
      </li>
   </ul>
</nav>