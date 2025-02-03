<?php
require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

include("../layout/header.php");
include("../layout/sidebar.php");

require_once 'room_listBackend.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Room History</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-lg-12">
          <!-- Advanced Search Filter Section -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Advanced Search</h3>
            </div>
            <div class="card-body">
              <div id="filterContainer">
                <!-- Initial filter row -->
                <div class="filter-row row mb-3">
                  <div class="col-md-4">
                    <select class="form-control filter-field">
                      <option value="">Select Field</option>
                      <option value="room_id">Room ID</option>
                      <option value="room_type">Room Type</option>
                      <option value="room_number">Room Number</option>
                      <option value="price">Price</option>
                      <option value="status">Status</option>
                      <option value="description">Description</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <input type="text" class="form-control filter-value" placeholder="Enter value">
                  </div>
                  <div class="col-md-4">
                    <button class="btn btn-primary add-filter">Add Filter</button>
                    <button class="btn btn-danger remove-filter" style="display: none;">Remove</button>
                  </div>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-md-12">
                  <button id="searchBtn" class="btn btn-success">Search</button>
                  <button id="resetBtn" class="btn btn-secondary ml-2">Reset</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Results Table Section -->
          <div class="card">
            <div class="card-body">
              <div id="tableContainer">
                <table id="roomsTable" class="table table-hover table-striped">
                  <thead class="thead-dark">
                    <tr>
                      <th>Room ID</th>
                      <th>Room Type</th>
                      <th>Room Number</th>
                      <th>Price</th>
                      <th>Status</th>
                      <th>Description</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="roomsTableBody">
                    <?php foreach ($rooms as $room) : ?>
                      <tr>
                        <td><?= htmlspecialchars($room['room_id']) ?></td>
                        <td><?= htmlspecialchars($room['room_type']) ?></td>
                        <td>
                          <a href="edit_room.php?room_number=<?= htmlspecialchars($room['room_number']) ?>">
                            <?= htmlspecialchars($room['room_number']) ?>
                          </a>
                        </td>
                        <td><?= htmlspecialchars($room['price']) ?></td>
                        <td><?= htmlspecialchars($room['status']) ?></td>
                        <td><?= htmlspecialchars($room['description']) ?></td>
                        <td>
                          <form action="room_list.php" method="POST" style="display:inline;">
                            <input type="hidden" name="room_id" value="<?= $room['room_id'] ?>">
                            <button type="submit" name="delete_room" class="btn btn-danger delete-room"
                              data-room-id="<?= $room['room_id'] ?>">Delete</button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <div id="noDataMessage" style="display: none;" class="alert alert-info text-center">
                No rooms found matching your search criteria.
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php include("../layout/footer.php"); ?>

<!-- Include jQuery and DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
  $(document).ready(function() {
    // Initialize DataTable
    var table = $('#roomsTable').DataTable({
      "processing": true,
      "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });

    var maxFilters = 5;
    var filterCount = 1;

    function updateAddFilterButton() {
      if (filterCount >= maxFilters) {
        $('.add-filter').prop('disabled', true);
      } else {
        $('.add-filter').prop('disabled', false);
      }
    }

    // Add filter row
    $(document).on('click', '.add-filter', function() {
      var newRow = `
        <div class="filter-row row mb-3">
          <div class="col-md-4">
            <select class="form-control filter-field">
              <option value="">Select Field</option>
              <option value="room_id">Room ID</option>
              <option value="room_type">Room Type</option>
              <option value="room_number">Room Number</option>
              <option value="price">Price</option>
              <option value="status">Status</option>
              <option value="description">Description</option>
            </select>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control filter-value" placeholder="Enter value">
          </div>
          <div class="col-md-4">
            <button class="btn btn-primary add-filter">Add Filter</button>
            <button class="btn btn-danger remove-filter">Remove</button>
          </div>
        </div>
      `;
      $('#filterContainer').append(newRow);
      filterCount++;
      updateAddFilterButton();
    });

    // Remove filter row
    $(document).on('click', '.remove-filter', function() {
      $(this).closest('.filter-row').remove();
      filterCount--;
      updateAddFilterButton();
    });

    // Search functionality
    $('#searchBtn').click(function() {
      var filters = [];
      $('.filter-row').each(function() {
        var field = $(this).find('.filter-field').val();
        var value = $(this).find('.filter-value').val();
        if (field && value) {
          filters.push({
            field: field,
            value: value
          });
        }
      });

      if (filters.length > 0) {
        $.ajax({
          url: 'room_listBackend.php',
          type: 'POST',
          data: {
            filters: JSON.stringify(filters)
          },
          success: function(response) {
            var data = JSON.parse(response);
            if (data.status === 'success') {
              table.clear().rows.add(data.data).draw();
              $('#noDataMessage').hide();
            } else {
              table.clear().draw();
              $('#noDataMessage').show();
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX Error: " + status + error);
          }
        });
      } else {
        alert("Please add at least one filter.");
      }
    });

    // Reset functionality
    $('#resetBtn').click(function() {
      $.ajax({
        url: 'room_listBackend.php',
        type: 'POST',
        data: {
          reset: true
        },
        success: function(response) {
          var data = JSON.parse(response);
          if (data.status === 'success') {
            table.clear().rows.add(data.rooms).draw();
            $('#noDataMessage').hide();
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + error);
        }
      });
    });
    updateAddFilterButton();
  });
</script>