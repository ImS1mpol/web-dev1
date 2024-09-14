
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="registration.css">
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.querySelector('.eye-icon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                eyeIcon.textContent = '👁️';
            }
        }

        function toggleConfirmPasswordVisibility() {
            const confirmPasswordField = document.getElementById('confirm-password');
            const eyeIcon = document.querySelector('.eye-icon-confirm');
            if (confirmPasswordField.type === 'password') {
                confirmPasswordField.type = 'text';
                eyeIcon.textContent = '🙈';
            } else {
                confirmPasswordField.type = 'password';
                eyeIcon.textContent = '👁️';
            }
        }
    </script>
</head>
<body>
    <div class="page-container">
        <div class="registration-container">
            <div class="form-title">REGISTER NOW</div>
            <?php
            if (isset($_POST["submit"])) {
                $fullName = $_POST["fullname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $passwordRepeat = $_POST["repeat_password"];
                $studentNumber = $_POST["student-number"];
                $section = $_POST["section"];
                
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $errors = array();
                
                if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat) || empty($studentNumber) || empty($section)) {
                    array_push($errors, "All fields are required");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Email is not valid");
                }
                if (strlen($password) < 8) {
                    array_push($errors, "Password must be at least 8 characters long");
                }
                if ($password !== $passwordRepeat) {
                    array_push($errors, "Passwords do not match");
                }

                require_once "database.php";
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) > 0) {
                    array_push($errors, "Email already exists!");
                }
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                } else {
                    $sql = "INSERT INTO users (full_name, email, password, student_number, section) VALUES (?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "sssss", $fullName, $email, $passwordHash, $studentNumber, $section);
                        mysqli_stmt_execute($stmt);
                        echo "<div class='alert alert-success'>You are registered successfully. <a href='login.php'>Login Here</a></div>";
                    } else {
                        die("Something went wrong");
                    }
                }
            }
            ?>
            <form action="registration.php" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="fullname" placeholder="Last Name, First Name, Middle Initial" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                </div>
                <div class="mb-3">
                    <label for="student-number" class="form-label">Student Number</label>
                    <input type="text" class="form-control" id="student-number" name="student-number" placeholder="Enter your student number" required>
                </div>
                <div class="mb-3">
                    <label for="section" class="form-label">Section</label>
                    <input type="text" class="form-control" id="section" name="section" placeholder="Enter your section" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-container">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required minlength="8">
                        <button type="button" class="eye-icon" onclick="togglePasswordVisibility()">👁️</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <div class="password-container">
                        <input type="password" class="form-control" id="confirm-password" name="repeat_password" placeholder="Confirm your password" required minlength="8">
                        <button type="button" class="eye-icon eye-icon-confirm" onclick="toggleConfirmPasswordVisibility()">👁️</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Sign Up</button>
            </form>
            <div class="links mt-3">
                <p>Already Registered? <a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
