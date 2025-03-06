<?php
include 'db.php'; // Include the database connection
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['patient_id']) && !isset($_SESSION['doctor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Initialize variables
$booking = null;
$message = '';

// Check if the booking ID is provided
if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Fetch the booking details
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->execute([$bookingId]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the booking exists
    if (!$booking) {
        die("Booking not found.");
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $slot = $_POST['slot'];
        $time = $_POST['time'];
        $disease = $_POST['disease'];

        // Update the booking in the database
        $stmt = $pdo->prepare("UPDATE bookings SET slot = ?, time = ?, disease = ? WHERE id = ?");
        if ($stmt->execute([$slot, $time, $disease, $bookingId])) {
            $message = "Booking updated successfully!";
            // Optionally redirect to booking_list.php after successful update
            // header("Location: booking_list.php");
            // exit();
        } else {
            $message = "Failed to update booking. Please try again.";
        }
    }
} else {
    die("No booking ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Include your custom styles -->
</head>
<body>
    <?php include 'base.php'; // Include the navigation bar ?>

    <div class="container mt-5">
        <h2 class="text-center">Edit Booking</h2>
        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="slot">Select Slot</label>
                <select class="form-control" id="slot" name="slot" required>
                    <option value="Morning" <?php echo ($booking['slot'] == 'Morning') ? 'selected' : ''; ?>>Morning</option>
                    <option value="Evening" <?php echo ($booking['slot'] == 'Evening') ? 'selected' : ''; ?>>Evening</option>
                    <option value="Night" <?php echo ($booking['slot'] == 'Night') ? 'selected' : ''; ?>>Night</option>
                </select>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" class="form-control" id="time" name="time" value="<?php echo htmlspecialchars($booking['time']); ?>" required>
            </div>
            <div class="form-group">
                <label for="disease">Disease</label>
                <input type="text" class="form-control" id="disease" name="disease" value="<?php echo htmlspecialchars($booking['disease']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Update Booking</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>