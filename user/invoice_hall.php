<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

if (!isset($_GET['booking_id'])) {
  echo "Booking ID is required.";
  exit;
}

$booking_id = $_GET['booking_id'];

// Fetch booking details from the database
try {
  $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';

  $sql = "SELECT hb.booking_id, hb.hall_type, hb.hall_number, hb.price_per_hour, hb.description, hb.booking_status, hb.payment_status, hb.check_in, hb.check_out, hb.total_price, e.images
            FROM hall_bookings hb
            JOIN event_halls e ON hb.hall_number = e.hall_number
            WHERE hb.booking_id = :booking_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":booking_id", $booking_id, PDO::PARAM_INT);
  $stmt->execute();
  $booking = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$booking) {
    echo "No booking found.";
    exit;
  }
} catch (PDOException $e) {
  echo "Error fetching booking data: " . $e->getMessage();
  exit;
}

if (!class_exists('Mpdf\Mpdf')) {
  echo 'mPDF class not found!';
  exit;
}

// Create mPDF instance
try {
  $mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'orientation' => 'P',
    'margin_top' => 10,
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_bottom' => 20,
    'font_size' => 10
  ]);

  // Define HTML content with the requested design improvements
  $html = "
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #004d99;
            text-transform: uppercase;
            margin: 0;
        }
        .invoice-header p {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }
        .intro {
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin-top: 10px;
            text-align: justify;
        }
        .facility-description {
            margin-top: 15px;
            font-size: 12px;
            color: #333;
        }
        .facility-description ul {
            list-style-type: square;
            margin-left: 20px;
        }
        .invoice-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .invoice-details th, .invoice-details td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .invoice-details th {
            background-color: #004d99;
            color: white;
            font-size: 14px;
        }
        .invoice-details td {
            font-size: 12px;
            color: #333;
        }
        .total-price {
            font-size: 14px;
            text-align: right;
            margin-top: 15px;
            font-weight: bold;
        }
        .status-info {
            font-size: 12px;
            text-align: right;
            margin-top: 10px;
            font-weight: bold;
        }
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            padding-bottom: 10px;
        }
        .footer p {
            margin: 5px 0;
        }
        .signature-section {
            margin-top: 20px;
            text-align: left;
            background-color: #f4f4f4;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #ddd;
            width: 180px;
        }
        .signature-section img {
            width: 100%;
        }
        .signature-section p {
            font-size: 14px;
            font-weight: bold;
            margin-top: 8px;
            text-transform: uppercase;
        }
        .stamp {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
            font-weight: bold;
            color: #004d99;
            border: 2px dashed #004d99;
            padding: 6px;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }
    </style>

    <div class='invoice-header'>
        <h1>Hotel Paradise</h1>
        <p>A Luxurious Stay Awaits You</p>
    </div>

    <div class='intro'>
        <p>Welcome to Hotel Paradise, where luxury meets comfort. Our hotel offers a range of services and amenities designed to make your stay unforgettable. Whether you are visiting for business or leisure, we promise you an extraordinary experience with world-class facilities and personalized service.</p>
    </div>

    <div class='facility-description'>
        <p>Our hotel features a variety of facilities to make your stay as comfortable as possible:</p>
        <ul>
            <li>Luxury Rooms with Modern Amenities</li>
            <li>Fine Dining Restaurants</li>
            <li>State-of-the-Art Conference Rooms</li>
            <li>Spa and Wellness Center</li>
            <li>Beautiful Garden and Outdoor Swimming Pool</li>
            <li>24/7 Concierge and Room Service</li>
        </ul>
    </div>

    <table class='invoice-details'>
        <tr>
            <th>Booking ID</th>
            <th>Room Type</th>
            <th>Room Number</th>
            <th>Price/Hour</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Booking Status</th>
            <th>Payment Status</th>
        </tr>
        <tr>
            <td>{$booking['booking_id']}</td>
            <td>{$booking['hall_type']}</td>
            <td>{$booking['hall_number']}</td>
            <td>₹{$booking['price_per_hour']}</td>
            <td>{$booking['check_in']}</td>
            <td>{$booking['check_out']}</td>
            <td>{$booking['booking_status']} </td>
            <td>{$booking['payment_status']} </td>
        </tr>
    </table>
    <div class='total-price'>
        <p><strong>Total Price:</strong> ₹{$booking['total_price']}</p>
    </div>

    <div class='stamp'>
        HOTEL PARADISE STAMP
    </div>
    
    <p style='text-align:center; margin-top:20px;'>Thank you, $username</p>

    <div class='signature-section'>
        <img src='/var/www/html/palmparadise/dist/img/signature.png' alt='Signature of Jay Goyani'>
        <p>Jay Goyani</p>
        <p>Founder & CEO</p>
    </div>

    <div class='footer'>
        <p>Hotel Paradise | Address: 777-787 Rajhans Montessa, Dumas Rd,  beside Le Meridien Hotel, near Airport, Surat, Gujarat 395007 </p>
        <p>Phone: (+91)82389 38615 | Email: info@hotelparadise.com | jaygoyani939@gmail.com</p>
    </div>
    ";

  $mpdf->WriteHTML($html);

  // $mpdf->Output("invoice_sample.pdf", ($action === 'download') ? 'D' : 'I');

  if ($action === 'download') {
    $mpdf->Output("invoice_{$booking['booking_id']}.pdf", 'D');
  } else {
    $mpdf->Output("invoice_{$booking['booking_id']}.pdf", 'I');
  }
  exit;
} catch (Exception $e) {
  echo "Error generating PDF: " . $e->getMessage();
  exit;
}
