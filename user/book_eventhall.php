<?php

require_once("../dbcon.php");

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
          <h1 class="m-0">Book Your Event Hall</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form id="addeventhallForm">
                <div class="form-group">
                  <label for="hall_type">Hall Type:</label>
                  <select id="hall_type" class="form-control" name="hall_type" required>
                    <option value="">Select Hall Type</option>
                    <?php
                    $sql = "SELECT DISTINCT hall_type FROM event_halls";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $halls = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($halls as $hall) {
                      echo "<option value='" . $hall['hall_type'] . "'>" . $hall['hall_type'] . "</option>";
                    }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="hallNumber">Hall Number:</label>
                  <select id="hall_number" class="form-control" name="hall_number" required>
                    <option value="">Select Hall Number</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="capacity">Capacity:</label>
                  <input type="text" id="capacity" class="form-control" name="capacity" required readonly>
                </div>

                <div class="form-group">
                  <label for="price_per_hour">Price per Hour:</label>
                  <input type="text" id="price_per_hour" class="form-control" name="price_per_hour" required readonly>
                </div>

                <div class="form-group">
                  <label for="description">Description:</label>
                  <textarea class="form-control" id="description" name="description" rows="4" readonly></textarea>
                </div>

                <div class="form-group">
                  <label for="images">Hall Images:</label>
                  <div id="imagesContainer"></div>
                </div>

                <div class="form-group">
                  <label for="checkInDate">Check-in Date and Time:</label>
                  <input type="datetime-local" id="check_in" class="form-control" name="check_in" required>
                </div>

                <div class="form-group">
                  <label for="checkOutDate">Check-out Date and Time:</label>
                  <input type="datetime-local" id="check_out" class="form-control" name="check_out" required>
                </div>


                <div class="form-group">
                  <label for="totalPrice">Total Price:</label>
                  <input type="text" id="total_price" class="form-control" name="total_price" readonly>
                </div>

                <input type="hidden" id="resource_id" name="resource_id"> <!-- Hidden field for resource_id -->

                <button type="submit" class="btn btn-primary">Book Hall</button>
              </form>
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
    $('#hall_type').on('change', function() {
      var selectedHallType = $(this).val();
      // Reset other fields
      $('#capacity').val('');
      $('#price_per_hour').val('');
      $('#description').val('');
      $('#imagesContainer').html('');
      $('#total_price').val('');

      $.ajax({
        url: 'book_eventhallBackend.php',
        type: 'POST',
        data: {
          action: 'fetchHalls',
          hall_type: selectedHallType
        },
        success: function(response) {
          $('#hall_number').html(response);
        }
      });
    });

    $('#hall_number').on('change', function() {
      var selectedHallNumber = $(this).val();
      if (!selectedHallNumber) {
        $('#capacity').val('');
        $('#price_per_hour').val('');
        $('#description').val('');
        $('#imagesContainer').html('');
        $('#total_price').val('');
        return;
      }

      $.ajax({
        url: 'book_eventhallBackend.php',
        type: 'POST',
        data: {
          action: 'fetchDetails',
          hall_number: selectedHallNumber
        },
        success: function(response) {
          try {
            if (typeof response === 'string') {
              response = JSON.parse(response);
            }

            if (response.error) {
              console.error('Error:', response.error);
              return;
            }

            // Update form fields
            $('#capacity').val(response.capacity || '');
            $('#price_per_hour').val(response.price_per_hour || '');
            $('#description').val(response.description || '');

            // Handle images
            var imagesContainer = $('#imagesContainer');
            imagesContainer.empty();

            if (response.images) {
              try {
                var images = JSON.parse(response.images);
                images.forEach(function(image) {
                  var imgElement = $('<img>')
                    .attr('src', '../uploads/halls/' + image)
                    .css({
                      'max-width': '350px',
                      'margin': '5px',
                      'border-radius': '5px'
                    });
                  imagesContainer.append(imgElement);
                });
              } catch (e) {
                console.error('Error parsing images:', e);
              }
            }
            // Store hall_id
            $('#resource_id').val(response.hall_id || '');
            // Reset total price
            $('#total_price').val('');
          } catch (e) {
            console.error('Error processing response:', e);
          }
        },
        error: function(xhr, status, error) {
          console.error('Ajax error:', error);
        }
      });
    });

    $('#check_in, #check_out').change(function() {
      var checkIn = new Date($('#check_in').val());
      var checkOut = new Date($('#check_out').val());
      var price = parseFloat($('#price_per_hour').val());
      var hours = (checkOut - checkIn) / (1000 * 60 * 60);
      var totalPrice = price * hours;
      $('#total_price').val(totalPrice.toFixed(2));
    });

    $('#addeventhallForm').submit(function(event) {
      event.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: 'book_eventhallBackend.php',
        type: 'POST',
        data: formData + '&action=bookHall',
        success: function(response) {
          $('#statusMessage').html(response);
          if (response.includes("Booking successful")) {
            Toastify({
              text: "Booking successful!",
              duration: 3000,
              gravity: "top",
              position: "right",
              backgroundColor: "#28a745",
              stopOnFocus: true
            }).showToast();
            $('#addeventhallForm')[0].reset();
            $('#imagesContainer').html('');
          } else {
            Toastify({
              text: "Booking failed: " + response,
              duration: 3000,
              gravity: "top",
              position: "right",
              backgroundColor: "#dc3545",
              stopOnFocus: true
            }).showToast();
          }
        }
      });
    });
  });
</script>