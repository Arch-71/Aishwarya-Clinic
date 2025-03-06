<?php
include 'db.php';
session_start(); // Start the session
$title = 'Home';

// Fetch doctors from the database
$stmt = $pdo->query("SELECT * FROM doctors");
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    
    
</head>
<body>
    <?php include 'base.php'; ?>


   
    <div class="row mb-4" style="background-color: #add8e6; padding: 20px; border-radius: 5px; margin-top: 0; font-size: larger;">
        <div class="col-md-6">
            <h2>Aishwarya ⚕️ Clinic</h2>
            <p><strong>About Us:</strong> Established in December 2024, Aishwarya Clinic is dedicated to providing high-quality healthcare services to our community. We offer a wide range of medical treatments and personalized care to ensure the well-being of our patients.</p>
            <p><strong>Our Mission:</strong> Our mission is to deliver comprehensive, compassionate, and patient-centered healthcare. We strive to improve the quality of life for our patients by utilizing advanced medical techniques and fostering a supportive environment.</p>
        </div>
        <div class="col-md-6">
            <img src="images/1.jpg" class="img-fluid" alt="Clinic Image" style="border-radius: 10px;">
        </div>
    </div>

    
    <div class="container mt-5">
        <?php if (!isset($_SESSION['doctor_id'])): ?>
            <h2 class="text-center mb-4">Our Doctors</h2> 
        <?php endif; ?>
        
        <div class="row">
            <?php foreach ($doctors as $doctor): ?>
                <?php if (isset($_SESSION['doctor_id']) && $_SESSION['doctor_id'] == $doctor['id']): ?>
                    
                    <?php continue; ?>
                <?php endif; ?>
                
                <div class="col-md-4 mb-4 d-flex">
                    <div class="card" style="width: 100%; height: 100%;">
                        <img src="<?php echo htmlspecialchars($doctor['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($doctor['name']); ?>" style="height: 150px; width: 100%; object-fit: contain;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($doctor['name']); ?></h5>
                            <p class="card-text"><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
                            <p class="card-text"><strong>Experience:</strong> <?php echo htmlspecialchars($doctor['experience']); ?> years</p>
                            <p class="card-text"><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['phone']); ?></p>
                            <p class="card-text">
                                <strong>Email:</strong> 
                                <a href="mailto:<?php echo htmlspecialchars($doctor['email']); ?>">
                                    <?php echo htmlspecialchars($doctor['email']); ?>
                                </a>
                            </p>
                        </div>
                        <?php if (isset($_SESSION['patient_id'])): ?>
                            <a href="booking.php?doctor_id=<?php echo $doctor['id']; ?>" class="btn btn-primary">Book</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">Login to Book</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    
   <section class="container">
    <h1 class="topic text-center topic-border">Our Services</h1>
    <div class="row mt-3">
        
        <div class="col-lg-4 col-md-6">
            <div class="service-card animation">
                <div class="overflow-hidden position-relative">
                    <img  src="images/OIP.jpeg" alt="Web Design 1" style="height: 200px; width: 100% ;">
                </div>
                <div class="p-4">
                    <h5 class="card-topic">Well Equipped Operation Theatre</h5>
                    <p class="card-para">
                        The well-equipped operation theater featured advanced surgical instruments and state-of-the-art monitoring systems. Its...
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="service-card animation">
                <div class="overflow-hidden position-relative">
                    <img class="card-img" src="images/emerg.jpeg" alt="Web Design 1" style="height: 200px; width: 100% ;">
                </div>
                <div class="p-4">
                    <h5 class="card-topic">24 Hours Emergency</h5>
                    <p class="card-para">
                        In a 24-hour emergency, swift action and clear communication are crucial for ensuring safety and efficiency. A...
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="service-card animation">
                <div class="overflow-hidden position-relative">
                    <img class="card-img" src="images/ent.jpeg" alt="Web Design 1" style="height: 200px; width: 100% ;">
                </div>
                <div class="p-4">
                    <h5 class="card-topic">ENT Surgeries</h5>
                    <p class="card-para">
                        ENT surgeries, or ear, nose, and throat surgeries, address a variety of conditions affecting these critical areas....
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="service-card animation">
                <div class="overflow-hidden position-relative">
                    <img class="card-img " src="images/lab.jpeg" alt="Web Design 1" style="height: 200px; width: 100% ;">
                </div>
                <div class="p-4">
                    <h5 class="card-topic">Modern Labs</h5>
                    <p class="card-para">
                        Modern labs are equipped with advanced technology that enhances research and experimentation. They prioritize safety...
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="service-card animation">
                <div class="overflow-hidden position-relative">
                    <img class="card-img " src="images/med.jpeg" alt="Web Design 1" style="height: 200px; width: 100% ;">
                </div>
                <div class="p-4">
                    <h5 class="card-topic">Pharmacy Services</h5>
                    <p class="card-para">
                        On-site pharmacy for prescribed medications.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="service-card animation">
                <div class="overflow-hidden position-relative">
                    <img class="card-img " src="images/parent.jpeg" alt="Web Design 1" style="height: 200px; width: 100% ;">
                </div>
                <div class="p-4">
                    <h5 class="card-topic">Prenatal and Postnatal Care</h5>
                    <p class="card-para">
                        Guidance and checkups for expecting and new mothers.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

    
<section class="container-fluid">
    <h3 class="topic text-center topic-border">Contact Information</h3>
    <div class="row mt-5">
        <div class="col-lg-6">
            <div class="contact-box">
                <h1 class="topic">Send a message</h1>
                <form action="https://formspree.io/f/YOUR_FORM_ID" method="POST">
    <div class="row">
        <div class="col-md-6">
            <input type="text" placeholder="Your Name" name="name" id="name" required class="form-control">
        </div>
        <div class="col-md-6">
            <input type="number" placeholder="Your Phone" name="phone" id="phone" min="0" pattern="\d+" inputmode="numeric" required class="form-control">
        </div>
    </div>
    <input type="email" placeholder="Your Email" name="email" id="email" required class="form-control mt-2">
    <textarea name="message" placeholder="Message" id="message" cols="30" rows="5" required class="form-control mt-2"></textarea>
    <input type="hidden" name="uid" class="form-control ref" value="374">
    <button type="submit" class="btn btn-primary mx-2 px-4 py-2 rounded-4 mt-2">Submit</button>
</form>
                <div id="response-message"></div>
            </div>
        </div>
        <div class="col-lg-6">
                <a href="https://www.google.com/maps/dir/?api=1&destination=WJC9%2B98Q%2C+VP+Rd%2C+Old+Madiwala%2C+Madiwala%2C+1st+Stage%2C+BTM+Layout%2C+Bengaluru%2C+Karnataka+560068" target="_blank">
            <img src="images/map.png" alt="Map of Aishwarya Clinic" style="width: 100%; height: 90%;">
        </a>
            </div>
    </div>
</section>

  <footer class="bg-dark text-white p-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <p class="para fw-bold fs-5 topic-border">Services</p>
                <div class="service-list">
                    <a href="service/infertility-and-women-welfare-treatment" class="text-white">Infertility and Women Welfare Treatment</a><br>
                    <a href="service/laryngoscopy-otomicroscopy" class="text-white">Laryngoscopy & Otomicroscopy</a><br>
                    <a href="service/nasal-endoscopy" class="text-white">Nasal Endoscopy</a><br>
                    <a href="service/laparoscopy" class="text-white">Laparoscopy</a><br>
                    <a href="service/ent-surgeries" class="text-white">ENT Surgeries</a><br>
                    <a href="service/24-hours-emergency" class="text-white">24 Hours Emergency</a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <p class="para fw-bold fs-5 topic-border">Contact Address</p>
                <p>Aishwarya Clinic, VP Rd, Old Madiwala, Madiwala, 1st Stage, BTM Layout, Bengaluru, Karnataka 560068</p>
            </div>

            <div class="col-lg-4 col-md-6">
                <p class="para fw-bold fs-5 topic-border">Social Media</p>
                <div class="d-flex gap-3">
                    <a class="text-white" href="https://www.facebook.com/#" target="_blank">
                        <i class="fa-brands fa-facebook fs-4"></i>
                    </a>
                    <a class="text-white" href="https://www.instagram.com/#" target="_blank">
                        <i class="fa-brands fa-instagram fs-4"></i>
                    </a>
                    <a class="text-white" href="https://www.twitter.com/#" target="_blank">
                        <i class="fa-brands fa-twitter fs-4"></i>
                    </a>
                    <a class="text-white" href="https://www.linkedin.com/#" target="_blank">
                        <i class="fa-brands fa-linkedin fs-4"></i>
                    </a>
                    <a class="text-white" href="https://www.youtube.com/#" target="_blank">
                        <i class="fa-brands fa-youtube fs-4"></i>
                    </a>
                </div>
            </div>
        </div>
        <hr class="bg-white">
        <p class="para text-center">© 2024. All rights reserved. </p>
    </div>
</footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize the carousel
        $('.owl-carousel').owlCarousel({
            loop: true,
            center: true,
            items: 1,
            margin: 10,
            nav: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            dots: false,
            navText: ['&#8592;', '&#8594'],
        });

       
   
    
    </script>
    
</body>
</html>