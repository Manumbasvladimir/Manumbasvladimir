<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "user_registration");

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $category = $_POST["category"];
    $price = floatval($_POST["price"]);
    $quantity = intval($_POST["quantity"]);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "images/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $stmt = $mysqli->prepare("INSERT INTO menu_items (name, category, image, price, quantity) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdi", $name, $category, $targetFile, $price, $quantity);
            if ($stmt->execute()) {
                $success = "Product added successfully!";
            } else {
                $error = "Database insert failed.";
            }
        } else {
            $error = "Image upload failed.";
        }
    } else {
        $error = "Image file is required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
    <style>
        body {
            background-color: #0d0d0d;
            color: #FFD700;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px;
            position: relative;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        form {
            max-width: 500px;
            margin: auto;
            background-color: #111;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #FFD700;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #FFD700;
            border-radius: 6px;
            background-color: #000;
            color: #FFD700;
        }

        button {
            background-color: #FFD700;
            color: #000;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #d4ac0d;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .success {
            color: #2ecc71;
        }

        .error {
            color: #e74c3c;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #FFD700;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .dashboard-link {
            position: absolute;
            top: 20px;
            right: 30px;
            background-color: #FFD700;
            color: #000;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .dashboard-link:hover {
            background-color: #d4ac0d;
        }
    </style>
</head>
<body>

<a href="admin_dashboard.php" class="dashboard-link">← Back to Dashboard</a>

<h2>Add New Product</h2>

<?php if ($success): ?>
    <div class="message success"><?= $success ?></div>
<?php elseif ($error): ?>
    <div class="message error"><?= $error ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="name">Product Name</label>
    <input type="text" name="name" id="name" required>

    <label for="category">Category</label>
    <select name="category" id="category" required>
        <option value="">-- Select Category --</option>
        <option value="Appetizer">Appetizer</option>
        <option value="Main">Main</option>
        <option value="Dessert">Dessert</option>
        <option value="Drink">Drink</option>
    </select>

    <label for="price">Price (₱)</label>
    <input type="number" step="0.01" name="price" id="price" required>

    <label for="quantity">Quantity</label>
    <input type="number" name="quantity" id="quantity" required>

    <label for="image">Product Image</label>
    <input type="file" name="image" id="image" accept="image/*" required>

    <button type="submit">Add Product</button>
</form>

<a href="inventory.php" class="back-link">← Back to Inventory</a>

</body>
</html>
