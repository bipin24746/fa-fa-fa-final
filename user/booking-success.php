<?php
require '../connection.php';
include 'header.php';

$user_id = $_SESSION['user_id'];

$sqlBookingDetails = "SELECT * FROM booking WHERE user_id = '$user_id' ORDER BY booking_date DESC, booking_time DESC LIMIT 1";
$resultBookingDetails = $conn->query($sqlBookingDetails);

if ($resultBookingDetails && $resultBookingDetails->num_rows > 0) {
    $row = $resultBookingDetails->fetch_assoc();
    $name = $_SESSION['user_name'];
    $movie_id = $row['movie_id'];
    $booking_date = $row['booking_date'];
    $booking_time = $row['booking_time'];
    $selectedDate = $row['show_date'];
    $selectedShowTime = $row['show_time'];
    $seat_id = $row['seat_num'];
    $totalAmount = $row['total_price'];

    // Retrieve movie details based on the movie_id
    $sqlMovieDetails = "SELECT * FROM movie WHERE id = '$movie_id'";
    $resultMovieDetails = $conn->query($sqlMovieDetails);

    if ($resultMovieDetails && $resultMovieDetails->num_rows > 0) {
        $movieData = $resultMovieDetails->fetch_assoc();
        $movieName = $movieData['name'];
    } else {
        $movieName = 'Movie Not Found';
    }

    // Retrieve the booked seats for the particular user
    $sqlBookedSeats = "SELECT GROUP_CONCAT(seat_num ORDER BY seat_num ASC) AS booked_seats 
                       FROM booking 
                       WHERE user_id = '$user_id' AND show_date = '$selectedDate' AND show_time = '$selectedShowTime'";
    $resultBookedSeats = $conn->query($sqlBookedSeats);

    if ($resultBookedSeats && $resultBookedSeats->num_rows > 0) {
        $data = $resultBookedSeats->fetch_assoc();
        $bookedSeats = $data['booked_seats'];
        $totalBookedSeats = count(explode(',', $bookedSeats));
    } else {
        $bookedSeats = '';
        $totalBookedSeats = 0;
    }
} else {
    echo "Booking details not found.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success</title>
    <link rel="stylesheet" href="booking-success.css"> <!-- You can create a CSS file for styling -->
</head>
<body>
    <div class="container">
        <h1>Booking Success</h1>
        <div class="bill">
            <p><strong>Name:</strong> <?php echo $name; ?></p>
            <p><strong>Movie Name:</strong> <?php echo $movieName; ?></p>
            <p><strong>Show Date:</strong> <?php echo $selectedDate; ?></p>
            <p><strong>Show Time:</strong> <?php echo $selectedShowTime; ?></p>
            <p><strong>Booking Date:</strong> <?php echo $booking_date; ?></p>
            <p><strong>Booking Time:</strong> <?php echo $booking_time; ?></p>
            <p><strong>Seat Numbers:</strong> <?php echo $bookedSeats; ?></p>
            <p><strong>Total Seats:</strong> <?php echo $totalBookedSeats; ?></p>
        </div>
        <div class="total-amount">
            <span>Total Amount Needs to Pay:</span>
            <span>Rs. <?php echo $totalAmount; ?></span>
        </div>
        <div>
            <p>Thank you for booking with us. Enjoy the movie!</p>
        </div>
        <a class="download-link" href="downloadbill.php?user_id=<?php echo $user_id; ?>&name=<?php echo $name; ?>&movieName=<?php echo $movieName; ?>&selectedDate=<?php echo $selectedDate; ?>&booking_date=<?php echo $booking_date; ?>&booking_time=<?php echo $booking_time; ?>&selectedShowTime=<?php echo $selectedShowTime; ?>&bookingDate=<?php echo $booking_date; ?>&bookedSeats=<?php echo $bookedSeats; ?>&totalBookedSeats=<?php echo $totalBookedSeats; ?>&totalAmount=<?php echo $totalAmount; ?>">Download Bill</a>
    </div>
</body>
</html>
