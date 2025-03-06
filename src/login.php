
<?php
include 'db.php'; // Include the database connection
session_start(); // Start the session
$title = 'Login';
// Initialize error message variable
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user is a patient
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE email = ?");
    $stmt->execute([$email]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($patient && password_verify($password, $patient['password'])) {
        // Set session variables for patient
        $_SESSION['patient_id'] = $patient['id'];
        header("Location: index.php"); // Redirect to the home page
        exit();
    }

    // Check if the user is a doctor
    $stmt = $pdo->prepare("SELECT * FROM doctors WHERE email = ?");
    $stmt->execute([$email]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($doctor && password_verify($password, $doctor['password'])) {
        // Set session variables for doctor
        $_SESSION['doctor_id'] = $doctor['id'];
        header("Location: index.php"); // Redirect to the home page
        exit();
    }

    // If login fails
    $error = "Invalid email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                        <h2 class="card-title text-center">Login</h2>
                        <?php if ($error): ?>
                            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                        <p class="text-center mt-3">Don't have an account? <a href="patient_signup.php">Sign up as Patient</a> | <a href="doctor_signup.php">Sign up as Doctor</a></p>
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