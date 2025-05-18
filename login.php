<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "user_registration");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login logic
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];

                if ($user['user_type'] === 'Admin') {
                    echo "<script>alert('Welcome Admin!'); window.location.href='admin_dashboard.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Welcome Staff!'); window.location.href='staff.php';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Incorrect password!');</script>";
            }
        } else {
            echo "<script>alert('Username not found!');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Database error: " . $conn->error . "');</script>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <style>
        body {
            background-color: #000;
            color: #FFD700;
            font-family: Arial, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        form {
            background-color: #111;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
            width: 320px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: 1px solid #FFD700;
            border-radius: 5px;
            background-color: #222;
            color: #FFD700;
        }

        input:focus {
            outline: none;
            border-color: #e6c200;
            box-shadow: 0 0 5px #FFD700;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #FFD700;
            color: #000;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #e6c200;
        }

        .register-text {
            text-align: center;
            margin-top: 15px;
        }

        .register-text a {
            color: #FFD700;
            text-decoration: underline;
        }

        .register-text a:hover {
            color: #e6c200;
        }
    </style>
</head>
<body>
    <form method="POST" action="login.php">
        <h2>Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>

        <div class="register-text">
            <a href="registration.php">Don’t have an account? Register here</a>
        </div>
    </form>
</body>
</html>
