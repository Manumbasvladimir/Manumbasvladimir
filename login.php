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

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            if ($user['user_type'] === "Admin") {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('User not found!');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            background-color: #000;
            color: #FFD700;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #111;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
            width: 300px;
            text-align: center;
            margin-bottom: 15px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #FFD700;
            background-color: #222;
            color: #FFD700;
            border-radius: 5px;
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
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e6c200;
        }

        .register-text {
            color: #FFD700;
            margin-top: 10px;
            text-decoration: underline;
            cursor: pointer;
        }

        .register-text:hover {
            color: #e6c200;
        }
    </style>
</head>
<body>

    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="register-text">
        <a href="registration.php">Don’t have an account? Register Now</a>
    </div>

</body>
</html>
