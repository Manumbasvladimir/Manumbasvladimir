<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Staff') {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

$receipt = null;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id'] ?? 0);

    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $receipt = $result->fetch_assoc();
    $stmt->close();

    if (!$receipt) {
        $message = "Order not found.";
    } elseif ($receipt['status'] !== 'Delivered') {
        $message = "Order #$order_id is not yet delivered. Receipt can only be issued after delivery.";
        $receipt = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Issue Receipt - Staff</title>
    <style>
        body {
            background-color: #000;
            color: #FFD700;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            background-color: #111;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
        }

        label, input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        input[type="number"] {
            padding: 10px;
            background: #222;
            border: 1px solid #FFD700;
            color: #FFD700;
        }

        button {
            background-color: #FFD700;
            color: #000;
            font-weight: bold;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }

        .dashboard-link {
            text-align: right;
            margin-bottom: 10px;
        }

        .dashboard-link a {
            background-color: #FFD700;
            color: #000;
            padding: 8px 16px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }

        .receipt {
            background-color: #222;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .receipt h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>
<div class="dashboard-link">
    <a href="staff.php">Dashboard</a>
</div>

<div class="container">
    <h2>Issue Receipt</h2>
    <form method="POST">
        <label for="order_id">Enter Order ID:</label>
        <input type="number" name="order_id" id="order_id" required min="1">
        <button type="submit">Generate Receipt</button>
    </form>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($receipt): ?>
        <div class="receipt">
            <h3>Receipt</h3>
            <p><strong>Order ID:</strong> <?= $receipt['id'] ?></p>
            <p><strong>Product:</strong> <?= htmlspecialchars($receipt['product_name']) ?></p>
            <p><strong>Quantity:</strong> <?= $receipt['quantity'] ?></p>
            <p><strong>Total Price:</strong> ₱<?= number_format($receipt['total_price'], 2) ?></p>
            <p><strong>Status:</strong> <?= $receipt['status'] ?></p>
            <p><strong>Order Time:</strong> <?= $receipt['order_time'] ?></p>
            <p><strong>Payment Method:</strong> Cash</p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

<?php $conn->close(); ?>
