<?php
require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

include("../layout/header.php");
include("../layout/sidebar.php");
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Visitor Messages</h1>
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
              <table class="table table-hover table-striped">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">Inquiry ID</th>
                    <th scope="col">User ID</th>
                    <th scope="col">Login name</th>
                    <th scope="col">Visitor name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Message</th>
                    <th scope="col">Admin Reply</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT * FROM inquiry";
                  $stmt = $conn->prepare($sql);
                  $stmt->execute();
                  $inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  foreach ($inquiries as $inquiry) {
                    echo "<tr class='message-row'>";
                    echo "<td>" . $inquiry["inquiry_id"] . "</td>";
                    echo "<td>" . $inquiry["user_id"] . "</td>";
                    echo "<td>" . $inquiry["login_username"] . "</td>";
                    echo "<td>" . $inquiry["visitor_name"] . "</td>";
                    echo "<td>" . $inquiry["email"] . "</td>";
                    echo "<td>" . $inquiry["contact"] . "</td>";
                    echo "<td>" . $inquiry["subject"] . "</td>";
                    echo '<td><button class="btn btn-primary viewButton" data-toggle="modal" data-target="#messageModal' . $inquiry['inquiry_id'] . '">View</button></td>';
                    echo "<td>" . ($inquiry['admin_reply'] ?: "No reply yet") . "</td>";
                    echo "</tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php
include("../layout/footer.php");
?>

<!-- Modal for Message View -->
<?php foreach ($inquiries as $inquiry): ?>
  <div class="modal fade" id="messageModal<?php echo $inquiry['inquiry_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel<?php echo $inquiry['inquiry_id']; ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="messageModalLabel<?php echo $inquiry['inquiry_id']; ?>">Message Details</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <p><strong>Inquiry ID:</strong> <?php echo $inquiry['inquiry_id']; ?></p>
            </div>
            <div class="col-12">
              <p><strong>User ID:</strong> <?php echo $inquiry['user_id']; ?></p>
            </div>
            <div class="col-12">
              <p><strong>Login Name:</strong> <?php echo $inquiry['login_username']; ?></p>
            </div>
            <div class="col-12">
              <p><strong>Visitor Name:</strong> <?php echo $inquiry['visitor_name']; ?></p>
            </div>
            <div class="col-12">
              <p><strong>Email:</strong> <?php echo $inquiry['email']; ?></p>
            </div>
            <div class="col-12">
              <p><strong>Contact:</strong> <?php echo $inquiry['contact']; ?></p>
            </div>
            <div class="col-12">
              <p><strong>Subject:</strong> <?php echo $inquiry['subject']; ?></p>
            </div>
            <div class="col-12">
              <p><strong>My Message:</strong></p>
              <p><?php echo nl2br($inquiry['message']); ?></p>
            </div>
            <div class="col-12 mt-3">
              <p><strong>Admin Reply:</strong></p>
              <p><?php echo !empty($inquiry['admin_reply']) ? nl2br($inquiry['admin_reply']) : "No reply yet."; ?></p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <form class="replyForm" method="POST" id="replyForm<?php echo $inquiry['inquiry_id']; ?>" action="javascript:void(0);">
            <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['inquiry_id']; ?>">
            <textarea name="reply" class="form-control" placeholder="Type your reply here..." required></textarea>
            <button type="submit" class="btn btn-primary mt-2">Send Reply</button>
          </form>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>


<script>
  $(document).ready(function() {
    $('.replyForm').on('submit', function(event) {
      event.preventDefault();

      var formData = $(this).serialize();
      var inquiryId = $(this).find("input[name='inquiry_id']").val();

      $.ajax({
        url: 'reply_inquiry.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            $('#messageModal' + inquiryId + ' .modal-body .col-12.mt-3 p:last-child').text(response.message);
            $('#messageModal' + inquiryId + ' .modal-footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>');
          } else {
            alert(response.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', error);
          alert('Something went wrong. Please try again.');
        }
      });
    });
  });
</script>

<style>
  .table-hover tbody tr:hover {
    background-color: #f1f1f1;
  }

  .viewButton {
    transition: background-color 0.3s ease;
  }

  .viewButton:hover {
    background-color: #007bff;
    color: white;
  }

  .modal-header {
    background-color: #007bff;
  }

  .modal-footer button {
    transition: background-color 0.3s ease;
  }

  .modal-footer button:hover {
    background-color: #007bff;
    color: white;
  }

  .message-row {
    transition: background-color 0.3s ease;
  }

  .message-row:hover {
    background-color: #f5f5f5;
  }
</style>