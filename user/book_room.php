<?php
require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

include("../layout/header.php");
include("../layout/sidebar.php");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Book Your Hotel Room</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="room_booking-form">
                <form id="roomBookingForm">
                  <div class="form-group">
                    <label for="roomType">Room Type:</label>
                    <select id="room_type" class="form-control" name="room_type" required>
                      <option value="">Select Room Type</option>
                      <?php
                      $sql = "SELECT DISTINCT room_type FROM rooms";
                      $stmt = $conn->prepare($sql);
                      $stmt->execute();
                      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      foreach ($rooms as $room) {
                        echo "<option value='" . $room['room_type'] . "'>" . $room['room_type'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="roomNumber">Room Number:</label>
                    <select id="room_number" class="form-control" name="room_number" required>
                      <option value="">Select Room Number</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" id="price" class="form-control" name="price" required readonly>
                  </div>

                  <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" readonly></textarea>
                  </div>

                  <div class="form-group">
                    <label for="images">Images:</label>
                    <div id="imagesContainer"></div>
                  </div>

                  <div class="form-group">
                    <label for="checkInDate">Check-in Date:</label>
                    <input type="date" id="check_in" class="form-control" name="check_in" required>
                  </div>

                  <div class="form-group">
                    <label for="checkOutDate">Check-out Date:</label>
                    <input type="date" id="check_out" class="form-control" name="check_out" required>
                  </div>

                  <div class="form-group">
                    <label for="totalPrice">Total Price:</label>
                    <input type="text" id="total_price" class="form-control" name="total_price" readonly>
                  </div>

                  <button type="submit" class="btn btn-primary">Book Room</button>
                </form>
                <div id="statusMessage"></div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </section>
</div>

<?php include("../layout/footer.php"); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<script>
  $(document).ready(function() {
    // Fetch rooms based on selected room type
    $("#room_type").change(function() {
      var room_type = $(this).val();
      $.ajax({
        url: "book_roomBackend.php",
        type: "POST",
        data: {
          action: "fetchRooms",
          room_type: room_type
        },
        success: function(response) {
          $("#room_number").html(response);
        }
      });
    });

    // Fetch room details (price, description, and images) based on room number
    $("#room_number").change(function() {
      var room_number = $(this).val();
      $.ajax({
        url: 'book_roomBackend.php',
        method: 'POST',
        data: {
          action: 'fetchDetails',
          room_number: room_number
        },
        dataType: 'json',
        success: function(response) {
          if (response.error) {
            $("#imagesContainer").html('<p class="text-danger">' + response.error + '</p>');
            return;
          }

          $("#price").val(response.price);
          $("#description").val(response.description);

          $("#imagesContainer").empty();

          // Check if images exist and is an array
          if (response.images && Array.isArray(response.images) && response.images.length > 0) {
            var imagesHtml = '<div class="row">';
            response.images.forEach(function(image) {
              imagesHtml += `
                <div class="col-md-4 mb-3">
                  <img src='../uploads/rooms/${image}' alt='Room Image' class='img-fluid rounded' style='max-width: 350px; height: 150px border-radius: 5px; object-fit: cover;'>
                </div>`;
            });
            imagesHtml += '</div>';
            $("#imagesContainer").html(imagesHtml);
          } else {
            $("#imagesContainer").html('<p class="text-muted">No images available for this room.</p>');
          }
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
          $("#imagesContainer").html('<p class="text-danger">Error loading room details. Please try again.</p>');
        }
      });
    });

    // Calculate total price based on check-in and check-out dates
    $("#check_in, #check_out").change(function() {
      var checkIn = new Date($("#check_in").val());
      var checkOut = new Date($("#check_out").val());
      var price = parseFloat($("#price").val());

      if (checkIn && checkOut && price && checkOut > checkIn) {
        var diffTime = Math.abs(checkOut - checkIn);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        var totalPrice = diffDays * price;
        $("#total_price").val(totalPrice.toFixed(2));
      } else {
        $("#total_price").val("");
      }
    });

    // Submit the form to book the room
    $("#roomBookingForm").submit(function(event) {
      event.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: "book_roomBackend.php",
        type: "POST",
        data: formData + "&action=bookRoom",
        success: function(response) {
          if (response.includes("Booking successful")) {
            Toastify({
              text: "Booking successful!",
              duration: 3000,
              gravity: "top",
              position: "right",
              backgroundColor: "#28a745",
              stopOnFocus: true
            }).showToast();
            $("#roomBookingForm")[0].reset();
            $("#imagesContainer").html("");
          } else {
            Toastify({
              text: "Error: " + response,
              duration: 3000,
              gravity: "top",
              position: "right",
              backgroundColor: "#dc3545",
              stopOnFocus: true
            }).showToast();
            $("#roomBookingForm")[0].reset();
            $("#imagesContainer").html("");
          }
        }
      });
    });
  });
</script>