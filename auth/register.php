<?php
require_once("../dbcon.php");

if (validateSession()) {
  header("Location: " . BASE_URL . "/dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= BASE_URL ?>/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"> <!-- Toastify CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/dist/css/adminlte.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script> <!-- Toastify JS -->
  <style>
    body,
    .register-box {
      font-family: 'Source Sans Pro', sans-serif;
    }
  </style>
</head>

<body>
  <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); height: auto; width: 500px;" class="register-box">
    <div class="register-logo">
      <div style="text-align: center;"><a href="#">Hotel <b>Paradise</b></a></div>
    </div>

    <div class="card">
      <div class="card-body register-card-body">
        <h6 class="login-box-msg">Create an account</h6>
        <form id="registrationForm">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Full name" name="username" id="username" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="email" id="email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Confirm Password" name="confirmPassword" id="confirmPassword" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="number" class="form-control" placeholder="Contact Number" name="contact" id="contact" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-phone"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <select class="form-control" name="gender" id="gender" required>
              <option value="" selected disabled>Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                <label for="agreeTerms"> I agree to the <a href="<?= BASE_URL ?>/auth/term.php">terms</a></label>
              </div>
            </div>
            <div class="col-8">
              <p style="margin-top: 10px;" class="mb-0">
                <a href="<?= BASE_URL ?>/auth/login.php" class="text-center">Already have an account? Login</a>
              </p>
            </div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    var baseURL = "<?php echo BASE_URL; ?>";

    $(document).ready(function() {
      $('#registrationForm').submit(function(e) {
        e.preventDefault();

        var username = $('#username').val().trim();
        var email = $('#email').val().trim();
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();
        var contact = $('#contact').val().trim();
        var gender = $('#gender').val();
        var agreeTerms = $('#agreeTerms').is(':checked');

        if (username === "" || email === "" || password === "" || confirmPassword === "" || contact === "" || gender === null) {
          alert("Please fill in all fields.");
          return;
        }

        if (!agreeTerms) {
          alert("Please agree to the terms and conditions.");
          return;
        }

        if (password !== confirmPassword) {
          alert("Passwords do not match.");
          return;
        }

        if (contact.length !== 10) {
          alert("Please enter a valid 10-digit contact number.");
          return;
        }

        var formData = $(this).serialize();
        formData += '&action=register';
        submitRegistrationForm(formData);
      });
    });

    function submitRegistrationForm(formData) {
      $.ajax({
        url: "backend.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
          console.log(response);
          if (response.status === "success") {
            Toastify({
              text: response.message,
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
            }).showToast();

            setTimeout(() => {
              window.location.href = baseURL + "/dashboard.php?nocache=" + new Date().getTime();
            }, 1000);
          } else {
            Toastify({
              text: response.message,
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"
            }).showToast();
          }
        },
        error: function() {
          Toastify({
            text: "An error occurred. Please try again.",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"
          }).showToast();
        }
      });
    }
  </script>
</body>

</html>