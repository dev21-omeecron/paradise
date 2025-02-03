<?php

require_once('dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

include(__DIR__ . "/layout/header.php");
include(__DIR__ . "/layout/sidebar.php");

// Fetch dashboard data using PDO
try {
  if ($_SESSION['role'] === 'admin') {
    // Admin Dashboard Data

    // Total Users Query (from both bookings)
    $totalUsersStmt = $conn->query("
      SELECT COUNT(DISTINCT user_id) as total 
      FROM (
        SELECT user_id FROM hall_bookings
        UNION
        SELECT user_id FROM room_bookings
      ) as users
    ");
    $totalUsers = $totalUsersStmt->fetchColumn();

    // Hall Bookings Statistics
    $hallBookingsStmt = $conn->query("SELECT COUNT(*) as total FROM hall_bookings");
    $totalHallBookings = $hallBookingsStmt->fetchColumn();

    $hallRevenueStmt = $conn->query("
      SELECT SUM(total_price) as revenue 
      FROM hall_bookings 
      WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
      AND YEAR(created_at) = YEAR(CURRENT_DATE())
    ");
    $hallRevenue = $hallRevenueStmt->fetchColumn() ?: 0;

    // Room Bookings Statistics
    $roomBookingsStmt = $conn->query("SELECT COUNT(*) as total FROM room_bookings");
    $totalRoomBookings = $roomBookingsStmt->fetchColumn();

    $roomRevenueStmt = $conn->query("
      SELECT SUM(total_price) as revenue 
      FROM room_bookings 
      WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
      AND YEAR(created_at) = YEAR(CURRENT_DATE())
    ");
    $roomRevenue = $roomRevenueStmt->fetchColumn() ?: 0;

    // Total Revenue
    $totalMonthlyRevenue = $hallRevenue + $roomRevenue;

    // Recent Hall Bookings
    $recentHallBookingsStmt = $conn->query("
      SELECT *, 'hall' as booking_type 
      FROM hall_bookings 
      ORDER BY created_at DESC LIMIT 5
    ");
    $recentHallBookings = $recentHallBookingsStmt->fetchAll();

    // Recent Room Bookings
    $recentRoomBookingsStmt = $conn->query("
      SELECT *, 'room' as booking_type 
      FROM room_bookings 
      ORDER BY created_at DESC LIMIT 5
    ");
    $recentRoomBookings = $recentRoomBookingsStmt->fetchAll();

    // Combine and sort recent bookings
    $recentBookings = array_merge($recentHallBookings, $recentRoomBookings);
    usort($recentBookings, function ($a, $b) {
      return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    $recentBookings = array_slice($recentBookings, 0, 5);

    // Monthly Bookings Data for Chart
    $monthlyHallBookingsStmt = $conn->query("
      SELECT COUNT(*) as count, DATE_FORMAT(created_at, '%Y-%m') as month 
      FROM hall_bookings 
      GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
      ORDER BY month DESC LIMIT 6
    ");
    $monthlyHallData = $monthlyHallBookingsStmt->fetchAll();

    $monthlyRoomBookingsStmt = $conn->query("
      SELECT COUNT(*) as count, DATE_FORMAT(created_at, '%Y-%m') as month 
      FROM room_bookings 
      GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
      ORDER BY month DESC LIMIT 6
    ");
    $monthlyRoomData = $monthlyRoomBookingsStmt->fetchAll();
  } else {
    // User Dashboard Data
    $userId = $_SESSION['user_id'];

    // User's Hall Bookings
    $userHallBookingsStmt = $conn->query("
      SELECT COUNT(*) as total 
      FROM hall_bookings 
      WHERE user_id = $userId
    ");
    $userHallBookings = $userHallBookingsStmt->fetchColumn();

    // User's Room Bookings
    $userRoomBookingsStmt = $conn->query("
      SELECT COUNT(*) as total 
      FROM room_bookings 
      WHERE user_id = $userId
    ");
    $userRoomBookings = $userRoomBookingsStmt->fetchColumn();

    // User's Recent Bookings (Both Hall and Room)
    $userRecentHallBookingsStmt = $conn->query("
      SELECT *, 'hall' as booking_type 
      FROM hall_bookings 
      WHERE user_id = $userId 
      ORDER BY created_at DESC LIMIT 5
    ");
    $userRecentHallBookings = $userRecentHallBookingsStmt->fetchAll();

    $userRecentRoomBookingsStmt = $conn->query("
      SELECT *, 'room' as booking_type 
      FROM room_bookings 
      WHERE user_id = $userId 
      ORDER BY created_at DESC LIMIT 5
    ");
    $userRecentRoomBookings = $userRecentRoomBookingsStmt->fetchAll();

    // Combine and sort user's recent bookings
    $userRecentBookings = array_merge($userRecentHallBookings, $userRecentRoomBookings);
    usort($userRecentBookings, function ($a, $b) {
      return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    $userRecentBookings = array_slice($userRecentBookings, 0, 5);
  }
} catch (PDOException $e) {
  error_log("Dashboard Error: " . $e->getMessage());
  $error = "An error occurred while loading the dashboard data.";
}
?>

<!-- Additional CSS -->
<link href="https://cdn.jsdelivr.net/npm/apexcharts@3.40.0/dist/apexcharts.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
<style>
  .small-box {
    border-radius: 10px;
    position: relative;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
  }

  .small-box:hover {
    transform: translateY(-5px);
  }

  .small-box .icon {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 40px;
    opacity: 0.3;
  }

  .small-box h3 {
    font-size: 2.2rem;
    margin: 0 0 10px 0;
    white-space: nowrap;
  }

  .small-box p {
    font-size: 1rem;
  }

  .bg-info {
    background-color: #17a2b8 !important;
    color: white;
  }

  .bg-success {
    background-color: #28a745 !important;
    color: white;
  }

  .bg-warning {
    background-color: #ffc107 !important;
    color: #1f2d3d;
  }

  .bg-danger {
    background-color: #dc3545 !important;
    color: white;
  }

  .bg-primary {
    background-color: #007bff !important;
    color: white;
  }

  .bg-secondary {
    background-color: #6c757d !important;
    color: white;
  }

  .recent-bookings {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .chart-container {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
  }

  .booking-type-badge {
    padding: 0.25em 0.6em;
    font-size: 12px;
    font-weight: 600;
    border-radius: 3px;
    text-transform: uppercase;
  }

  .booking-type-hall {
    background-color: #e3f2fd;
    color: #0d47a1;
  }

  .booking-type-room {
    background-color: #f3e5f5;
    color: #7b1fa2;
  }
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Dashboard</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php else: ?>

        <?php if ($_SESSION['role'] === 'admin'): ?>
          <!-- Admin Dashboard -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php echo $totalUsers; ?></h3>
                  <p>Total Users</p>
                </div>
                <div class="icon">
                  <i class="fas fa-users"></i>
                </div>
                <a href="<?= ADMIN_URL ?>/user_list.php" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?php echo $totalHallBookings; ?></h3>
                  <p>Hall Bookings</p>
                </div>
                <div class="icon">
                  <i class="fas fa-calendar-check"></i>
                </div>
                <a href="<?= ADMIN_URL ?>/hallbooking_details.php" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?php echo $totalRoomBookings; ?></h3>
                  <p>Room Bookings</p>
                </div>
                <div class="icon">
                  <i class="fas fa-bed"></i>
                </div>
                <a href="<?= ADMIN_URL ?>/roombooking_details.php" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>₹<?php echo number_format($totalMonthlyRevenue); ?></h3>
                  <p>Total Revenue This Month</p>
                </div>
                <div class="icon">
                  <i class="fas fa-rupee-sign"></i>
                </div>
                <a href="#" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
          </div>

          <!-- Revenue Breakdown -->
          <div class="row">
            <div class="col-md-6">
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3>₹<?php echo number_format($hallRevenue); ?></h3>
                  <p>Hall Revenue This Month</p>
                </div>
                <div class="icon">
                  <i class="fas fa-chart-line"></i>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="small-box bg-secondary">
                <div class="inner">
                  <h3>₹<?php echo number_format($roomRevenue); ?></h3>
                  <p>Room Revenue This Month</p>
                </div>
                <div class="icon">
                  <i class="fas fa-chart-bar"></i>
                </div>
              </div>
            </div>
          </div>

        <?php else: ?>
          <!-- User Dashboard -->
          <div class="row">
            <div class="col-lg-6 col-12">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php echo $userHallBookings; ?></h3>
                  <p>Your Hall Bookings</p>
                </div>
                <div class="icon">
                  <i class="fas fa-calendar-check"></i>
                </div>
                <a href="<?= USER_URL ?>/eventhall_history.php" class="small-box-footer">
                  View Details <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-6 col-12">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?php echo $userRoomBookings; ?></h3>
                  <p>Your Room Bookings</p>
                </div>
                <div class="icon">
                  <i class="fas fa-bed"></i>
                </div>
                <a href="<?= USER_URL ?>/room_history.php" class="small-box-footer">
                  View Details <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Recent Bookings -->
        <div class="row">
          <div class="col-md-12">
            <div class="recent-bookings">
              <h5>Recent Bookings</h5>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Type</th>
                      <th>User</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $bookings = $_SESSION['role'] === 'admin' ? $recentBookings : $userRecentBookings;
                    foreach ($bookings as $booking):
                    ?>
                      <tr>
                        <td>
                          <span class="booking-type-badge booking-type-<?php echo $booking['booking_type']; ?>">
                            <?php echo ucfirst($booking['booking_type']); ?>
                          </span>
                        </td>
                        <td><?php echo htmlspecialchars($booking['username']); ?></td>
                        <td><?php echo date('d M Y', strtotime($booking['created_at'])); ?></td>
                        <td>
                          <span class="badge badge-<?php echo $booking['booking_status'] == 'confirmed' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($booking['booking_status']); ?>
                          </span>
                        </td>
                        <td>₹<?php echo number_format($booking['total_price']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <?php if ($_SESSION['role'] === 'admin'): ?>
          <!-- Booking Trends Chart -->
          <div class="row mt-4">
            <div class="col-md-12">
              <div class="chart-container">
                <h5>Monthly Booking Trends</h5>
                <div id="bookingTrendsChart"></div>
              </div>
            </div>
          </div>
        <?php endif; ?>

      <?php endif; ?>
    </div>
  </section>
</div>

<?php include(__DIR__ . "/layout/footer.php"); ?>

<!-- Required Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.40.0/dist/apexcharts.min.js"></script>
<script>
  <?php if ($_SESSION['role'] === 'admin'): ?>
    // Monthly Bookings Chart
    document.addEventListener('DOMContentLoaded', function() {
      var hallData = <?php echo json_encode($monthlyHallData); ?>;
      var roomData = <?php echo json_encode($monthlyRoomData); ?>;

      // Process data for chart
      var months = [...new Set([
        ...hallData.map(item => item.month),
        ...roomData.map(item => item.month)
      ])].sort();

      var hallCounts = months.map(month => {
        const found = hallData.find(item => item.month === month);
        return found ? parseInt(found.count) : 0;
      });

      var roomCounts = months.map(month => {
        const found = roomData.find(item => item.month === month);
        return found ? parseInt(found.count) : 0;
      });

      var options = {
        series: [{
          name: 'Hall Bookings',
          data: hallCounts
        }, {
          name: 'Room Bookings',
          data: roomCounts
        }],
        chart: {
          type: 'bar',
          height: 350,
          stacked: true,
          toolbar: {
            show: false
          }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 4,
          },
        },
        dataLabels: {
          enabled: false
        },
        xaxis: {
          categories: months,
        },
        colors: ['#17a2b8', '#28a745'],
        fill: {
          opacity: 0.9
        },
        legend: {
          position: 'top',
          horizontalAlign: 'right'
        },
        title: {
          text: 'Monthly Booking Trends',
          align: 'left',
          style: {
            fontSize: '16px'
          }
        }
      };

      var chart = new ApexCharts(document.querySelector("#bookingTrendsChart"), options);
      chart.render();
    });
  <?php endif; ?>

  // Auto refresh dashboard data every 5 minutes
  setInterval(function() {
    location.reload();
  }, 300000);
</script>