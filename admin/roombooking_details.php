<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit;
}

include("../layout/header.php");
include("../layout/sidebar.php");

require_once 'roombooking_detailsBackend.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0 text-center">Booked Room History</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
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
              <form action="roombooking_detailsBackend.php" method="GET">
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="room_type">Room Type</label>
                    <select name="room_type" id="room_type" class="form-control">
                      <option value="">Select Room Type</option>
                      <?php
                      $types = ['Single Room', 'Double Room', 'Standard Room', 'Deluxe Room', 'Quadruple Room', 'Presidential Room'];
                      foreach ($types as $type) {
                        $selected = (isset($_GET['room_type']) && $_GET['room_type'] == $type) ? 'selected' : '';
                        echo "<option value='$type' $selected>$type</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="room_number">Room Number</label>
                    <select name="room_number" id="room_number" class="form-control">
                      <option value="">Select Room Number</option>
                      <!-- The available room numbers will be dynamically populated based on room type selection -->
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
                      <th>Room ID</th>
                      <th>Username</th>
                      <th>Email</th>
                      <th>Room Type</th>
                      <th>Room Number</th>
                      <th>Price</th>
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
                    <?php if (!empty($room_bookings)) : ?>
                      <?php foreach ($room_bookings as $booking) : ?>
                        <tr>
                          <td><strong><?= htmlspecialchars($booking['booking_id']) ?></strong></td>
                          <td><?= htmlspecialchars($booking['user_id']) ?></td>
                          <td><?= htmlspecialchars($booking['room_id']) ?></td>
                          <td><?= htmlspecialchars($booking['username']) ?></td>
                          <td><?= htmlspecialchars($booking['email']) ?></td>
                          <td><?= htmlspecialchars($booking['room_type']) ?></td>
                          <td><?= htmlspecialchars($booking['room_number']) ?></td>
                          <td>₹<?= number_format($booking['price'], 2) ?></td>
                          <td>
                            <button class="btn btn-primary btn-sm view-description"
                              data-booking-id="<?= $booking['booking_id'] ?>"
                              data-user-id="<?= $booking['user_id'] ?>"
                              data-room-id="<?= $booking['room_id'] ?>"
                              data-username="<?= htmlspecialchars($booking['username']) ?>"
                              data-description="<?= htmlspecialchars($booking['description']) ?>"
                              data-email="<?= htmlspecialchars($booking['email']) ?>"
                              data-room-type="<?= htmlspecialchars($booking['room_type']) ?>"
                              data-room-number="<?= htmlspecialchars($booking['room_number']) ?>"
                              data-price="<?= number_format($booking['price'], 2) ?>"
                              data-checkin="<?= htmlspecialchars($booking['check_in']) ?>"
                              data-checkout="<?= htmlspecialchars($booking['check_out']) ?>"
                              data-total="<?= number_format($booking['total_price'], 2) ?>">
                              View
                            </button>
                          </td>
                          <td>
                            <?php
                            $images = json_decode($booking['images'], true);
                            if (is_array($images)) {
                              foreach ($images as $image) {
                                echo "<img src='../uploads/rooms/$image' alt='Room Image' class='img-thumbnail' width='80' height='80' style='margin-right:10px;'>";
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
                            <a href="roombooking_detailsBackend.php?delete=<?= $booking['booking_id'] ?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to delete this booking?')">
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

            <!-- Modal -->
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
                          <p><strong>Room ID:</strong> <span id="modal-room-id"></span></p>
                          <p><strong>User ID:</strong> <span id="modal-user-id"></span></p>
                          <p><strong>Username:</strong> <span id="modal-username"></span></p>
                          <p><strong>Email:</strong> <span id="modal-email"></span></p>
                          <p><strong>Room Type:</strong> <span id="modal-room-type"></span></p>
                        </div>
                        <div class="col-md-6">
                          <p><strong>Room Number:</strong> <span id="modal-room-number"></span></p>
                          <p><strong>Price:</strong> ₹<span id="modal-price"></span></p>
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
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php include("../layout/footer.php"); ?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-description');
    viewButtons.forEach(button => {
      button.addEventListener('click', function() {
        const description = this.getAttribute('data-description');
        const bookingId = this.getAttribute('data-booking-id');
        const roomId = this.getAttribute('data-room-id');
        const userId = this.getAttribute('data-user-id');
        const username = this.getAttribute('data-username');
        const email = this.getAttribute('data-email');
        const roomType = this.getAttribute('data-room-type');
        const roomNumber = this.getAttribute('data-room-number');
        const price = this.getAttribute('data-price');
        const checkIn = this.getAttribute('data-checkin');
        const checkOut = this.getAttribute('data-checkout');
        const total = this.getAttribute('data-total');

        document.getElementById('modal-description').textContent = description;
        document.getElementById('modal-booking-id').textContent = bookingId;
        document.getElementById('modal-room-id').textContent = roomId;
        document.getElementById('modal-user-id').textContent = userId;
        document.getElementById('modal-username').textContent = username;
        document.getElementById('modal-email').textContent = email;
        document.getElementById('modal-room-type').textContent = roomType;
        document.getElementById('modal-room-number').textContent = roomNumber;
        document.getElementById('modal-price').textContent = price;
        document.getElementById('modal-checkin').textContent = checkIn;
        document.getElementById('modal-checkout').textContent = checkOut;
        document.getElementById('modal-total').textContent = total;

        $('#descriptionModal').modal('show');
      });
    });
  });


  // $(document).ready(function() {
  //   $('#room_type').change(function() {
  //     var room_type = $(this).val();

  //     $.ajax({
  //       url: 'roombooking_detailsBackend.php',
  //       type: 'GET',
  //       data: {
  //         room_type: room_type
  //       },
  //       success: function(response) {
  //         var availableRooms = JSON.parse(response);
  //         $('#room_number').empty();
  //         $('#room_number').append('<option value="">Select Room Number</option>');

  //         availableRooms.forEach(function(room) {
  //           $('#room_number').append('<option value="' + room.room_number + '">' + room.room_number + '</option>');
  //         });
  //       },
  //       error: function() {
  //         alert('Error fetching available rooms.');
  //       }
  //     });
  //   });
  // });

  $(document).ready(function() {
    $('#room_type').change(function() {
      var room_type = $(this).val();

      $('#room_number').empty().append('<option value="">Select Room Number</option>');

      if (room_type) {
        $.ajax({
          url: 'roombooking_detailsBackend.php',
          type: 'GET',
          data: {
            action: 'get_rooms',
            room_type: room_type
          },
          success: function(response) {
            try {
              var data = JSON.parse(response);
              if (data.status === 'success') {
                data.rooms.forEach(function(room) {
                  $('#room_number').append('<option value="' + room.room_number + '">' + room.room_number + '</option>');
                });
              } else {
                alert('No rooms found for this type');
              }
            } catch (e) {
              console.error('Error parsing response:', e);
            }
          },
          error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
          }
        });
      }
    });

    // When form is submitted
    $('form').on('submit', function(e) {
      e.preventDefault();
      var room_type = $('#room_type').val();
      var room_number = $('#room_number').val();

      if (room_type && room_number) {
        $.ajax({
          url: 'roombooking_detailsBackend.php',
          type: 'GET',
          data: {
            action: 'get_bookings',
            room_type: room_type,
            room_number: room_number
          },
          success: function(response) {
            try {
              var data = JSON.parse(response);
              if (data.status === 'booked') {
                // Clear existing table
                $('table tbody').empty();

                // Add booking data to table
                data.data.forEach(function(booking) {
                  var row = `
                                    <tr>
                                        <td><strong>${booking.booking_id}</strong></td>
                                        <td>${booking.user_id}</td>
                                        <td>${booking.room_id}</td>
                                        <td>${booking.username}</td>
                                        <td>${booking.email}</td>
                                        <td>${booking.room_type}</td>
                                        <td>${booking.room_number}</td>
                                        <td>₹${parseFloat(booking.price).toFixed(2)}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm view-description"
                                                data-booking-id="${booking.booking_id}"
                                                data-user-id="${booking.user_id}"
                                                data-room-id="${booking.room_id}"
                                                data-username="${booking.username}"
                                                data-description="${booking.description}"
                                                data-email="${booking.email}"
                                                data-room-type="${booking.room_type}"
                                                data-room-number="${booking.room_number}"
                                                data-price="${parseFloat(booking.price).toFixed(2)}"
                                                data-checkin="${booking.check_in}"
                                                data-checkout="${booking.check_out}"
                                                data-total="${parseFloat(booking.total_price).toFixed(2)}">
                                                View
                                            </button>
                                        </td>
                                        <td>${renderImages(booking.images)}</td>
                                        <td>${formatDate(booking.check_in)}</td>
                                        <td>${formatDate(booking.check_out)}</td>
                                        <td><strong>₹${parseFloat(booking.total_price).toFixed(2)}</strong></td>
                                        <td>${getStatusBadge(booking.booking_status, 'booking')}</td>
                                        <td>${getStatusBadge(booking.payment_status, 'payment')}</td>
                                        <td>
                                            <a href="roombooking_detailsBackend.php?delete=${booking.booking_id}" 
                                               class="btn btn-danger btn-sm" 
                                               onClick="return confirm('Are you sure you want to delete this booking?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                `;
                  $('table tbody').append(row);
                });
              } else {
                $('table tbody').html('<tr><td colspan="16" class="text-center text-muted">No bookings found for this room.</td></tr>');
              }
            } catch (e) {
              console.error('Error parsing response:', e);
            }
          },
          error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
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
          `<img src='../uploads/rooms/${image}' alt='Room Image' class='img-thumbnail' width='80' height='80' style='margin-right:10px;'>`
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
    return `<span class='badge ${className}'>${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
  }
</script>