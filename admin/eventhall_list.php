<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

include("../layout/header.php");
include("../layout/sidebar.php");

require_once 'eventhall_listBackend.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Event Hall History</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-lg-12">
          <div class="card">
            <div class="card-body">

              <!-- Search Form -->
              <div class="row mb-4">
                <!-- Eventhall Type Dropdown -->
                <div class="col-md-2">
                  <label for="hall_type_filter">Eventhall Type:</label>
                  <select id="hall_type_filter" class="form-control">
                    <option value="">Select Eventhall Type</option>
                    <option value="Banquet Hall">Banquet Hall</option>
                    <option value="Function Hall">Function Hall</option>
                    <option value="Conference Hall">Conference Hall</option>
                    <option value="Meeting Hall">Meeting Hall</option>
                    <option value="Party Hall">Party Hall</option>
                    <option value="Rooftop Venue Hall">Rooftop Venue Hall</option>
                    <option value="Wedding Hall">Wedding Hall</option>
                  </select>
                </div>

                <!-- Hall Number Dropdown -->
                <div class="col-md-2">
                  <label for="hall_number_filter">Eventhall Number:</label>
                  <select id="hall_number_filter" class="form-control">
                    <option value="">Select Eventhall Number</option>
                    <?php
                    // Fetch hall numbers from the database
                    $sql = "SELECT DISTINCT hall_number FROM event_halls";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $hallNumbers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($hallNumbers as $hall) {
                      echo "<option value='{$hall['hall_number']}'>{$hall['hall_number']}</option>";
                    }
                    ?>
                  </select>
                </div>

                <!-- Status Dropdown -->
                <div class="col-md-2">
                  <label for="status_filter">Status:</label>
                  <select id="status_filter" class="form-control">
                    <option value="">Select Status</option>
                    <option value="Available">Available</option>
                    <option value="Booked">Booked</option>
                    <option value="Maintenance">Maintenance</option>
                  </select>
                </div>

                <!-- Capacity Input -->
                <div class="col-md-2">
                  <label for="capacity_filter">Capacity:</label>
                  <input type="number" id="capacity_filter" class="form-control" placeholder="Enter Capacity">
                </div>

                <!-- Price per Hour Input -->
                <div class="col-md-2">
                  <label for="price_filter">Price per Hour:</label>
                  <input type="number" id="price_filter" class="form-control" placeholder="Enter Price per Hour">
                </div>

                <!-- Search and Reset Button -->
                <div class="col-md-2 d-flex align-items-end">
                  <button id="search_btn" class="btn btn-primary me-2"><i class="fas fa-search"></i> Search</button>
                  <button id="reset_btn" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Reset</button>
                </div>
              </div>

              <!-- No Result Message -->
              <div id="no_results_message" style="display: none;" class="alert alert-info mt-3">
                No Eventhalls available for the selected filters.
              </div>

              <!-- Results Table -->
              <div id="results_table">
                <table class="table table-hover table-striped">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">Hall ID</th>
                      <th scope="col">Hall Type</th>
                      <th scope="col">Hall Number</th>
                      <th scope="col">Capacity</th>
                      <th scope="col">Price per Hour</th>
                      <th scope="col">Status</th>
                      <th scope="col">Description</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody id="results_table_body">
                    <?php foreach ($eventhalls as $eventhall) : ?>
                      <tr class="eventhall-row">
                        <td><?= $eventhall['hall_id'] ?></td>
                        <td><?= $eventhall['hall_type'] ?></td>
                        <td>
                          <a href="edit_eventhall.php?hall_number=<?= $eventhall['hall_number'] ?>" class="eventhall-link">
                            <?= $eventhall['hall_number'] ?>
                          </a>
                        </td>
                        <td><?= $eventhall['capacity'] ?></td>
                        <td><?= $eventhall['price_per_hour'] ?></td>
                        <td><?= $eventhall['status'] ?></td>
                        <td><?= $eventhall['description'] ?></td>
                        <td>
                          <!-- Delete Button -->
                          <form action="eventhall_list.php" method="POST" style="display:inline;">
                            <input type="hidden" name="hall_id" value="<?= $eventhall['hall_id'] ?>">
                            <button type="submit" name="delete_eventhall" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event hall?');">Delete</button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    // Search button click handler
    $('#search_btn').click(function() {
      const hallType = $('#hall_type_filter').val();
      const hallNumber = $('#hall_number_filter').val();
      const status = $('#status_filter').val();
      const capacity = $('#capacity_filter').val();
      const price = $('#price_filter').val();

      $.ajax({
        url: 'eventhall_listBackend.php',
        method: 'POST',
        data: {
          hall_type: hallType,
          hall_number: hallNumber,
          status: status,
          capacity: capacity,
          price_per_hour: price
        },
        success: function(response) {
          try {
            const result = JSON.parse(response);

            if (result.event_halls && result.event_halls.length > 0) {
              $('#results_table').show();
              $('#no_results_message').hide();
              updateTable(result.event_halls);
            } else {
              $('#results_table').hide();
              $('#no_results_message').show();
            }
          } catch (e) {
            console.error('Error parsing response:', e);
            alert('Error occurred while searching. Please try again.');
          }
        },
        error: function() {
          alert('Error occurred while searching. Please try again.');
        }
      });
    });

    // Reset button click handler
    $('#reset_btn').click(function() {
      // Clear filters
      $('#hall_type_filter').val('');
      $('#hall_number_filter').val('');
      $('#status_filter').val('');
      $('#capacity_filter').val('');
      $('#price_filter').val('');

      // Fetch all eventhalls
      $.ajax({
        url: 'eventhall_listBackend.php',
        method: 'POST',
        data: {
          reset: true
        },
        success: function(response) {
          try {
            const result = JSON.parse(response);
            if (result.event_halls) {
              $('#results_table').show();
              $('#no_results_message').hide();
              updateTable(result.event_halls);
            }
          } catch (e) {
            console.error('Error parsing response:', e);
          }
        }
      });
    });

    // Function to update the table with eventhall data
    function updateTable(event_halls) {
      let tableContent = '';
      event_halls.forEach(function(eventhall) {
        tableContent += `
        <tr class="eventhall-row">
          <td>${eventhall.hall_id}</td>
          <td>${eventhall.hall_type}</td>
          <td>
            <a href="edit_eventhall.php?hall_number=${eventhall.hall_number}" class="eventhall-link">
              ${eventhall.hall_number}
            </a>
          </td>
          <td>${eventhall.capacity}</td>
          <td>${eventhall.price_per_hour}</td>
          <td>${eventhall.status}</td>
          <td>${eventhall.description}</td>
          <td>
            <form action="eventhall_list.php" method="POST" style="display:inline;">
              <input type="hidden" name="hall_id" value="${eventhall.hall_id}">
              <button type="submit" name="delete_eventhall" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event hall?');">Delete</button>
            </form>
          </td>
        </tr>
      `;
      });
      $('#results_table_body').html(tableContent);
    }
  });
</script>