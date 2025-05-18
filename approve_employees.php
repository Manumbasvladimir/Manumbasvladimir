<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "user_registration");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approval
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $conn->query("UPDATE users SET user_type='Staff', status='Approved', approved=1 WHERE id=$id");
    header("Location: approve_employees.php");
    exit();
}

// Handle rejection
if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: approve_employees.php");
    exit();
}

// Fetch pending employees (excluding Admins)
$sql = "SELECT * FROM users WHERE status='Pending' AND user_type != 'Admin'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approve Employees</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background-color: #000;
            color: #FFD700;
            font-family: Arial, sans-serif;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            padding: 20px;
        }

        .top-bar a {
            background-color: #FFD700;
            color: #000;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .top-bar a:hover {
            background-color: #e0c200;
        }

        h2 {
            text-align: center;
            margin-top: 10px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #111;
        }

        th, td {
            padding: 12px;
            border: 1px solid #FFD700;
            text-align: center;
        }

        th {
            background-color: #222;
        }

        a.button {
            padding: 5px 10px;
            text-decoration: none;
            color: #000;
            background-color: #FFD700;
            border-radius: 5px;
        }

        a.button:hover {
            background-color: #e0c200;
        }

        a.button.reject {
            background-color: red;
            color: #fff;
        }

        a.button.reject:hover {
            background-color: darkred;
        }

        footer {
            background-color: #111;
            color: #FFD700;
            text-align: center;
            padding: 15px;
            border-top: 1px solid #FFD700;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>

    <h2>Pending Employee Approvals</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                    <td><?= htmlspecialchars($row['contact']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td>
                        <a class="button" href="?approve=<?= $row['id'] ?>">Approve</a>
                        <a class="button reject" href="?reject=<?= $row['id'] ?>">Reject</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No pending employees found.</td></tr>
        <?php endif; ?>
    </table>
</div>

<footer>
    &copy; <?= date("Y") ?> Orchard Corporation. All rights reserved.
</footer>
</body>
</html>
