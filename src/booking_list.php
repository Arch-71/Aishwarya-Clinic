<?php
include 'db.php'; // Include the database connection
session_start(); // Start the session
$title = 'My Bookings';

// Check if the user is logged in
if (!isset($_SESSION['patient_id']) && !isset($_SESSION['doctor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Initialize an empty array for bookings
$bookings = [];

// If the user is a patient, fetch their bookings
if (isset($_SESSION['patient_id'])) {
    $stmt = $pdo->prepare("SELECT b.id, b.slot, b.time, b.disease, b.patient_name, b.patient_email, b.patient_phone, d.name AS doctor_name FROM bookings b JOIN doctors d ON b.doctor_id = d.id WHERE b.patient_id = ?");
    $stmt->execute([$_SESSION['patient_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// If the user is a doctor, fetch all bookings
if (isset($_SESSION['doctor_id'])) {
    $stmt = $pdo->prepare("SELECT b.id, b.slot, b.time, b.disease, p.name AS patient_name, p.email AS patient_email, p.phone AS patient_phone, b.status FROM bookings b JOIN patients p ON b.patient_id = p.id WHERE b.doctor_id = ?");
    $stmt->execute([$_SESSION['doctor_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle cancel booking
if (isset($_GET['cancel'])) {
    $bookingId = $_GET['cancel'];
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'canceled' WHERE id = ?");
    if ($stmt->execute([$bookingId])) {
        header("Location: booking_list.php"); // Redirect to the booking list after cancellation
        exit();
    } else {
        echo "<script>alert('Failed to cancel booking. Please try again.');</script>";
    }
}

// Handle complete booking
if (isset($_GET['complete'])) {
    $bookingId = $_GET['complete'];
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'completed' WHERE id = ?");
    if ($stmt->execute([$bookingId])) {
        header("Location: booking_list.php"); // Redirect to the booking list after marking as complete
        exit();
    } else {
        echo "<script>alert('Failed to mark booking as completed. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Include your custom styles -->
</head>
<body>
    <?php include 'base.php'; // Include the navigation bar ?>

    <div class="container mt-5">
        <h2 class="text-center">Booking List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <?php if (isset($_SESSION['patient_id'])): ?>
                        <th>Doctor Name</th>
                    <?php else: ?>
                        <th>Patient Name</th>
                    <?php endif; ?>
                    <th>Patient Email</th>
                    <th>Patient Phone</th>
                    <th>Slot</th>
                    <th>Time</th>
                    <th>Disease</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="8" class="text-center">No bookings found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <?php if (isset($_SESSION['patient_id'])): ?>
                                <td><?php echo htmlspecialchars($booking['doctor_name']); ?></td>
                            <?php else: ?>
                                <td><?php echo htmlspecialchars($booking['patient_name']); ?></td>
                            <?php endif; ?>
                            <td><?php echo htmlspecialchars($booking['patient_email']); ?></td>
                            <td><?php echo htmlspecialchars($booking['patient_phone']); ?></td>
                            <td><?php echo htmlspecialchars($booking['slot']); ?></td>
                            <td><?php echo htmlspecialchars($booking['time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['disease']); ?></td>
                            <td><?php echo htmlspecialchars($booking['status']); ?></td> <!-- Display status -->
                            <td>
                                <a href="edit.php?id=<?php echo $booking['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="booking_list.php?cancel=<?php echo $booking['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</a>
                                <?php if (isset($_SESSION['doctor_id'])): ?>
                                    <a href="booking_list.php?complete=<?php echo $booking['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Mark this checkup as completed?');">Complete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>