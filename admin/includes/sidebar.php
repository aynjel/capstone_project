<div id="layoutSidenav_nav">
   <nav class="sidenav shadow-right sidenav-light">
      <div class="sidenav-menu">
         <div class="nav accordion" id="accordionSidenav">

            <?php if($curr_page == 'index.php') : ?>
            <a class="nav-link collapsed pt-4 active" href="index.php">
               <div class="nav-link-icon"><i data-feather="activity"></i></div>
               Dashboard
            </a>
            <?php else : ?>
            <a class="nav-link collapsed pt-4" href="index.php">
               <div class="nav-link-icon"><i data-feather="activity"></i></div>
               Dashboard
            </a>
            <?php endif; ?>

            <?php if($curr_page == 'organizations.php' || $curr_page == 'organization_create.php' || $curr_page == 'organization_view.php' || $curr_page == 'organization_edit.php') : ?>
            <a class="nav-link active" href="organizations.php">
               <div class="nav-link-icon"><i class="fas fa-building"></i></div>
               Organization
            </a>
            <?php else : ?>
            <a class="nav-link" href="organizations.php">
               <div class="nav-link-icon"><i class="fas fa-building"></i></div>
               Organization
            </a>
            <?php endif; ?>

            <?php if($curr_page == 'courses.php' || $curr_page == 'course_create.php' || $curr_page == 'course_view.php' || $curr_page == 'course_edit.php') : ?>
            <a class="nav-link active" href="courses.php">
               <div class="nav-link-icon"><i class="fas fa-school"></i></div>
               Course
            </a>
            <?php else : ?>
            <a class="nav-link" href="courses.php">
               <div class="nav-link-icon"><i class="fas fa-school"></i></div>
               Course
            </a>
            <?php endif; ?>

            <?php if($curr_page == 'enrollments.php' || $curr_page == 'enrollment_create.php' || $curr_page == 'enrollment_view.php' || $curr_page == 'enrollment_edit.php') : ?>
            <a class="nav-link active" href="enrollments.php">
               <div class="nav-link-icon"><i class="fas fa-graduation-cap"></i></div>
               Enrollment
            </a>
            <?php else : ?>
            <a class="nav-link" href="enrollments.php">
               <div class="nav-link-icon"><i class="fas fa-graduation-cap"></i></div>
               Enrollment
            </a>
            <?php endif; ?>

            <?php if($curr_page == 'coordinators.php' || $curr_page == 'coordinator_create.php' || $curr_page == 'coordinator_view.php' || $curr_page == 'coordinator_edit.php') : ?>
            <a class="nav-link active" href="coordinators.php">
               <div class="nav-link-icon"><i class="fas fa-user-tie"></i></div>
               Coordinator
            </a>
            <?php else : ?>
            <a class="nav-link" href="coordinators.php">
               <div class="nav-link-icon"><i class="fas fa-user-tie"></i></div>
               Coordinator
            </a>
            <?php endif; ?>

            <?php if($curr_page == 'students.php' || $curr_page == 'student_create.php' || $curr_page == 'student_view.php' || $curr_page == 'student_edit.php') : ?>
            <a class="nav-link active" href="students.php">
               <div class="nav-link-icon"><i class="fas fa-user-graduate"></i></div>
               Student
            </a>
            <?php else : ?>
            <a class="nav-link" href="students.php">
               <div class="nav-link-icon"><i class="fas fa-user-graduate"></i></div>
               Student
            </a>
            <?php endif; ?>

            <a class="nav-link" href="messages.php">
               <div class="nav-link-icon"><i class="fas fa-comment"></i></div>
               Message
            </a>
            <a class="nav-link" href="users.php">
               <div class="nav-link-icon"><i data-feather="users"></i></div>
               Users
            </a>
            <a class="nav-link collapsed" href="javascript:void(0);" data-toggle="collapse"
               data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
               <div class="nav-link-icon"><i data-feather="layout"></i></div>
               Reports
               <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" data-parent="#accordionSidenav">
               <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                  <a class="nav-link" href="reports_student.php">by Student</a>
                  <a class="nav-link" href="report_coordinator.php">by Coordinator</a>
               </nav>
            </div>
         </div>
      </div>

      <div class="sidenav-footer">
         <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle"><?php echo $nickname; ?></div>
            <div class="sidenav-footer-title"><?php echo $email; ?></div>
         </div>
      </div>

   </nav>
</div>