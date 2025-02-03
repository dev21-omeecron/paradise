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
          <h1 class="m-0">Add Room</h1>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form id="addRoomForm">
                <div class="form-group">
                  <label for="room_type">Room Type:</label>
                  <select class="form-control" id="room_type" name="room_type" required>
                    <option value="" disabled selected>Select Room Type</option>
                    <option value="Single Room">Single Room</option>
                    <option value="Double Room">Double Room</option>
                    <option value="Standard Room">Standard Room</option>
                    <option value="Deluxe Room">Deluxe Room</option>
                    <option value="Quadruple Room">Quadruple Room</option>
                    <option value="Presidential Room">Presidential Room</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="room_number">Room Number:</label>
                  <input type="text" class="form-control" id="room_number" name="room_number" required>
                </div>
                <div class="form-group">
                  <label for="price">Price:</label>
                  <input type="number" class="form-control" id="price" name="price" required>
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
                  <label for="images">Room Images:</label>
                  <input type="file" class="form-control" id="images" name="images[]" multiple required>
                </div>

                <button type="submit" class="btn btn-primary">Add Room</button>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("../layout/footer.php"); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $(document).ready(function() {
    $('#addRoomForm').submit(function(e) {
      e.preventDefault();
      var formData = new FormData(this);

      $.ajax({
        url: 'add_roomBackend.php',
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
              alert('Room added successfully!');
              $('#addRoomForm')[0].reset();
            } else {
              alert('Error: ' + res.message);
            }
          } catch (e) {
            console.error('Error parsing response:', e);
            alert('Unexpected error occurred. Please check console.');
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Request Failed:', status, error);
          alert('Error! Please try again.');
        }
      });
    });
  });
</script>