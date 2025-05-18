<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Staff') {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";   // Adjust if needed
$password = "";       // Adjust if needed
$dbname = "user_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($order_id > 0) {
        if ($action === 'accept') {
            // Accept order (mark as accepted)
            $stmt = $conn->prepare("UPDATE orders SET status='Accepted' WHERE id=?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'deliver') {
            // Mark order delivered
            $stmt = $conn->prepare("UPDATE orders SET status='Delivered' WHERE id=?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Fetch all orders sorted by order_time
$sql = "SELECT * FROM orders ORDER BY order_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>View Orders - Staff</title>
<style>
    body {
        background-color: #000;
        color: #FFD700;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 20px;
    }
   
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #111;
    }
    th, td {
        border: 1px solid #FFD700;
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #333;
    }
    button {
        background-color: #FFD700;
        border: none;
        padding: 7px 12px;
        font-weight: bold;
        cursor: pointer;
        border-radius: 4px;
        color: #000;
    }
    button:disabled {
        background-color: #555;
        cursor: not-allowed;
    }
    .header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.dashboard-btn {
    background-color: #FFD700;
    color: #000;
    padding: 8px 16px;
    text-decoration: none;
    font-weight: bold;
    border-radius: 5px;
}
</style>
<script>
function submitAction(orderId, action) {
    if (!confirm(`Are you sure you want to ${action} order #${orderId}?`)) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';

    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'order_id';
    inputId.value = orderId;
    form.appendChild(inputId);

    const inputAction = document.createElement('input');
    inputAction.type = 'hidden';
    inputAction.name = 'action';
    inputAction.value = action;
    form.appendChild(inputAction);

    document.body.appendChild(form);
    form.submit();
}
</script>
</head>

<body>

<div class="header-bar">
  <h1>Customer Orders</h1>
  <a href="staff.php" class="dashboard-btn">Dashboard</a>
</div>


<table>
<thead>
<tr>
    <th>Order ID</th>
    <th>Product</th>
    <th>Quantity</th>
    <th>Price (₱)</th>
    <th>Total Price (₱)</th>
    <th>Status</th>
    <th>Order Time</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= htmlspecialchars($row['quantity']) ?></td>
        <td><?= number_format($row['price'], 2) ?></td>
        <td><?= number_format($row['total_price'], 2) ?></td>
        <td><?= htmlspecialchars($row['status'] ?? 'Pending') ?></td>
        <td><?= htmlspecialchars($row['order_time']) ?></td>
        <td>
            <?php
            $status = $row['status'] ?? 'Pending';
            if ($status === 'Pending'): ?>
                <button onclick="submitAction(<?= $row['id'] ?>, 'accept')">Accept (Cash)</button>
                <button disabled>Deliver</button>
            <?php elseif ($status === 'Accepted'): ?>
                <button disabled>Accept</button>
                <button onclick="submitAction(<?= $row['id'] ?>, 'deliver')">Mark Delivered</button>
            <?php else: ?>
                <button disabled>Accept</button>
                <button disabled>Deliver</button>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="8" style="text-align:center;">No orders found.</td></tr>
<?php endif; ?>
</tbody>
</table>

</body>
</html>

<?php $conn->close(); ?>
