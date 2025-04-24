<?php
// Database connection
$host = "localhost";
$dbname = "user_registration";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

ob_start(); // Start buffering output to avoid header issues

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $reenter_password = $_POST['reenter_password'];
    $user_type = $_POST['user_type'];

    // Validate password requirements
    if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[\W_]/', $password)) {
        echo "Password must be at least 8 characters long, contain a number, a letter, and a special character!";
    } elseif ($password !== $reenter_password) {
        echo "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database (no custom ID)
        $sql = "INSERT INTO users (firstname, middlename, lastname, contact, username, password, user_type)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssssss", $firstname, $middlename, $lastname, $contact, $username, $hashed_password, $user_type);
            if ($stmt->execute()) {
                echo "Registration successful! Redirecting to login page...";
                header("Location: login.php");
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error in preparing statement: " . $conn->error;
        }
    }
}

$conn->close();
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="styleres.css">
    <style>
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #FFD700;
        }
        .register-text {
            color: #007BFF;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Registration Form</h2>
    <form method="POST" action="registration.php" id="registrationForm" onsubmit="return validatePassword()">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br><br>

        <label for="middlename">Middle Name:</label>
        <input type="text" id="middlename" name="middlename"><br><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br><br>
       
        <label for="contact">Contact:</label>
        <input type="number" id="contact" name="contact" required><br><br>
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <div style="position: relative;">
            <input type="password" id="password" name="password" required>
            <span class="eye-icon" onclick="togglePasswordVisibility('password')">&#128065;</span>
        </div><br><br>

        <label for="reenter_password">Re-enter Password:</label>
        <div style="position: relative;">
            <input type="password" id="reenter_password" name="reenter_password" required>
            <span class="eye-icon" onclick="togglePasswordVisibility('reenter_password')">&#128065;</span>
        </div><br><br>

        <label for="user_type">Type of User:</label>
        <select id="user_type" name="user_type" required>
            <option value="Admin">Admin</option>
            <option value="User">Staff</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>

    <div class="register-text">
        <a href="login.php">Already have an account? Login Now</a>
    </div>

    <script>
        function togglePasswordVisibility(id) {
            const passwordField = document.getElementById(id);
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
        }

        function validatePassword() {
            const password = document.getElementById("password").value;
            const regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (!regex.test(password)) {
                alert("Password must be at least 8 characters long, contain a number, a letter, and a special character!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
