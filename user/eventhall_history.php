<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit;
}

include("../layout/header.php");
include("../layout/sidebar.php");

require_once 'eventhall_historyBackend.php';

?>

<style>
  .table th,
  .table td {
    vertical-align: middle;
    text-align: center;
  }

  .btn-primary {
    background-color: #4A90E2;
    border-color: #4A90E2;
  }

  .btn-danger {
    background-color: #E74C3C;
    border-color: #E74C3C;
  }

  .btn-primary:hover {
    background-color: #357ABD;
  }

  .btn-danger:hover {
    background-color: #C0392B;
  }

  .table img {
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 2px;
  }

  .badge {
    padding: 0.4rem 0.8rem;
    font-size: 0.9rem;
  }

  .card-header {
    background-color: #4A90E2;
    color: #fff;
  }

  .content-header h1 {
    font-size: 2rem;
    color: #4A90E2;
  }

  .content-header p {
    color: #6c757d;
  }
</style>

<div class="content-wrapper">
  <div class="content-header text-center">
    <div class="container-fluid">
      <h1 class="m-0 font-weight-bold">Event Hall Booking History</h1>
      <p class="text-muted">Here you can view all the booked event hall details with actions</p>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-lg-12">
          <div class="card shadow">
            <div class="card-header">
              <h3 class="card-title">Booking Details</h3>
            </div>
            <div class="card-body">
              <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                  <tr>
                    <th>Booking ID</th>
                    <th>Hall Type</th>
                    <th>Hall Number</th>
                    <th>Capacity</th>
                    <th>Price/Hour</th>
                    <th>Description</th>
                    <th>Images</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Total Price</th>
                    <th>Booking Status</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                    <th>Invoice</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($hall_bookings)) : ?>
                    <?php foreach ($hall_bookings as $booking) : ?>
                      <tr>
                        <td><strong><?= htmlspecialchars($booking['booking_id']) ?></strong></td>
                        <td><?= htmlspecialchars($booking['hall_type']) ?></td>
                        <td><?= htmlspecialchars($booking['hall_number']) ?></td>
                        <td><?= htmlspecialchars($booking['capacity']) ?></td>
                        <td>₹<?= number_format($booking['price_per_hour'], 2) ?></td>
                        <td><?= htmlspecialchars($booking['description']) ?></td>
                        <td>
                          <?php
                          $images = json_decode($booking['images'], true);
                          if (is_array($images)) {
                            foreach ($images as $image) {
                              echo "<img src='../uploads/halls/$image' alt='Hall Image' width='60' height='60' class='mr-2'>";
                            }
                          } else {
                            echo "<span class='text-muted'>No images available</span>";
                          }
                          ?>
                        </td>
                        <td><?= htmlspecialchars(date("d M Y, h:i A", strtotime($booking['check_in']))) ?></td>
                        <td><?= htmlspecialchars(date("d M Y, h:i A", strtotime($booking['check_out']))) ?></td>
                        <td><strong>₹<?= number_format($booking['total_price'], 2) ?></strong></td>
                        <td>
                          <?php
                          if ($booking['booking_status'] === 'confirmed') {
                            echo "<span class='badge badge-success'>Confirmed</span>";
                          } elseif ($booking['booking_status'] === 'pending') {
                            echo "<span class='badge badge-warning'>Pending</span>";
                          } else {
                            echo "<span class='badge badge-danger'>Cancelled</span>";
                          }
                          ?>
                        </td>
                        <td>
                          <?php
                          if ($booking['payment_status'] === 'paid') {
                            echo "<span class='badge badge-success'>Paid</span>";
                          } elseif ($booking['payment_status'] === 'pending') {
                            echo "<span class='badge badge-warning'>Pending</span>";
                          } else {
                            echo "<span class='badge badge-danger'>Unpaid</span>";
                          }
                          ?>
                        </td>
                        <td>
                          <a href="eventhall_historyBackend.php?delete=<?= $booking['booking_id'] ?>" class="btn btn-sm btn-danger" onClick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
                        </td>
                        <td>
                          <a href="invoice_hall.php?booking_id=<?= $booking['booking_id'] ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> View</a>
                          <a href="invoice_hall.php?booking_id=<?= $booking['booking_id'] ?>&download=true" class="btn btn-sm btn-primary">
                            <i class="fa fa-download"></i> Download
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <tr>
                      <td colspan="13" class="text-center text-muted">No bookings found.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php include("../layout/footer.php"); ?>