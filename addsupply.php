<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #0d0d0d;
      color: #f1c40f;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background-color: #111;
      color: #f1c40f;
      padding: 20px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      border-bottom: 1px solid #f1c40f;
      position: relative;
    }

    .inventory-link {
      position: absolute;
      top: 20px;
      right: 30px;
      background-color: #f1c40f;
      color: #000;
      padding: 8px 16px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .inventory-link:hover {
      background-color: #d4ac0d;
    }

    main {
      flex: 1;
      padding: 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    form {
      background-color: #1a1a1a;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(241, 196, 15, 0.3);
      width: 400px;
      margin-bottom: 20px;
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

    footer {
      background-color: #111;
      color: #f1c40f;
      text-align: center;
      padding: 15px;
      border-top: 1px solid #f1c40f;
    }
  </style>
</head>
<body>

<header>
  Orchard Corporation - Add Product
  <a href="inventory.php" class="inventory-link">Inventory</a>
</header>

<main>
  <form action="save_product.php" method="POST">
    <h2>Add Product</h2>

    <label for="classification">Type of Foods</label>
    <select id="classification" name="type" onchange="updateProducts()" required>
      <option value="" disabled selected>Select Food Type</option>
      <option value="Appetizer">Appetizer</option>
      <option value="Main Course">Main Course</option>
      <option value="Desserts">Desserts</option>
      <option value="Drinks">Drinks</option>
    </select>

    <label for="productname">Select Product</label>
    <select id="productname" name="productname" required>
      <option value="" disabled selected>Select Product</option>
    </select>

    <label for="quantity">Quantity</label>
    <input type="number" id="quantity" name="quantity" min="1" required>

    <button type="submit">Save</button>
  </form>
</main>

<footer>
  &copy; <?= date("Y") ?> Orchard Corporation. All rights reserved.
</footer>

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

</body>
</html>
