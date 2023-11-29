<?php
include 'header.php';
require '../connection.php';
session_start();
if (!isset($_SESSION['email'])) {
    header("location:login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['mark_paid'])) {
        $bookingId = $_POST['booking_id'];
        $booking_date=$_POST['booking_date'];
        $booking_time=$_POST['booking_time'];
        // Update the paid status in the database
        $updateSql = "UPDATE booking SET paid = 1 WHERE booking_date='$booking_date' AND booking_time='$booking_time'";
        $conn->query($updateSql);
    } elseif (isset($_POST['delete_booking'])) {
        $bookingId = $_POST['booking_id'];

        // Delete the booking from the database
        $deleteSql = "DELETE FROM booking WHERE id = $bookingId";
        $conn->query($deleteSql);
    }

    // Redirect to the same page to prevent form resubmission
    header("Location: $_SERVER[PHP_SELF]");
    exit;
}

$sql = "SELECT booking.*, movie.name AS movie_name, GROUP_CONCAT(seat_num ORDER BY seat_num) AS seat_numbers, COUNT(*) AS num_seats_booked, user.name AS user_name
        FROM booking
        JOIN movie ON booking.movie_id = movie.id
        JOIN user ON booking.user_id = user.id
        GROUP BY booking_date, booking_time, user.id";

$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo "<div class='booking-list'>";
    echo "<h2>Booking List</h2>";

    echo "<form method='post'>";
    echo "<table>";
    echo "<tr>
            <th>Name</th>
            <th>Movie Name</th>
            <th>Show Date</th>
            <th>Show Time</th>
            <th>Seats Booked</th>
            <th>Total Price</th>
            <th>Paid Status</th>
            <th>Action</th>
            <th>Payments</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        $name = $row['user_name'];
        $imgname = $row['image_path'];
        $movieName = $row['movie_name'];
        $showDate = date('F j, Y', strtotime($row['show_date'])); // Format the date
        $showTime = date('h:i A', strtotime($row['show_time'])); // Format the time
        $seatNumbers = $row['seat_numbers'];
        $numSeatsBooked = $row['num_seats_booked'];
        $totalPrice = $row['total_price']; // Replace 'total_price' with your actual column name
        $paid = $row['paid'];

        echo "<tr>
                <td>$name</td>
                <td>$movieName</td>
                <td>$showDate</td>
                <td>$showTime</td>
                <td>$seatNumbers</td>
                <td>$totalPrice</td>
                
                <td id='paid-status-{$row['id']}'>" . ($paid ? 'Paid' : 'Not Paid') . "</td>
                
                <td>
                <form method='post'>
                    <input type='hidden' name='booking_id' value='{$row['id']}'>
                    <input type='hidden' name='booking_date' value='{$row['booking_date']}'>
                    <input type='hidden' name='booking_time' value='{$row['booking_time']}'>
                    <input type='submit' name='mark_paid' value='Mark as Paid'>
                    <input type='hidden' name='booking_date' value='{$row['booking_date']}'>
                    <input type='hidden' name='booking_time' value='{$row['booking_time']}'>
                        <input type='hidden' name='booking_id' value='{$row['id']}'><br>
                        <input type='submit' name='delete_booking' value='Delete booked'>
                    </form>
                </td>
                <td><img src='../uploads/$imgname' style='height:100px; width:100px;'  id ='jsuse'>  </td>
              </tr>";
    }

    echo "</table>";
    echo "</form>";
    echo "</div>";
} else {
    echo "No bookings found.";
}

$conn->close();
?>
<link rel="stylesheet" href="booking.css">


<script>


var img = document.getElementsByTagName('img');

for (var i = 0; i < img.length; i++) {
    img[i].onclick = function() {
        this.style.height = '350px';
        this.style.width = '700px';
    };

    img[i].onmouseout = function() {
        this.style.height = '100px';
        this.style.width = '100px';
    };
}
</script>