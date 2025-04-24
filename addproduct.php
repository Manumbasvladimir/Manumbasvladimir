
<?php
if (isset($_POST['go_import'])) {
  header("Location: import.php");
  exit(); // important!
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #0d0d0d;
      color: #f1c40f;
      padding: 40px;
    }

    form {
      background-color: #1a1a1a;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(241, 196, 15, 0.3);
      width: 400px;
      margin: auto;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #f1c40f;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #f1c40f;
    }

    select,
    input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid #f1c40f;
      background-color: #000;
      color: #f1c40f;
    }

    button {
      width: 100%;
      background-color: #f1c40f;
      color: #000;
      border: none;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #d4ac0d;
    }
  </style>
</head>
<body>
<form action="save_product.php" method="POST">
  <label for="classification">Type of Foods</label>
  <select id="classification" name="type" onchange="updateProducts()" required>
    <option value="" disabled selected>Select Food Type</option>
    <option value="Appetizer">Appetizer</option>
    <option value="Main Course">Main Course</option>
    <option value="Desserts">Desserts</option>
    <option value="Drinks">Drinks</option>
  </select>

  <br><br>

  <label for="productname">Select Product</label>
  <select id="productname" name="productname" required>
    <option value="" disabled selected>Select Product</option>
  </select>

  <br><br>

  <label for="quantity">Quantity</label>
  <input type="number" id="quantity" name="quantity" min="1" required>

  <br><br>

  <button type="submit">Save</button>
</form>

<script>
  const foodOptions = {
    "Appetizer": ["Spring Rolls", "Bruschetta", "Garlic Bread"],
    "Main Course": ["Steak", "Grilled Salmon", "Spaghetti"],
    "Desserts": ["Ice Cream", "Cheesecake", "Brownies"],
    "Drinks": ["Coke", "Orange Juice", "Iced Tea"]
  };

  function updateProducts() {
    const foodType = document.getElementById("classification").value;
    const productSelect = document.getElementById("productname");

    productSelect.innerHTML = '<option value="" disabled selected>Select Product</option>';

    if (foodOptions[foodType]) {
      foodOptions[foodType].forEach(item => {
        const option = document.createElement("option");
        option.value = item;
        option.textContent = item;
        productSelect.appendChild(option);
      });
    }
  }
</script>
<form method="POST">
  <button type="submit" name="go_import" style="
    width: 400px;
    background-color: #f1c40f;
    color: #000;
    border: none;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
    display: block;
    margin: 20px auto 0;
  ">
    Go to Import Page
  </button>
</form>
</body>
</html>