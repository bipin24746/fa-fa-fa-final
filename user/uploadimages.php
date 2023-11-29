<?php
require '../connection.php';
include 'header.php';

// Check if the user is logged in and the email address is available in the session
if (!isset($_SESSION['email'])) {
    // Redirect the user to the login page or display an error message
    header('Location: login.php');
    exit;
}

// Get the email address of the logged-in user
$email = $_SESSION['email'];

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Check if there are uploaded files
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $bookingId = $_POST['booking_id']; // Assuming you have a hidden input field for booking_id in your form
        $uploadedImages = $_FILES['images'];
        $image_payment = $_FILES['images']['name'];
        // Define upload directory
        $uploadDir = '../uploads/';

        // Start a transaction to ensure consistency in the database
        $conn->begin_transaction();

        try {
            // Iterate through each uploaded file
            foreach ($uploadedImages['tmp_name'] as $key => $tmpName) {
                // Generate a unique filename
                $fileName = uniqid() . '_' . $uploadedImages['name'][$key];

                // Define the full path for the file
                $targetPath = $uploadDir . $fileName;

                // Move the uploaded file to the destination
                if (move_uploaded_file($tmpName, $targetPath)) {
                    // Update the 'image_path' column in the 'booking' table
                    $sqlUpdateBooking = $conn->prepare("UPDATE booking SET image_path = ? WHERE id = ?");
                    $sqlUpdateBooking->bind_param("si", $targetPath, $bookingId);
                
                    if (!$sqlUpdateBooking->execute()) {
                        throw new Exception("Error updating user image in the database: " . $conn->error);
                    }
                
                    $sqlUpdateBooking->close();
                } else {
                    throw new Exception("Error uploading file.");
                }
            }

            // Commit the transaction if all insertions are successful
            $conn->commit();

            echo "Images uploaded successfully!";
        } catch (Exception $e) {
            // Rollback the transaction in case of any error during insertions
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "No images selected for upload.";
    }
}
?>




<!DOCTYPE html>
<html>
<head>
    <title>Upload Images</title>
    <link rel="stylesheet" href="seats.css">
</head>
<body>
    <div class="container">
        <h1>Upload Images</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <!-- Assuming you have a hidden input field for booking_id -->
            <input type="hidden" name="booking_id" value="<?php echo $_GET['booking_id']; ?>">

            <label>Select Images:</label>
            <input type="file" name="images[]" multiple accept="image/*">

            <input type="submit" name="submit" value="Upload Images">
        </form>

        <a href="index.php">Back to Booking</a>
    </div>
</body>
</html>
