<?php
// Start the session at the beginning
session_start();

// Check if the form is submitted
if (isset($_POST["login"])) {
    // Retrieve email and password from the POST request
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    // Include database connection
    require_once "database.php";

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify user credentials
    if ($user) {
        if (password_verify($password, $user["password"])) {
            // Set session variable
            $_SESSION["user"] = "yes";
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Password does not match";
        }
    } else {
        $error_message = "Email does not match";
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <div class="left-side">
            <h1>Join the Fatima Tech & E-Sports Games!</h1>
            <p>
                Show your skills in PC assembly, coding, and e-sports tournaments like ML, COD, Valorant, and Dota. Compete
                in the IT Olympics and embrace the fusion of faith, tech, and gaming. Register now!
            </p>
        </div>
        <div class="right-side">
            <div class="login-container">
                <h2>Login</h2>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                
                <form action="login.php" method="post">
                    <label for="email">Email or Student Number:</label>
                    <input type="text" id="email" name="email" required aria-required="true" />
                    
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required aria-required="true" />
                    
                    <div class="options">
                        <label>
                            <input type="checkbox" id="remember" name="remember" />
                            Remember me
                        </label>
                        <a href="#forgot-password">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" name="login">Login</button>
                    
                    <div>
                        <p>Don't have an account? <a href="registration.php">Sign up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>