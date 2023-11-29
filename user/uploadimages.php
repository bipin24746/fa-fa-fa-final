<?php
session_start(); // Move session_start to the beginning

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
    if (isset($_FILES['images']) && is_array($_FILES['images']['tmp_name']) && count($_FILES['images']['tmp_name']) > 0) {
        $bookingId = $_POST['booking_id']; // Assuming you have a hidden input field for booking_id in your form
        $uploadedImages = $_FILES['images'];
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
                    $uid = $_SESSION['user_id'];
                    $imagePath = $fileName; // Use the generated filename or adjust as needed

                    $sql = "UPDATE booking SET image_path = ? WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('si', $imagePath, $uid);
                    $stmt->execute();
                    $stmt->close();

                    echo "Image uploaded successfully!";
                } else {
                    echo "Image upload unsuccessful.";
                }
            }

            // Commit the transaction if all insertions are successful
            $conn->commit();
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
            <input type="file" name="images" accept=".jpg, .png, .jpeg">

            <input type="submit" name="submit" value="Upload Images">
        </form>

        <a href="index.php">Back to Booking</a>
    </div>
</body>
</html>
