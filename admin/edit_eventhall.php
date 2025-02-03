<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "auth/login.php");
  exit();
}

include("../layout/header.php");
include("../layout/sidebar.php");

$hall_number = $_GET['hall_number'];
$sql = "SELECT * FROM event_halls WHERE hall_number = :hall_number";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':hall_number', $hall_number, PDO::PARAM_STR);
$stmt->execute();
$hall = $stmt->fetch(PDO::FETCH_ASSOC);

if ($hall) {
  $images = json_decode($hall['images'], true);
} else {
  echo "<script>alert('Hall not found.'); window.location.href = 'eventhall_list.php';</script>";
  exit();
}


?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Edit Event Hall</h1>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-md-12">
          <div class="card shadow-lg">
            <div class="card-body">
              <form id="editHallForm">
                <input type="hidden" name="hall_id" value="<?= htmlspecialchars($hall['hall_id']) ?>">
                <div class="form-group mb-3">
                  <label for="hall_type">Hall Type:</label>
                  <select class="form-control" id="hall_type" name="hall_type" required>
                    <option value="" disabled selected>Select Hall Type</option>
                    <option value="Banquet Hall" <?= $hall['hall_type'] == 'Banquet Hall' ? 'selected' : '' ?>>Banquet Hall</option>
                    <option value="Function Hall" <?= $hall['hall_type'] == 'Function Hall' ? 'selected' : '' ?>>Function Hall</option>
                    <option value="Conference Hall" <?= $hall['hall_type'] == 'Conference Hall' ? 'selected' : '' ?>>Conference Hall</option>
                    <option value="Meeting Hall" <?= $hall['hall_type'] == 'Meeting Hall' ? 'selected' : '' ?>>Meeting Hall</option>
                    <option value="Party Hall" <?= $hall['hall_type'] == 'Party Hall' ? 'selected' : '' ?>>Party Hall</option>
                    <option value="Rooftop Venue Hall" <?= $hall['hall_type'] == 'Rooftop Venue Hall' ? 'selected' : '' ?>>Rooftop Venue Hall</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="hall_number">Hall Number:</label>
                  <input type="text" class="form-control" id="hall_number" name="hall_number" value="<?= htmlspecialchars($hall['hall_number']) ?>" required>
                </div>
                <div class="form-group">
                  <label for="capacity">Capacity:</label>
                  <input type="number" class="form-control" id="capacity" name="capacity" value="<?= htmlspecialchars($hall['capacity']) ?>" required>
                </div>
                <div class="form-group">
                  <label for="price_per_hour">Price per Hour:</label>
                  <input type="text" class="form-control" id="price_per_hour" name="price_per_hour" value="<?= htmlspecialchars($hall['price_per_hour']) ?>">
                </div>
                <div class="form-group">
                  <label for="status">Status:</label>
                  <select class="form-control" id="status" name="status" required>
                    <option value="Available" <?= $hall['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                    <option value="Booked" <?= $hall['status'] == 'Booked' ? 'selected' : '' ?>>Booked</option>
                    <option value="Maintenance" <?= $hall['status'] == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="description">Description:</label>
                  <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($hall['description']) ?></textarea>
                </div>
                <div class="form-group">
                  <label>Current Images:</label>
                  <div class="row">
                    <?php if ($images) : ?>
                      <?php foreach ($images as $image) : ?>
                        <div class="col-md-3 mb-3">
                          <div class="image-container">
                            <img src="../uploads/halls/<?= htmlspecialchars($image) ?>" alt="Eventhall Image" class="img-fluid">
                            <div class="mt-2">
                              <input type="checkbox" name="delete_images[]" value="<?= htmlspecialchars($image) ?>">Delete this image
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <p>No images available.</p>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="images">Add New Images:</label>
                  <input type="file" class="form-control" id="images" name="images[]" multiple>
                </div>
                <button type="submit" class="btn btn-primary">Update Hall</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Include Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  $(document).ready(function() {
    $('#editHallForm').on('submit', function(e) {
      e.preventDefault();

      var formData = new FormData(this);

      $.ajax({
        url: 'edit_eventhallBackend.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          var result = JSON.parse(response);
          if (result.status === "success") {
            toastr.success(result.message);
            setTimeout(() => {
              window.location.href = 'eventhall_list.php';
            }, 3000);
          } else {
            toastr.error(result.message);
          }
        },
        error: function() {
          toastr.error("An error occurred while updating the event hall.");
        }
      });
    })
  })
</script>

<?php include("../layout/footer.php"); ?>