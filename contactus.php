<?php
include_once('dbcon.php');
include_once('send_contact.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$login_username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$visitor_name = '';

include(__DIR__ . "/layout/header.php");
include(__DIR__ . "/layout/sidebar.php");
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Contact Us - Hotel Paradise</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <section class="col-lg-8">
          <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body">

              <!-- Display Feedback Messages -->
              <?php if (isset($_GET['status'])): ?>
                <div class="alert 
                <?php
                if ($_GET['status'] == 'success') {
                  echo 'alert-success';
                } elseif ($_GET['status'] == 'error') {
                  echo 'alert-danger';
                } elseif ($_GET['status'] == 'empty') {
                  echo 'alert-warning';
                } elseif ($_GET['status'] == 'invalid_email' || $_GET['status'] == 'invalid_contact') {
                  echo 'alert-danger';
                }
                ?>"
                  role="alert">
                  <?php
                  if ($_GET['status'] == 'success') {
                    echo "Your message has been sent successfully!";
                  } elseif ($_GET['status'] == 'error') {
                    echo "Something went wrong. Please try again later.";
                  } elseif ($_GET['status'] == 'empty') {
                    echo "Please fill in all the fields.";
                  } elseif ($_GET['status'] == 'invalid_email') {
                    echo "Please enter a valid email address.";
                  } elseif ($_GET['status'] == 'invalid_contact') {
                    echo "Please enter a valid 10-digit mobile number.";
                  }
                  ?>
                </div>
              <?php endif; ?>


              <!-- Contact Header -->
              <div class="header-section text-center animate__animated animate__fadeIn">
                <h1 class="text-white">Get in Touch with Us</h1>
                <p class="lead text-white">Our team is ready to assist you. Reach out to us today!</p>
              </div>

              <!-- Contact Form Section -->
              <div class="contact-form-section animate__animated animate__fadeInUp">
                <form action="send_contact.php" method="POST" id="contactForm">
                  <div class="form-group mb-4">
                    <label for="name" class="form-label text-dark">Full Name</label>
                    <input type="text" class="form-control" id="visitor_name" name="visitor_name" placeholder="Your Full Name" required>
                  </div>
                  <div class="form-group mb-4">
                    <label for="email" class="form-label text-dark">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Your Email Address" required>
                  </div>
                  <div class="form-group mb-4">
                    <label for="mobile" class="form-label text-dark">Mobile Number</label>
                    <input type="number" class="form-control" id="contact" name="contact" placeholder="Your Mobile Number" required>
                  </div>
                  <div class="form-group mb-4">
                    <label for="subject" class="form-label text-dark">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                  </div>
                  <div class="form-group mb-4">
                    <label for="message" class="form-label text-dark">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="4" placeholder="Your Message" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary btn-lg btn-block">Send Message</button>
                </form>
              </div>

              <!-- Location Section -->
              <div class="location-section text-center mt-5">
                <h2 class="text-primary">Our Location</h2>
                <p class="text-muted mb-4">Visit us at our central location. We're happy to help!</p>
                <div id="map" style="height: 400px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                  <span class="text-muted">Map Placeholder</span>
                </div>
              </div>

            </div><!-- /.card-body -->
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php
include(__DIR__ . "/layout/footer.php");
?>

<!-- Link to Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<!-- Google Maps API for embedding location -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>

<!-- Form Validation Script -->
<script>
  document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let visitor_name = document.getElementById('visitor_name').value;
    let email = document.getElementById('email').value;
    let contact = document.getElementById('contact').value;
    let subject = document.getElementById('subject').value;
    let message = document.getElementById('message').value;

    if (visitor_name === '' || email === '' || contact === '' || subject === '' || message === '') {
      alert("Please fill all fields.");
      return false;
    }

    let contactRegex = /^[6-9]\d{9}$/;
    if (contact.length !== 10 || !contactRegex.test(contact)) {
      alert("Please enter a valid 10-digit mobile number.");
      return false;
    }
    this.submit();
  });
</script>

<!-- Custom Styles -->
<style>
  body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f9;
    color: #333;
  }

  .header-section {
    background-color: #007bff;
    color: white;
    padding: 60px 20px;
    border-radius: 10px;
    margin-bottom: 30px;
  }

  .header-section h1 {
    font-size: 2.5rem;
    font-weight: 600;
  }

  .header-section p {
    font-size: 1.1rem;
    font-weight: 400;
  }

  .contact-form-section {
    background-color: #ffffff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-label {
    font-size: 1rem;
    font-weight: 500;
  }

  .form-control {
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 12px;
    font-size: 1rem;
    transition: border 0.3s;
  }

  .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  }

  .btn-primary {
    background-color: #007bff;
    color: white;
    padding: 15px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    border: none;
  }

  .btn-primary:hover {
    background-color: #0056b3;
  }

  .location-section {
    margin-top: 50px;
  }

  #map {
    width: 100%;
    height: 100%;
    border-radius: 10px;
  }

  @media (max-width: 768px) {
    .content-wrapper {
      padding: 15px;
    }
  }
</style>

<!-- Google Maps Initialization -->
<script>
  function initMap() {
    var location = {
      lat: 21.1702,
      lng: 72.8311
    }; // Surat coordinates
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 15,
      center: location
    });
    var marker = new google.maps.Marker({
      position: location,
      map: map
    });
  }
</script>