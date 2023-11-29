<?php
include '../connection.php';

// Retrieve the booking details from the query parameters
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$movieName = isset($_GET['movieName']) ? $_GET['movieName'] : '';
$selectedDate = isset($_GET['selectedDate']) ? $_GET['selectedDate'] : '';

$selectedShowTime = isset($_GET['selectedShowTime']) ? $_GET['selectedShowTime'] : '';

$bookedSeats = isset($_GET['bookedSeats']) ? $_GET['bookedSeats'] : '';
$totalBookedSeats = isset($_GET['totalBookedSeats']) ? $_GET['totalBookedSeats'] : '';
$totalAmount = isset($_GET['totalAmount']) ? $_GET['totalAmount'] : '';

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
        /* Your CSS styles here */
    </style>
</head>
<body>
    <h1>Booking Success</h1>
    <div class="bill">
        <p><strong>Name:</strong> ' . $name . '</p>
        <p><strong>Movie Name:</strong> ' . $movieName . '</p>
        <p><strong>Show Date:</strong> ' . $selectedDate . '</p>
        <p><strong>Show Time:</strong> ' . $selectedShowTime . '</p>
        
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
