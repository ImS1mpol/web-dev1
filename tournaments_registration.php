<?php
session_start(); // Start the session to store form data temporarily
$alertMessages = ''; // Initialize variable to store alert messages

// Include database connection
require_once "database.php";

if (isset($_POST["submit"])) {
    $teamName = trim($_POST["team-name"]);
    $teamCaptainName = trim($_POST["team-captain-name"]);
    $teamCaptainEmail = trim($_POST["team-captain-email"]);
    $teamCaptainPhoneNumber = trim($_POST["team-captain-phone-number"]);
    $availability = trim($_POST["availability"]);
    $teamMemberName = isset($_POST["team-member-name"]) ? $_POST["team-member-name"] : [];
    $teamMemberEmail = isset($_POST["team-member-email"]) ? $_POST["team-member-email"] : [];
    $teamMemberPhoneNumber = isset($_POST["team-member-phone-number"]) ? $_POST["team-member-phone-number"] : [];

    $errors = array();

    // Validate required fields
    if (empty($teamName) || empty($teamCaptainName) || empty($teamCaptainEmail) || empty($teamCaptainPhoneNumber) || empty($availability)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($teamCaptainEmail, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Team captain's email is not valid");
    }

    // Check for existing email
    $sql = "SELECT * FROM tournament_users WHERE team_captain_email = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $teamCaptainEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Email already exists!");
        }
    } else {
        array_push($errors, "Database error: Unable to prepare statement.");
    }

    // Output errors or insert data
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            $alertMessages .= "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Convert non-array values to arrays if needed
        $teamMemberNames = is_array($teamMemberName) ? implode(',', $teamMemberName) : '';
        $teamMemberEmails = is_array($teamMemberEmail) ? implode(',', $teamMemberEmail) : '';
        $teamMemberPhones = is_array($teamMemberPhoneNumber) ? implode(',', $teamMemberPhoneNumber) : '';

        // Insert into database
        $sql = "INSERT INTO tournament_users (team_name, team_captain_name, team_captain_email, team_captain_phone_number, availability, team_member_name, team_member_email, team_member_phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssss", $teamName, $teamCaptainName, $teamCaptainEmail, $teamCaptainPhoneNumber, $availability, $teamMemberNames, $teamMemberEmails, $teamMemberPhones);
            mysqli_stmt_execute($stmt);
            $_SESSION['form_data'] = $_POST; // Store form data in session
            header("Location: registration_summary_tourna.php"); // Redirect to summary page
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
    <link rel="stylesheet" href="tournaments_registration.css">
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
            <div class="form-title">REGISTER NOW</div>
            <?php
            // Output alert messages inside the container
            echo $alertMessages;
            ?>
            <form action="tournaments_registration.php" method="post">
                <div class="form-layout">
                    <!-- Left Side -->
                    <div class="form-left">
                        <div class="mb-3">
                            <label for="team-name" class="form-label">Team Name</label>
                            <input type="text" class="form-control" id="team-name" name="team-name" placeholder="Enter your team name" required>
                        </div>
                        <div class="mb-3">
                            <label for="team-captain-name" class="form-label">Team Captain Name</label>
                            <input type="text" class="form-control" id="team-captain-name" name="team-captain-name" placeholder="Enter team captain's name" required>
                        </div>
                        <div class="mb-3">
                            <label for="team-captain-email" class="form-label">Team Captain Email Address</label>
                            <input type="email" class="form-control" id="team-captain-email" name="team-captain-email" placeholder="Enter team captain's email address" required>
                        </div>
                        <div class="mb-3">
                            <label for="team-captain-phone-number" class="form-label">Team Captain Phone Number</label>
                            <input type="text" class="form-control" id="team-captain-phone-number" name="team-captain-phone-number" placeholder="Enter team captain's phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="availability" class="form-label">Availability</label>
                            <input type="text" class="form-control" id="availability" name="availability" placeholder="Enter your availability" required>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="form-right">
                        <div id="team-members-container">
                            <div class="team-member">
                                <div class="mb-3">
                                    <label for="team-member-name" class="form-label">Team Member Name</label>
                                    <textarea class="form-control" id="team-member-name" name="team-member-name" rows="5" placeholder="Enter team member's name"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="team-member-email" class="form-label">Team Member Email Address</label>
                                    <textarea class="form-control" id="team-member-email" name="team-member-email" rows="5" placeholder="Enter team member's email address"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="team-member-phone-number" class="form-label">Team Member Phone Number</label>
                                    <textarea class="form-control" id="team-member-phone-number" name="team-member-phone-number" rows="5" placeholder="Enter team member's phone number"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Fatima Tech & E-Sports Games. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-oBqDVmMz4fnFO9RtxC5JrET3kLhO6S28Tk3kX1l7y5urXvqSAmF9d/+5i7+g40fO" crossorigin="anonymous"></script>
    <script src="tournaments_registration.js"></script>
</body>
</html>
