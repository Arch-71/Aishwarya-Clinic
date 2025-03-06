<?php
include 'db.php'; // Include the database connection
$title = "Doctor Signup"; // Set the title for the page

// Initialize error message variable
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $specialization = $_POST['specialization'];
    $experience = $_POST['experience'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $docClinic = $_POST['docClinic'];

    
    $image = $_FILES['image'];
    $imagePath = 'uploads/' . basename($image['name']); 
    
    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare("INSERT INTO doctors (name, email, phone, specialization, experience, password, image, docClinic) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $specialization, $experience, $password, $imagePath, $docClinic])) {
            header("Location: login.php"); // Redirect to login page after successful signup
            exit();
        } else {
            $error = "Signup failed. Please try again.";
        }
    } else {
        $error = "Image upload failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
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
                        <h2 class="card-title text-center"><?php echo $title; ?></h2>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST" action="" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="specialization">Specialization</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" required>
                            </div>
                            <div class="form-group">
                                <label for="experience">Experience (years)</label>
                                <input type="number" class="form-control" id="experience" name="experience" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="docClinic">Clinic Name</label>
                                <input type="text" class="form-control" id="docClinic" name="docClinic" required>
                            </div>
                            <div class="form-group">
                    <label for="image">Choose Image</label>
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                </div>
                            <button type="submit" class="btn btn-primary btn-block">Signup</button>
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