<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Kiosk Menu</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      display: flex;
      height: 100vh;
      background-color: #111;
      color: #f5c542;
    }

    .sidebar {
      width: 220px;
      background-color: #000;
      padding: 30px 20px;
      box-shadow: 2px 0 10px rgba(255, 215, 0, 0.1);
    }

    .sidebar h2 {
      font-size: 24px;
      margin-bottom: 30px;
      text-align: center;
      color: #f5c542;
      border-bottom: 1px solid #f5c542;
      padding-bottom: 10px;
    }

    .menu-button {
      width: 100%;
      padding: 14px 0;
      margin: 12px 0;
      font-size: 16px;
      background-color: #f5c542;
      border: none;
      border-radius: 8px;
      color: #000;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .menu-button:hover {
      background-color: #ffd700;
      transform: scale(1.05);
    }

    .content {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
    }

    .content h1 {
      font-size: 28px;
      color: #f5c542;
      margin-bottom: 20px;
    }

    .menu-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .item-card {
      width: 200px;
      background-color: #1a1a1a;
      border: 1px solid #f5c542;
      border-radius: 10px;
      padding: 15px;
      text-align: center;
    }

    .item-card img {
      width: 100%;
      height: 140px;
      object-fit: cover;
      border-radius: 8px;
    }

    .item-name {
      margin-top: 10px;
      font-size: 18px;
      font-weight: bold;
    }

    .quantity-controls {
      margin-top: 10px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
    }

    .qty-btn {
      background-color: #f5c542;
      border: none;
      padding: 5px 10px;
      font-weight: bold;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    .qty {
      font-size: 16px;
      min-width: 20px;
    }

    .order-button {
      margin-top: 30px;
      background-color: #f5c542;
      border: none;
      padding: 14px 24px;
      font-size: 18px;
      font-weight: bold;
      color: #000;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s;
    }

    .order-button:hover {
      background-color: #ffd700;
      transform: scale(1.03);
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Menu</h2>
    <button class="menu-button" onclick="showItems('appetizer')">Appetizer</button>
    <button class="menu-button" onclick="showItems('main')">Main Course</button>
    <button class="menu-button" onclick="showItems('dessert')">Desserts</button>
    <button class="menu-button" onclick="showItems('drink')">Drinks</button>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h1>Select a Category</h1>
    <div id="itemList" class="menu-grid"></div>

    <!-- Place Order Button -->
    <div>
      <button class="order-button" onclick="placeOrder()">🛒 Place Order</button>
    </div>
  </div>

  <script>
    const menuItems = {
      appetizer: [
        { name: "Spring Rolls", image: "images/spring-rolls.jpg", quantity: 0 },
        { name: "Bruschetta", image: "images/bruschetta.jpg", quantity: 0 },
        { name: "Garlic Bread", image: "images/garlic-bread.jpg", quantity: 0 }
      ],
      main: [
        { name: "Steak", image: "images/steak.jpg", quantity: 0 },
        { name: "Grilled Salmon", image: "images/salmon.jpg", quantity: 0 },
        { name: "Spaghetti", image: "images/spaghetti.jpg", quantity: 0 }
      ],
      dessert: [
        { name: "Ice Cream", image: "images/ice-cream.jpg", quantity: 0 },
        { name: "Cheesecake", image: "images/cheesecake.jpg", quantity: 0 },
        { name: "Brownies", image: "images/brownies.jpg", quantity: 0 }
      ],
      drink: [
        { name: "Coke", image: "images/coke.jpg", quantity: 0 },
        { name: "Orange Juice", image: "images/orange-juice.jpg", quantity: 0 },
        { name: "Iced Tea", image: "images/iced-tea.jpg", quantity: 0 }
      ]
    };

    function showItems(category) {
      const container = document.getElementById("itemList");
      const items = menuItems[category];

      container.innerHTML = items.map((item, index) => `
        <div class="item-card">
          <img src="${item.image}" alt="${item.name}">
          <div class="item-name">${item.name}</div>
          <div class="quantity-controls">
            <button class="qty-btn" onclick="changeQty('${category}', ${index}, -1)">−</button>
            <div class="qty" id="${category}-qty-${index}">${item.quantity}</div>
            <button class="qty-btn" onclick="changeQty('${category}', ${index}, 1)">+</button>
          </div>
        </div>
      `).join('');
    }

    function changeQty(category, index, amount) {
      const item = menuItems[category][index];
      item.quantity = Math.max(0, item.quantity + amount);
      document.getElementById(`${category}-qty-${index}`).innerText = item.quantity;
    }

    function placeOrder() {
      const orderedItems = [];

      for (const category in menuItems) {
        menuItems[category].forEach(item => {
          if (item.quantity > 0) {
            orderedItems.push(`${item.name} x${item.quantity}`);
          }
        });
      }

      if (orderedItems.length === 0) {
        alert("🚫 No items selected.");
      } else {
        const summary = orderedItems.join("\n");
        alert("🧾 Your Order:\n\n" + summary);

        // 🔄 Optional: send data to backend using fetch/ajax here
      }
    }
  </script>

</body>
</html>
