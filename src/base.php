<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy(); 
    header("Location: index.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aishwaraya Clinic</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato&display=swap">
    <link rel="shortcut icon" type="image/x-icon" href="https://asraaz.blr1.digitaloceanspaces.com/favi/374_2024_2048_clinic.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    

</head>
<body >
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="padding-top: 2rem; padding-bottom: 2rem; margin-bottom: 0;">
    <img src="images/clinic.jpg" alt="Clinic" style="border-radius:50%;height:100px;weight:100px;margin-left:4rem;">
    <a class="navbar-brand" href="#" style="margin-left: 1rem;">Aishwarya Clinic</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav" style="margin-right: 2rem;">
            <?php if (isset($_SESSION['patient_id'])): ?>
                <li class="nav-item active">
                    <a class="nav-link <?php echo (isset($title) && $title == 'Home') ? 'active' : ''; ?>" href="index.php" style="font-size: 1.2rem; margin-right: 1rem;">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($title) && $title == 'My Bookings') ? 'active' : ''; ?>" href="booking_list.php" style="font-size: 1.2rem; margin-right: 1rem;">My Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="base.php?logout=true" style="font-size: 1.2rem;">Logout</a>
                </li>
            <?php elseif (isset($_SESSION['doctor_id'])): ?>
                <!-- Similar updates for doctor links -->
            <?php else: ?>
                <li class="nav-item active">
                    <a class="nav-link <?php echo (isset($title) && $title == 'Home') ? 'active' : ''; ?>" href="index.php" style="font-size: 1.2rem; margin-right: 1rem;">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($title) && $title == 'Patient signup') ? 'active' : ''; ?>" href="patient_signup.php" style="font-size: 1.2rem; margin-right: 1rem;">Patient Signup</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($title) && $title == 'Doctor signup') ? 'active' : ''; ?>" href="doctor_signup.php" style="font-size: 1.2rem; margin-right: 1rem;">Doctor Signup</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($title) && $title == 'Login') ? 'active' : ''; ?>" href="login.php" style="font-size: 1.2rem;">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

    <div class="container mt-5">
        <?php 
        if (isset($content) && !empty($content)) {
            include($content); 
        }
        ?>
    </div>
    
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>
