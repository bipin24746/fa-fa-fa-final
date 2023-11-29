<?php
include '../connection.php';

// Retrieve the booking details from the query parameters
$user_id = $_GET['user_id'];
$name = $_GET['name'];
$movieName = $_GET['movieName'];
$selectedDate = $_GET['selectedDate'];
$booking_date = $_GET['booking_date'];
$booking_time = $_GET['booking_time'];
$selectedShowTime = $_GET['selectedShowTime'];
$bookingDate = $_GET['bookingDate'];
$bookedSeats = $_GET['bookedSeats'];
$totalBookedSeats = $_GET['totalBookedSeats'];
$totalAmount = $_GET['totalAmount'];

// Set the filename for the downloaded bill
$filename = 'booking_bill_' . date('Y-m-d_H-i-s') . '.html';

// Generate the bill content with HTML and CSS
$billContent = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Bill</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
        }
        .bill {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            background-color: #fff;
        }
        .bill p {
            margin: 5px 0;
        }
        .bill strong {
            display: inline-block;
            width: 120px;
        }
        .total-amount {
            margin-top: 20px;
        }
        .thank-you {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Booking Success</h1>
    <div class="bill">
        <p><strong>Name:</strong> ' . $name . '</p>
        <p><strong>Movie Name:</strong> ' . $movieName . '</p>
        <p><strong>Show Date:</strong> ' . $selectedDate . '</p>
        <p><strong>Show Time:</strong> ' . $selectedShowTime . '</p>
        <p><strong>Booking Date:</strong> ' . $booking_date . '</p>
        <p><strong>Booking Time:</strong> ' . $booking_time . '</p>
        <p><strong>Seat Numbers:</strong> ' . $bookedSeats . '</p>
        <p><strong>Total Seats:</strong> ' . $totalBookedSeats . '</p>
    </div>
    <div class="total-amount">
        <span>Total Amount Needs to Pay:</span>
        <span>Rs. ' . $totalAmount . '</span>
    </div>
    <div class="thank-you">
        <p>Thank You</p>
        <p>Thank you for booking with us. Enjoy the movie!</p>
    </div>
</body>
</html>';

// Send appropriate headers for file download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Output the bill content to the user for download
echo $billContent;
exit();
?>
