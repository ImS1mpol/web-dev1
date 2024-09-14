<?php
session_start(); // Start the session to access stored form data

if (!isset($_SESSION['form_data'])) {
    header("Location: olympics_registration.php"); // Redirect to registration page if no data
    exit();
}
$formData = $_SESSION['form_data']; // Retrieve form data from session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Summary</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="registration_summary.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="social-icons">
            <a href="https://www.youtube.com" target="_blank">
                <img src="img/youtube.png" alt="YouTube">
            </a>
            <a href="https://www.facebook.com" target="_blank">
                <img src="img/facebook.png" alt="Facebook">
            </a>
            <a href="https://www.tiktok.com" target="_blank">
                <img src="img/tiktok.png" alt="TikTok">
            </a>
            <a href="https://www.instagram.com" target="_blank">
                <img src="img/instagram.png" alt="Instagram">
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="homepage.html">Home</a></li>
            <li><a href="registrationTutorial.html">Registration Tutorial</a></li>
            <li><a href="rules.html">Rules & Guidelines</a></li>
            <li><a href="contact.html">Contact Us</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="page-container">
        <div class="registration-container">
            <div class="card mt-4">
                <h1 class="card-header">You are now registered!</h1>
                <div class="card-body">
                    <h4 class="card-title">Details</h4>
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($formData['fullname']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($formData['email']); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($formData['phone-number']); ?></p>
                    <p><strong>Experience Level:</strong> <?php echo htmlspecialchars($formData['experience-level']); ?></p>
                    <p><strong>Availability:</strong> <?php echo htmlspecialchars($formData['availability']); ?></p>
                    <p><strong>Availability for Practice:</strong> <?php echo htmlspecialchars($formData['availability-practice']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Clear session data after displaying
    unset($_SESSION['form_data']);
    ?>
</body>
</html>