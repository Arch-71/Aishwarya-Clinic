<?php
include 'db.php'; // Include the database connection
session_start(); // Start the session
$title = 'Booking History';

// Check if the user is logged in as a doctor
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch all bookings for the logged-in doctor, including canceled ones
$stmt = $pdo->prepare("SELECT b.id, b.slot, b.time, b.disease, p.name AS patient_name, p.email AS patient_email, p.phone AS patient_phone, b.status FROM bookings b JOIN patients p ON b.patient_id = p.id WHERE b.doctor_id = ?");
$stmt->execute([$_SESSION['doctor_id']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Include your custom styles -->
</head>
<body>
    <?php include 'base.php'; // Include the navigation bar ?>

    <div class="container mt-5">
        <h2 class="text-center">Booking History</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Patient Email</th>
                    <th>Patient Phone</th>
                    <th>Slot</th>
                    <th>Time</th>
                    <th>Disease</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No bookings found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['patient_email']); ?></td>
                            <td><?php echo htmlspecialchars($booking['patient_phone']); ?></td>
                            <td><?php echo htmlspecialchars($booking['slot']); ?></td>
                            <td><?php echo htmlspecialchars($booking['time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['disease']); ?></td>
                            <td><?php echo htmlspecialchars($booking['status']); ?></td> <!-- Display status -->
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