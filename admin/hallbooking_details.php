<?php

require_once("../dbcon.php");

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit;
}

include("../layout/header.php");
include("../layout/sidebar.php");

require_once 'hallbooking_detailsBackend.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0 text-center">Booked Event Hall History</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-lg-12">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h3 class="card-title">Booking Details</h3>
            </div>
            <div class="card-body">
              <!-- Filter Form -->
              <form action="hallbooking_detailsBackend.php" method="GET">
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="hall_type">Eventhall Type</label>
                    <select name="hall_type" id="hall_type" class="form-control">
                      <option value="">Select Eventhall Type</option>
                      <?php
                      $types = ['Banquet Hall', 'Function Hall', 'Conference Hall', 'Meeting Hall', 'Party Hall', 'Rooftop Venue Hall', 'Wedding Hall'];
                      foreach ($types as $type) {
                        $selected = (isset($_GET['hall_type']) && $_GET['hall_type'] == $type) ? 'selected' : '';
                        echo "<option value='$type' $selected>$type</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="hall_number">Eventhall Number</label>
                    <select name="hall_number" id="hall_number" class="form-control">
                      <option value="">Select Hall Number</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">Filter</button>
                  </div>
                </div>
              </form>
              <hr>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead class="thead-dark">
                    <tr>
                      <th>Booking ID</th>
                      <th>User ID</th>
                      <th>Hall ID</th>
                      <th>Username</th>
                      <th>Email</th>
                      <th>Hall Type</th>
                      <th>Hall Number</th>
                      <th>Capacity</th>
                      <th>Price per Hour</th>
                      <th>Description</th>
                      <th>Images</th>
                      <th>Check In</th>
                      <th>Check Out</th>
                      <th>Total Price</th>
                      <th>Booking Status</th>
                      <th>Payment Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($hall_bookings)) : ?>
                      <?php foreach ($hall_bookings as $booking) : ?>
                        <tr>
                          <td><strong><?= htmlspecialchars($booking['booking_id']) ?></strong></td>
                          <td><?= htmlspecialchars($booking['user_id']) ?></td>
                          <td><?= htmlspecialchars($booking['hall_id']) ?></td>
                          <td><?= htmlspecialchars($booking['username']) ?></td>
                          <td><?= htmlspecialchars($booking['email']) ?></td>
                          <td><?= htmlspecialchars($booking['hall_type']) ?></td>
                          <td><?= htmlspecialchars($booking['hall_number']) ?></td>
                          <td><?= htmlspecialchars($booking['capacity']) ?></td>
                          <td>₹<?= htmlspecialchars($booking['price_per_hour'], 2) ?></td>
                          <td>
                            <button class="btn btn-primary btn-sm view-description"
                              data-booking-id="<?= htmlspecialchars($booking['booking_id']) ?>"
                              data-user-id="<?= htmlspecialchars($booking['user_id']) ?>"
                              data-hall-id="<?= htmlspecialchars($booking['hall_id']) ?>"
                              data-username="<?= htmlspecialchars($booking['username']) ?>"
                              data-email="<?= htmlspecialchars($booking['email']) ?>"
                              data-hall-type="<?= htmlspecialchars($booking['hall_type']) ?>"
                              data-hall-number="<?= htmlspecialchars($booking['hall_number']) ?>"
                              data-capacity="<?= htmlspecialchars($booking['capacity']) ?>"
                              data-price="<?= number_format($booking['price_per_hour'], 2) ?>"
                              data-checkin="<?= htmlspecialchars(date('d M Y, h:i A', strtotime($booking['check_in']))) ?>"
                              data-checkout="<?= htmlspecialchars(date('d M Y, h:i A', strtotime($booking['check_out']))) ?>"
                              data-total="<?= htmlspecialchars(number_format($booking['total_price'], 2)) ?>"
                              data-description="<?= htmlspecialchars($booking['description']) ?>">
                              View
                            </button>
                          </td>
                          <td>
                            <?php
                            $images = json_decode($booking['images'], true);
                            if (is_array($images)) {
                              foreach ($images as $image) {
                                echo "<img src='../uploads/halls/$image' alt='Hall Image' width='100' height='100' class='img-thumbnail' style='margin-right:10px;'>";
                              }
                            } else {
                              echo "No images available.";
                            }
                            ?>
                          </td>
                          <td><?= htmlspecialchars(date("d M Y, h:i A", strtotime($booking['check_in']))) ?></td>
                          <td><?= htmlspecialchars(date("d M Y, h:i A", strtotime($booking['check_out']))) ?></td>
                          <td><strong>₹<?= number_format($booking['total_price'], 2) ?></strong></td>
                          <td>
                            <?php
                            if ($booking['booking_status'] === 'confirmed') {
                              echo "<span class='badge badge-success'>Confirmed</span>";
                            } elseif ($booking['booking_status'] === 'pending') {
                              echo "<span class='badge badge-warning'>Pending</span>";
                            } else {
                              echo "<span class='badge badge-danger'>Cancelled</span>";
                            }
                            ?>
                          </td>
                          <td>
                            <?php
                            if ($booking['payment_status'] === 'paid') {
                              echo "<span class='badge badge-success'>Paid</span>";
                            } elseif ($booking['payment_status'] === 'pending') {
                              echo "<span class='badge badge-warning'>Pending</span>";
                            } else {
                              echo "<span class='badge badge-danger'>Unpaid</span>";
                            }
                            ?>
                          </td>
                          <td>
                            <a href="hallbooking_detailsBackend.php?delete=<?= $booking['booking_id'] ?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to delete this booking?')">
                              <i class="fas fa-trash"></i> Delete
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <tr>
                        <td colspan="16" class="text-center text-muted">No bookings found.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php include("../layout/footer.php"); ?>

<!-- Modal for Dynamic Details -->
<div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="descriptionModalLabel">Booking Details</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Booking ID:</strong> <span id="modal-booking-id"></span></p>
              <p><strong>User ID:</strong> <span id="modal-user-id"></span></p>
              <p><strong>Hall ID:</strong> <span id="modal-hall-id"></span></p>
              <p><strong>Username:</strong> <span id="modal-username"></span></p>
              <p><strong>Email:</strong> <span id="modal-email"></span></p>
              <p><strong>Hall Type:</strong> <span id="modal-hall-type"></span></p>
              <p><strong>Hall Number:</strong> <span id="modal-hall-number"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>Capacity:</strong><span id="modal-capacity"></span></p>
              <p><strong>Price per hour:</strong> ₹<span id="modal-price"></span></p>
              <p><strong>Check In:</strong> <span id="modal-checkin"></span></p>
              <p><strong>Check Out:</strong> <span id="modal-checkout"></span></p>
              <p><strong>Total Price:</strong> ₹<span id="modal-total"></span></p>
              <p><strong>Description:</strong> <span id="modal-description"></span></p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-description');
    viewButtons.forEach(button => {
      button.addEventListener('click', function() {
        const bookingId = this.getAttribute('data-booking-id');
        const userId = this.getAttribute('data-user-id');
        const hallId = this.getAttribute('data-hall-id');
        const username = this.getAttribute('data-username');
        const email = this.getAttribute('data-email');
        const hallType = this.getAttribute('data-hall-type');
        const hallNumber = this.getAttribute('data-hall-number');
        const capacity = this.getAttribute('data-capacity');
        const price = this.getAttribute('data-price');
        const checkIn = this.getAttribute('data-checkin');
        const checkOut = this.getAttribute('data-checkout');
        const total = this.getAttribute('data-total');
        const description = this.getAttribute('data-description');

        document.getElementById('modal-booking-id').textContent = bookingId;
        document.getElementById('modal-user-id').textContent = userId;
        document.getElementById('modal-hall-id').textContent = hallId;
        document.getElementById('modal-username').textContent = username;
        document.getElementById('modal-email').textContent = email;
        document.getElementById('modal-hall-type').textContent = hallType;
        document.getElementById('modal-hall-number').textContent = hallNumber;
        document.getElementById('modal-capacity').textContent = capacity;
        document.getElementById('modal-price').textContent = price;
        document.getElementById('modal-checkin').textContent = checkIn;
        document.getElementById('modal-checkout').textContent = checkOut;
        document.getElementById('modal-total').textContent = total;
        document.getElementById('modal-description').textContent = description;

        $('#descriptionModal').modal('show');
      });
    });
  });

  $(document).ready(function() {
    $('#hall_type').change(function() {
      var hall_type = $(this).val();

      $('#hall_number').empty().append('<option value="">Select Hall Number</option>');

      if (hall_type) {
        $.ajax({
          url: 'hallbooking_detailsBackend.php',
          type: 'GET',
          data: {
            action: 'get_halls',
            hall_type: hall_type
          },
          success: function(response) {
            try {
              var data = JSON.parse(response);
              if (data.status === 'success') {
                data.halls.forEach(function(hall) {
                  $('#hall_number').append('<option value="' + hall.hall_number + '">' + hall.hall_number + '</option>');
                });
              } else {
                alert('No halls found for this type');
              }
            } catch (e) {
              console.error('Error parsing JSON response:', e);
            }
          },
          error: function(xhr, status, error) {
            console.error('Error fetching halls:', error);
          }
        });
      }
    });

    $('form').on('submit', function(e) {
      e.preventDefault();
      var hall_type = $('#hall_type').val();
      var hall_number = $('#hall_number').val();

      if (hall_type && hall_number) {
        $.ajax({
          url: 'hallbooking_detailsBackend.php',
          type: 'GET',
          data: {
            action: 'get_bookings',
            hall_type: hall_type,
            hall_number: hall_number
          },
          success: function(response) {
            try {
              var data = JSON.parse(response);
              if (data.status === 'booked') {
                $('table tbody').empty();

                // Add booking data to table
                data.data.forEach(function(booking) {
                  var row = `
                    <tr>
                      <td><strong>${booking.booking_id}</strong></td>
                      <td>${booking.user_id}</td>
                      <td>${booking.hall_id}</td>
                      <td>${booking.username}</td>
                      <td>${booking.email}</td>
                      <td>${booking.hall_type}</td>
                      <td>${booking.hall_number}</td>
                      <td>${booking.capacity}</td>
                      <td>${booking.price_per_hour}</td>
                      <td>
                        <button class="btn btn-primary btn-sm view-description" data-booking-id="${booking.booking_id}" data-hall-id="${booking.hall_id}" data-description="${booking.description}" data-email="${booking.email}" data-hall-type="${booking.hall_type}" data-hall-number="${booking.hall_number}" data-price="${booking.price_per_hour}" data-checkin="${booking.check_in}" data-checkout="${booking.check_out}" data-total="${booking.total_price}">View</button>
                      </td>
                      <td>${renderImages(booking.images)}</td>
                      <td>${formatDate(booking.check_in)}</td>
                      <td>${formatDate(booking.check_out)}</td>
                      <td>${booking.total_price}</td>
                      <td>${getStatusBadge(booking.booking_status, 'booking')}</td>
                      <td>${getStatusBadge(booking.payment_status, 'payment')}</td>
                      <td>
                        <a href="hallbooking_detailsBackend.php?delete=${booking.booking_id}" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to delete this booking?')">
                          <i class="fas fa-trash"></i> Delete
                        </a>
                      </td>
                    </tr>
                  `;
                  $('table tbody').append(row);
                });
              } else {
                $('table tbody').html('<tr><td colspan="16" class="text-center text-muted">No bookings found for this hall.</td></tr>');
              }
            } catch (e) {
              console.error('Error parsing response:', e);
            }
          },
          error: function(xhr, status, error) {
            console.error('Error fetching booking details:', error);
          }
        });
      }
    });
  });

  // Helper functions
  function renderImages(images) {
    try {
      const imageArray = JSON.parse(images);
      if (Array.isArray(imageArray)) {
        return imageArray.map(image =>
          `<img src='../uploads/halls/${image}' alt='Hall Image' class='img-thumbnail' width='80' height='80' style='margin-right:10px;'>`
        ).join('');
      }
    } catch (e) {
      console.error('Error parsing images:', e);
    }
    return 'No images available.';
  }

  function formatDate(dateString) {
    return new Date(dateString).toLocaleString('en-IN', {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      hour12: true
    });
  }

  function getStatusBadge(status, type) {
    const statusClasses = {
      'confirmed': 'badge-success',
      'pending': 'badge-warning',
      'cancelled': 'badge-danger',
      'paid': 'badge-success',
      'unpaid': 'badge-danger'
    };
    const className = statusClasses[status.toLowerCase()] || 'badge-secondary';
    return `<span class="badge ${className}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
  }
</script>