\<?php
  require_once('../dbcon.php');

  if (!validateSession()) {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit();
  }

  include("../layout/header.php");
  include("../layout/sidebar.php");

  $room_number = $_GET['room_number'];
  $sql = "SELECT * FROM rooms WHERE room_number = :room_number";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
  $stmt->execute();
  $room = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($room) {
    $images = json_decode($room['images'], true);
  } else {
    echo "<script>alert('Room not found.'); window.location.href = 'room_list.php';</script>";
    exit();
  }
  ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Edit Room</h1>
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
              <form id="editRoomForm">
                <input type="hidden" name="room_id" value="<?= htmlspecialchars($room['room_id']) ?>">

                <div class="form-group">
                  <label>Room Type:</label>
                  <select class="form-control" name="room_type" required>
                    <option value="Single Room" <?= $room['room_type'] == 'Single Room' ? 'selected' : '' ?>>Single Room</option>
                    <option value="Double Room" <?= $room['room_type'] == 'Double Room' ? 'selected' : '' ?>>Double Room</option>
                    <option value="Standard Room" <?= $room['room_type'] == 'Standard Room' ? 'selected' : '' ?>>Standard Room</option>
                    <option value="Deluxe Room" <?= $room['room_type'] == 'Deluxe Room' ? 'selected' : '' ?>>Deluxe Room</option>
                    <option value="Quadruple Room" <?= $room['room_type'] == 'Quadruple Room' ? 'selected' : '' ?>>Quadruple Room</option>
                    <option value="Presidential Room" <?= $room['room_type'] == 'Presidential Room' ? 'selected' : '' ?>>Presidential Room</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Room Number:</label>
                  <input type="text" class="form-control" name="room_number" value="<?= htmlspecialchars($room['room_number']) ?>" required>
                </div>

                <div class="form-group">
                  <label>Price:</label>
                  <input type="number" class="form-control" name="price" value="<?= htmlspecialchars($room['price']) ?>" required>
                </div>

                <div class="form-group">
                  <label>Status:</label>
                  <select class="form-control" name="status" required>
                    <option value="Available" <?= $room['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                    <option value="Booked" <?= $room['status'] == 'Booked' ? 'selected' : '' ?>>Booked</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Description:</label>
                  <textarea class="form-control" name="description" required><?= htmlspecialchars($room['description']) ?></textarea>
                </div>

                <div class="form-group">
                  <label>Current Images:</label>
                  <div class="row">
                    <?php if ($images): ?>
                      <?php foreach ($images as $image): ?>
                        <div class="col-md-3 mb-3">
                          <div class="image-container text-center">
                            <img src="../uploads/rooms/<?= htmlspecialchars($image) ?>" alt="Room Image" class="img-fluid rounded shadow">
                            <div class="mt-2">
                              <input type="checkbox" name="delete_images[]" value="<?= htmlspecialchars($image) ?>"> Delete
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
                  <label>Add New Images:</label>
                  <input type="file" class="form-control" name="images[]" multiple>
                </div>

                <button type="submit" class="btn btn-primary">Update Room</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  $(document).ready(function() {
    $('#editRoomForm').on('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(this);

      $.ajax({
        url: 'edit_roomBackend.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          var result = JSON.parse(response);
          if (result.status === "success") {
            toastr.success(result.message);
            setTimeout(() => {
              window.location.href = 'room_list.php';
            }, 2000);
          } else {
            toastr.error(result.message);
          }
        },
        error: function() {
          toastr.error("An error occurred while updating the room.");
        }
      });
    });
  });
</script>

<?php include("../layout/footer.php"); ?>