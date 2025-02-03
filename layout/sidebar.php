<?php
require_once(__DIR__ . "/../dbcon.php");

// Set current page for active menu highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= BASE_URL ?>/dashboard.php" class="brand-link">
    <img
      src="<?= BASE_URL ?>/dist/img/logo.jpeg"
      alt="AdminLTE Logo"
      class="brand-image img-circle elevation-3"
      style="opacity: 0.8" />
    <span class="brand-text font-weight-light">Hotel <b>Paradise</b></span>
  </a>

  <!-- Sidebar -->
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
      <img
        src="<?= BASE_URL ?>/dist/img/user2-160x160.jpg"
        class="img-circle elevation-2"
        alt="User Image" />
    </div>
    <div class="info">
      <a href="#" class="d-block" style="color: white;"><?php echo $_SESSION['username']; ?></a>
    </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Common Menu (Visible to All Roles) -->
      <li class="nav-item">
        <a href="<?= BASE_URL ?>/dashboard.php" class="nav-link <?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>Dashboard</p>
        </a>
      </li>

      <!-- Menu for User -->
      <?php if ($_SESSION['role'] == 'user') { ?>
        <li class="nav-item">
          <a href="<?= USER_URL ?>/book_room.php" class="nav-link <?php echo ($currentPage == 'book_room.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-calendar-check"></i>
            <p>Book Room</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= USER_URL ?>/room_history.php" class="nav-link <?php echo ($currentPage == 'room_history.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-history"></i>
            <p>Room History</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= USER_URL ?>/book_eventhall.php" class="nav-link <?php echo ($currentPage == 'book_eventhall.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-solid fa-hotel"></i>
            <p>Book Event Hall</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= USER_URL ?>/eventhall_history.php" class="nav-link <?php echo ($currentPage == 'eventhall_history.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-history"></i>
            <p>Event Hall History</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= USER_URL ?>/view_messages.php" class="nav-link <?php echo ($currentPage == 'view_messages.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-envelope"></i>
            <p>View Messages</p>
          </a>
        </li>
      <?php } ?>


      <!-- Menu for Admin -->
      <?php if ($_SESSION['role'] == 'admin') { ?>
        <li class="nav-item">
          <a href="<?= ADMIN_URL ?>/add_room.php" class="nav-link <?php echo ($currentPage == 'add_room.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-solid fa-bed"></i>
            <p>add Room</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= ADMIN_URL ?>/room_list.php" class="nav-link <?php echo ($currentPage == 'room_list.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-solid fa-list-ol"></i>
            <p>Room List</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= ADMIN_URL ?>/roombooking_details.php" class="nav-link <?php echo ($currentPage == 'roombooking_details.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-regular fa-calendar-check"></i>
            <p>Room Booking Details</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= ADMIN_URL ?>/add_eventhall.php" class="nav-link <?php echo ($currentPage == 'add_eventhall.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-solid fa-holly-berry"></i>
            <p>Add Event Hall</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= ADMIN_URL ?>/eventhall_list.php" class="nav-link <?php echo ($currentPage == 'eventhall_list.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-solid fa-list-ul"></i>
            <p>Event Hall List</p>
          </a>
        </li>
        <li class="nav-item">
          <a href=" <?= ADMIN_URL ?>/hallbooking_details.php" class="nav-link <?php echo ($currentPage == 'hallbooking_details.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-regular fa-calendar-check"></i>
            <p>Hall Booking Details</p>
          </a>
        </li>
        <li class="nav-item">
          <a href=" <?= ADMIN_URL ?>/user_list.php" class="nav-link <?php echo ($currentPage == 'user_list.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-regular fa-user"></i>
            <p>User List</p>
          </a>
        </li>
        <li class="nav-item">
          <a href=" <?= ADMIN_URL ?>/messages.php" class="nav-link <?php echo ($currentPage == 'messages.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-envelope"></i>
            <p>Messages</p>
          </a>
        </li>
      <?php } ?>

      <!-- Menu Visible to All -->
      <!-- <li class="nav-item">
        <a href="profile.php" class="nav-link <?php echo ($currentPage == 'profile.php') ? 'active' : ''; ?>">
          <i class="nav-icon fas fa-user"></i>
          <p>Profile</p>
        </a>  
      </li> -->

      <!-- Change Password Menu -->
      <li class="nav-item">
        <a href="<?= BASE_URL ?>/auth/change_password.php" class="nav-link <?php echo ($currentPage == 'change_password.php') ? 'active' : ''; ?>">
          <i class="nav-icon fas fa-key"></i>
          <p>Change Password</p>
        </a>
      </li>
      <!-- Logout Menu -->
      <li class="nav-item">
        <a href="/auth/logout.php" class="nav-link">
          <i class="nav-icon fas fa-sign-out-alt"></i>
          <p>Logout</p>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</aside>