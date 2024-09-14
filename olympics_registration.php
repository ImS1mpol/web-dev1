<?php
session_start(); // Start the session to store form data temporarily
$alertMessages = ''; // Initialize variable to store alert messages

if (isset($_POST["submit"])) {
    $fullName = $_POST["fullname"];
    $email = $_POST["email"];
    $phoneNumber = $_POST["phone-number"];
    $experienceLevel = $_POST["experience-level"];
    $availability = $_POST["availability"];
    $availabilityPractice = $_POST["availability-practice"];
    
    $errors = array();
    
    if (empty($fullName) || empty($email) || empty($phoneNumber) || empty($experienceLevel) || empty($availability) || empty($availabilityPractice)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }

    require_once "database.php";
    $sql = "SELECT * FROM olympics_users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        array_push($errors, "Email already exists!");
    }
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            $alertMessages .= "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        $sql = "INSERT INTO olympics_users (full_name, email, phone_number, experience_level, availability, availability_practice) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $fullName, $email, $phoneNumber, $experienceLevel, $availability, $availabilityPractice);
            mysqli_stmt_execute($stmt);
            $_SESSION['form_data'] = $_POST; // Store form data in session
            header("Location: registration_summary.php"); // Redirect to summary page
            exit();
        } else {
            $alertMessages .= "<div class='alert alert-danger'>Something went wrong</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="olympics_reg.css">    


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
        <div class="logo"></div>
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
            <div class="form-title">REGISTER NOW</div>
            <?php
            // Output alert messages inside the container
            echo $alertMessages;
            ?>
            <form action="olympics_registration.php" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="fullname" placeholder="Last Name, First Name, Middle Initial" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                </div>
                <div class="mb-3">
                    <label for="phone-number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone-number" name="phone-number" placeholder="Enter your phone number" required>
                </div>
                <div class="mb-3">
                    <label for="experience-level" class="form-label">Experience Level</label>
                    <select class="form-control" id="experience-level" name="experience-level" required>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advance">Advance</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="availability" class="form-label">Availability</label>
                    <input type="text" class="form-control" id="availability" name="availability" placeholder="Enter your availability" required>
                </div>
                <div class="mb-3">
                    <label for="availability-practice" class="form-label">Availability for Practice</label>
                    <input type="text" class="form-control" id="availability-practice" name="availability-practice" placeholder="Enter your availability for practice" required>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Sign Up</button>
            </form>
        </div>
    </div>
</body>
</html>
