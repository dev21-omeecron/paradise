<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

$message = "";

$role = $_SESSION['role'];
include(__DIR__ . '/../layout/header.php');
include(__DIR__ . '/../layout/sidebar.php');
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-sm-12">
          <h1 class="text-xl font-bold">Change Password (<?= ucfirst($role) ?>)</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="card shadow-lg rounded-lg">
            <div class="card-body">
              <?php if (!empty($message)) echo $message; ?>
              <form id="changePasswordForm" class="space-y-4">
                <div class="form-group">
                  <label for="currentPassword" class="font-medium">Current Password</label>
                  <input type="password" class="form-control p-3 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" id="currentPassword" name="currentPassword" placeholder="Enter Current Password" required>
                </div>
                <div class="form-group">
                  <label for="newPassword" class="font-medium">New Password</label>
                  <input type="password" class="form-control p-3 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" id="newPassword" name="newPassword" placeholder="Enter New Password" required>
                </div>
                <div class="form-group">
                  <label for="confirmPassword" class="font-medium">Confirm Password</label>
                  <input type="password" class="form-control p-3 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                </div>
                <button type="button" id="changePasswordBtn" class="btn btn-primary w-full py-2 px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition duration-300 ease-in-out">Change Password</button>
              </form>

              <div id="responseMessage" class="mt-4"></div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  $(document).ready(function() {
    $('#changePasswordBtn').click(function(e) {
      e.preventDefault();

      var currentPassword = $('#currentPassword').val();
      var newPassword = $('#newPassword').val();
      var confirmPassword = $('#confirmPassword').val();

      if (currentPassword === "" || newPassword === "" || confirmPassword === "") {
        $('#responseMessage').html('<div class="alert alert-danger p-3 rounded-lg">Please enter all fields.</div>');
        return;
      }

      if (newPassword !== confirmPassword) {
        $('#responseMessage').html('<div class="alert alert-danger p-3 rounded-lg">New Password and Confirm Password do not match.</div>');
        return;
      }

      if (newPassword.length < 8) {
        $('#responseMessage').html('<div class="alert alert-danger p-3 rounded-lg">Password must be at least 8 characters long.</div>');
        return;
      }

      $.ajax({
        url: './backend.php',
        method: 'POST',
        data: $('#changePasswordForm').serialize() + '&action=change_password',
        dataType: 'json',
        success: function(response) {
          if (response.status === "success") {
            $('#responseMessage').html('<div class="alert alert-success p-3 rounded-lg">' + response.message + '</div>');
            $('#changePasswordForm')[0].reset();

            setTimeout(function() {
              window.location.href = '<?= BASE_URL ?>/auth/login.php';
            }, 2000);
          } else {
            $('#responseMessage').html('<div class="alert alert-danger p-3 rounded-lg">' + response.message + '</div>');
          }
        },
        error: function(xhr, status, error) {
          $('#responseMessage').html('<div class="alert alert-danger p-3 rounded-lg">An error occurred. Please try again.</div>');
          console.error('Error:', error);
        }
      });
    });
  });
</script>

<?php
include(__DIR__ . '/../layout/footer.php');
?>

<style>
  .form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
  }

  .btn-primary:hover {
    background-color: #2563eb;
    border-color: #2563eb;
  }
</style>