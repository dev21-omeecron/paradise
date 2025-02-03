  <?php

  require_once('../dbcon.php');

  if (!validateSession()) {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit();
  }

  $action = isset($_POST['action']) ? $_POST['action'] : '';

  switch ($action) {
    case 'fetchHalls':
      fetchHalls($_POST['hall_type']);
      break;

    case 'fetchDetails':
      fetchHallDetails($_POST['hall_number']);
      break;

    case 'bookHall':
      bookHall($_POST);
      break;

    default:
      echo "Invalid action.";
      break;
  }

  function fetchHalls($hall_type)
  {
    global $conn;
    $sql = "SELECT hall_number FROM event_halls WHERE hall_type = :hall_type";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":hall_type", $hall_type, PDO::PARAM_STR);
    $stmt->execute();
    $halls = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $options = '<option value="">Select Hall Number</option>';
    foreach ($halls as $hall) {
      $options .= '<option value="' . $hall['hall_number'] . '">' . $hall['hall_number'] . '</option>';
    }
    echo $options;
  }

  function fetchHallDetails($hall_number)
  {
    global $conn;
    try {
      $sql = "SELECT hall_id, capacity, price_per_hour, description, images FROM event_halls WHERE hall_number = :hall_number";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(":hall_number", $hall_number, PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        header('Content-Type: application/json');
        echo json_encode($result);
      } else {
        echo json_encode(['error' => 'Hall not found']);
      }
    } catch (PDOException $e) {
      echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
  }

  function bookHall($data)
  {
    global $conn;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

    if (empty($user_id)) {
      echo "Error: User not logged in!";
      return;
    }
    $hall_type = $data['hall_type'];
    $hall_number = $data['hall_number'];
    $capacity = $data['capacity'];
    $price_per_hour = $data['price_per_hour'];
    $description = $data['description'];
    $check_in = $data['check_in'];
    $check_out = $data['check_out'];
    $total_price = $data['total_price'];

    // Fetch hall_id based on hall_number
    $sql = "SELECT hall_id FROM event_halls WHERE hall_number = :hall_number LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':hall_number', $hall_number, PDO::PARAM_STR);
    $stmt->execute();
    $hall = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($hall) {
      $hall_id = $hall['hall_id'];
    } else {
      echo "Error: Hall not found!";
      return;
    }

    $booking_id = mt_rand(100000, 999999);

    $sql = "INSERT INTO hall_bookings (booking_id, user_id, hall_id, username, email, hall_type, hall_number, capacity, price_per_hour, description, check_in, check_out, total_price, booking_status, payment_status, created_at)
            VALUES (:booking_id, :user_id, :hall_id, :username, :email, :hall_type, :hall_number, :capacity, :price_per_hour, :description, :check_in, :check_out, :total_price, 'confirmed', 'pending', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":booking_id", $booking_id, PDO::PARAM_INT);
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->bindParam(":hall_id", $hall_id, PDO::PARAM_INT);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":hall_type", $hall_type, PDO::PARAM_STR);
    $stmt->bindParam(":hall_number", $hall_number, PDO::PARAM_STR);
    $stmt->bindParam(":capacity", $capacity, PDO::PARAM_INT);
    $stmt->bindParam(":price_per_hour", $price_per_hour, PDO::PARAM_INT);
    $stmt->bindParam(":description", $description, PDO::PARAM_STR);
    $stmt->bindParam(":check_in", $check_in, PDO::PARAM_STR);
    $stmt->bindParam(":check_out", $check_out, PDO::PARAM_STR);
    $stmt->bindParam(":total_price", $total_price, PDO::PARAM_INT);

    if ($stmt->execute()) {
      echo "Booking successful";
    } else {
      echo "Error: " . $stmt->errorInfo()[2];
    }
  }
