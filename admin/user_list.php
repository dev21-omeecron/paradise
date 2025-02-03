<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

if (isset($_GET['user_id'])) {
  $user_id = $_GET['user_id'];

  $sql = "DELETE FROM user WHERE user_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':user_id', $user_id);
  if ($stmt->execute()) {
    header("Location: user_list.php?message=User Deleted Successfully");
    exit();
  } else {
    echo "Error: Could not delete the user.";
  }
}

include("../layout/header.php");
include("../layout/sidebar.php");
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">User List</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-lg-12">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h3 class="card-title">Registered Users</h3>
            </div>
            <div class="card-body">
              <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['message']); ?></div>
              <?php endif; ?>
              <table class="table table-hover table-striped">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">User ID</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $stmt = $conn->prepare("SELECT * FROM user");
                  $stmt->execute();
                  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($user['user_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['contact']) . "</td>";

                    // Delete Button
                    echo '<td><a href="user_list.php?user_id=' . $user['user_id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this user?\');">Delete</a></td>';
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