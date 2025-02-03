<?php

require_once("../dbcon.php");

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit;
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
          <h1 class="m-0">Add Event Hall</h1>
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
              <form id="addEventHallForm">
                <div class="form-group">
                  <label for="hall_type">Hall Type:</label>
                  <select class="form-control" id="hall_type" name="hall_type" required>
                    <option value="" disabled selected>Select Hall Type</option>

                    <option value="Banquet Hall">Banquet Hall</option>
                    <option value="Function Hall">Function Hall</option>
                    <option value="Conference Hall">Conference Hall</option>
                    <option value="Meeting Hall">Meeting Hall</option>
                    <option value="Party Hall">Party Hall</option>
                    <option value="Rooftop Venue Hall">Rooftop Venue Hall</option>
                    <option value="Wedding Hall">Wedding Hall</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="hall_number">Hall Number:</label>
                  <input type="text" class="form-control" id="hall_number" name="hall_number" required>
                </div>
                <div class="form-group">
                  <label for="capacity">Capacity:</label>
                  <input type="number" class="form-control" id="capacity" name="capacity" required>
                </div>
                <div class="form-group">
                  <label for="price_per_hour">Price per Hour:</label>
                  <input type="number" class="form-control" id="price_per_hour" name="price_per_hour" required>
                </div>
                <div class="form-group">
                  <label for="status">Status:</label>
                  <select class="form-control" id="status" name="status" required>
                    <option value="Available">Available</option>
                    <option value="Booked">Booked</option>
                    <option value="Maintenance">Maintenance</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="description">Description:</label>
                  <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                  <label for="images">Hall Images:</label>
                  <input type="file" class="form-control" id="images" name="images[]" multiple required>
                </div>
                <button type="submit" class="btn btn-primary">Add Hall</button>
              </form>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php include("../layout/footer.php"); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $(document).ready(function() {
    $('#addEventHallForm').submit(function(e) {
      e.preventDefault();
      var formData = new FormData(this);

      $.ajax({
        url: 'add_eventhallBackend.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          console.log('Response:', response);
          try {
            var res = JSON.parse(response);
            console.log('Parsed Response:', res);
            if (res.status === "success") {
              alert('Event Hall added successfully!');
              $('#addEventHallForm')[0].reset();
            } else {
              alert('Error: ' + res.message);
            }
          } catch (error) {
            console.error('Error parsing response:', error);
            alert('Error: ' + error.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
          alert('Error: ' + error);
        }
      });
    });
  })
</script>