<?php
include_once('dbcon.php');
include("./layout/header.php");
include("./layout/sidebar.php");
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/dist/css/aboutus.css">

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <!-- <h1 class="m-0 text-center">About Us - Hotel Paradise</h1> -->
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <section class="col-lg-8">
          <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body">
              <!-- <h4 class="m-0 text-center">About Us - Hotel Paradise</h4> -->
              <!-- Parallax Header Section -->
              <section class="parallax-header mb-5">
                <div class="overlay">
                  <div class="container text-center text-white">
                    <h1 class="display-4 text-primary font-weight-bold">Welcome to Hotel Paradise</h1>
                    <p class="lead mt-3 font-bold" style="font-size: 1.5rem; color: black;">Where Luxury Meets Comfort</p>
                  </div>
                </div>
              </section>

              <!-- About Us Section -->
              <section class="luxury-card container mb-5">
                <div class="row">
                  <div class="col-lg-6">
                    <img src="<?= BASE_URL ?>/dist/img/paradise_pic.jpg" alt="Hotel Image" class="img-fluid rounded" style="width: 100%; height: 300px; object-fit: cover;">
                  </div>
                  <div class="col-lg-6">
                    <h2 class="elegant-title">About Us</h2>
                    <p class="text-muted">
                      Nestled in the heart of the city, Hotel Paradise is a sanctuary of elegance and luxury. Our mission is to
                      provide an unforgettable experience for our guests, blending world-class hospitality with the
                      sophistication of a five-star resort. Whether you're here for business or leisure, our hotel promises an
                      unparalleled stay.
                    </p>
                    <p class="text-muted">
                      Boasting exquisite architecture, opulent interiors, and exceptional service, we have been awarded as one
                      of the top luxury hotels in the region. Our dedication to creating unforgettable memories is what sets us
                      apart.
                    </p>
                  </div>
                </div>
              </section>

              <!-- Core Values Section -->
              <section class="container mb-5">
                <h3 class="section-title text-center">Our Core Values</h3>
                <div class="row text-center">
                  <div class="col-md-4">
                    <i class="facility-icon fas fa-gem"></i>
                    <h5 class="mt-3">Excellence</h5>
                    <p class="text-muted">Delivering impeccable service and creating a flawless experience for every guest.</p>
                  </div>
                  <div class="col-md-4">
                    <i class="facility-icon fas fa-heart"></i>
                    <h5 class="mt-3">Warmth</h5>
                    <p class="text-muted">Making every guest feel at home with our personalized and caring hospitality.</p>
                  </div>
                  <div class="col-md-4">
                    <i class="facility-icon fas fa-leaf"></i>
                    <h5 class="mt-3">Sustainability</h5>
                    <p class="text-muted">Adopting eco-friendly practices to protect our planet for future generations.</p>
                  </div>
                </div>
              </section>

              <!-- Facilities Section -->
              <section class="container mb-5">
                <h3 class="section-title text-center">Unmatched Facilities</h3>
                <div class="row">
                  <div class="col-md-4">
                    <img src="<?= BASE_URL ?>/dist/img/spa.jpg" alt="Spa" class="img-fluid rounded" style="width: 300px; height: 200px; object-fit: cover;">
                    <h5 class="mt-3">Luxury Spa</h5>
                    <p class="text-muted">Indulge in rejuvenating treatments at our world-class spa, designed to relax your
                      mind, body, and soul.</p>
                  </div>
                  <div class="col-md-4">
                    <img src="<?= BASE_URL ?>/dist/img/pool.jpg" alt="Pool" class="img-fluid rounded" style="width: 300px; height: 200px; object-fit: cover;">
                    <h5 class="mt-3">Infinity Pool</h5>
                    <p class="text-muted">Take a refreshing dip in our rooftop infinity pool, offering panoramic views of the
                      skyline.</p>
                  </div>
                  <div class="col-md-4">
                    <img src="<?= BASE_URL ?>/dist/img/dining2.jpg" alt="Dining" class="img-fluid rounded" style="width: 300px; height: 200px; object-fit: cover;">
                    <h5 class="mt-3">Fine Dining</h5>
                    <p class="text-muted">Savor gourmet cuisines crafted by our award-winning chefs in a luxurious ambiance.</p>
                  </div>
                </div>
              </section>

              <!-- Meet Our Team Section -->
              <section class="team-section container mb-5">
                <h3 class="section-title text-center">Meet Our Team</h3>
                <div class="row">
                  <div class="col-md-4">
                    <img src="<?= BASE_URL ?>/dist/img/founder.jpg" alt="Founder" class="img-fluid rounded" style="width: 300px; height: 300px; object-fit: cover;">
                    <h5 class="team-name mt-3">Jay Goyani</h5>
                    <p class="team-role">Founder & CEO</p>
                    <p class="text-muted">Jay is the visionary behind Hotel Paradise, bringing together the finest
                      ingredients to create a luxury destination.</p>
                  </div>
                  <div class="col-md-4">
                    <img src="<?= BASE_URL ?>/dist/img/chef.jpg" alt="Head Chef" class="img-fluid rounded" style="width: 300px; height: 300px; object-fit: cover;">
                    <h5 class="team-name mt-3">Jane Smith</h5>
                    <p class="team-role">Head Chef</p>
                    <p class="text-muted">Jane is the head chef at Hotel Paradise, specializing in creating
                      gourmet dishes that are both delicious and healthy.</p>
                  </div>
                  <div class="col-md-4">
                    <img src="<?= BASE_URL ?>/dist/img/manager.jpg" alt="Guest Relations Manager" class="img-fluid rounded" style="width: 300px; height: 300px; object-fit: cover;">
                    <h5 class="team-name mt-3">Sarah Johnson</h5>
                    <p class="team-role">Guest Relations Manager</p>
                    <p class="text-muted">Sarah is responsible for managing the hotel's guest relations, ensuring that
                      every guest has a positive and memorable experience.</p>
                  </div>
                  <div class="col-md-4">
                    <img src="<?= BASE_URL ?>/dist/img/emily.jpg" alt="Front Desk Manager" class="img-fluid rounded" style="width: 300px; height: 300px; object-fit: cover;">
                    <h5 class="team-name mt-3">Emily Brown</h5>
                    <p class="team-role">Front Desk Manager</p>
                    <p class="text-muted">Emily is responsible for managing the front desk operations, ensuring that
                      every guest has a pleasant and efficient check-in experience.</p>
                  </div>
                  <div class="col-md-4">
                    <img src="<?= BASE_URL ?>/dist/img/rebecca.jpg" alt="Marketing Manager" class="img-fluid rounded" style="width: 300px; height: 300px; object-fit: cover;">
                    <h5 class="team-name mt-3">Rebecca Thompson</h5>
                    <p class="team-role">Marketing Manager</p>
                    <p class="text-muted">Rebecca is responsible for developing and implementing marketing campaigns
                      to promote the hotel and its services.</p>
                  </div>
                  <div class="col-md-4">
                    <img src="<?= BASE_URL ?>/dist/img/brian.jpg" alt="Housekeeping Manager" class="img-fluid rounded" style="width: 300px; height: 300px; object-fit: cover;">
                    <h5 class="team-name mt-3">Brian Davis</h5>
                    <p class="team-role">General Manager</p>
                    <p class="text-muted">Brian is responsible for managing the hotel's operations, ensuring that every
                      aspect of the business is running smoothly.</p>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </section>
      </div>
    </div>
</div>

<?php
// Including the footer
include("./layout/footer.php");
?>