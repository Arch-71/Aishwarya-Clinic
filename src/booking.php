<?php
include 'db.php'; // Include the database connection
session_start(); // Start the session
$title = 'Book Appointment';

// Check if the user is logged in as a patient
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch patient details from the database
$stmt = $pdo->prepare("SELECT id, name, email, phone FROM patients WHERE id = ?");
$stmt->execute([$_SESSION['patient_id']]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize variables
$message = '';
$doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : null;

// Debugging: Check the GET parameters
//var_dump($_GET); // This will show all the GET parameters

// Check if doctor_id is provided
if (!$doctor_id) {
    die("Error: Doctor ID is missing."); // You can change this to a redirect if preferred
}

// Check if the doctor exists
$stmt = $pdo->prepare("SELECT id FROM doctors WHERE id = ?");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch();

if (!$doctor) {
    die("Error: Doctor ID does not exist.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slot = $_POST['slot'];
    $time = $_POST['time'];
    $disease = $_POST['disease'];

    // Insert booking into the database
    $stmt = $pdo->prepare("INSERT INTO bookings (patient_id, doctor_id, patient_name, patient_email, patient_phone, slot, time, disease) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    try {
        if ($stmt->execute([$patient['id'], $doctor_id, $patient['name'], $patient['email'], $patient['phone'], $slot, $time, $disease])) {
            $message = "Appointment registered successfully!";
        } else {
            $message = "Failed to register appointment. Please try again.";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Include your custom styles -->
</head>
<body>
    <?php include 'base.php'; // Include the navigation bar ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6"> <!-- Set the width of the form -->
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">Book Appointment</h2>
                        <?php if ($message): ?>
                            <div class="alert alert-info text-center"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor_id); ?>"> <!-- Hidden field for doctor ID -->
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="slot">Select Slot</label>
                                <select class="form-control" id="slot" name="slot" required>
                                    <option value="">Select a slot</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Evening">Evening</option>
                                    <option value="Night">Night</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="time">Time</label>
                                <input type="time" class="form-control" id="time" name="time" required>
                            </div>
                            <div class="form-group">
                                <label for="disease">Disease</label>
                                <input type="text" class="form-control" id="disease" name="disease" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Book Appointment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>