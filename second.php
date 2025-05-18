<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'user_registration';

// Handle order form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_data'])) {
    $orderData = json_decode($_POST['order_data'], true);
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    date_default_timezone_set('Asia/Manila');
    $timestamp = date("Y-m-d H:i:s");

    foreach ($orderData as $item) {
        $id = $conn->real_escape_string($item['id']);
        $name = $conn->real_escape_string($item['name']);
        $qty = intval($item['qty']);
        $price = floatval($item['price']);
        $total = $qty * $price;

        $sql = "INSERT INTO orders (product_id, product_name, quantity, price, total_price, order_time)
                VALUES ('$id', '$name', '$qty', '$price', '$total', '$timestamp')";
        $conn->query($sql);
    }

    $conn->close();

    echo "<script>alert('✅ Order placed successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
    exit();
}

// Load menu items
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT id, name, category, image, price, quantity FROM menu_items";
$result = $conn->query($query);

$menuItems = [];
while ($row = $result->fetch_assoc()) {
    $menuItems[$row['category']][] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Kiosk Menu</title>
  <style>
    /* [Your existing CSS from the original file remains unchanged] */
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      height: 100vh;
      background-color: #111;
      color: #f5c542;
    }
    .sidebar {
      width: 220px;
      background-color: #000;
      padding: 30px 20px;
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
    }
    .content {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
    }
    .menu-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .item-card {
      width: 160px;
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
    .qty {
      font-size: 16px;
      min-width: 20px;
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
    .quantity-controls {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 10px;
    }
    .order-button {
      margin-top: 20px;
      background-color: #f5c542;
      border: none;
      padding: 14px 24px;
      font-size: 18px;
      font-weight: bold;
      color: #000;
      border-radius: 10px;
      cursor: pointer;
      margin-right: 10px;
    }
    .chatbot {
      width: 300px;
      background-color: #1a1a1a;
      padding: 20px;
      border-left: 1px solid #333;
      display: flex;
      flex-direction: column;
    }
    .chat-window {
      flex: 1;
      overflow-y: auto;
      background-color: #222;
      border-radius: 10px;
      padding: 10px;
      margin-bottom: 10px;
    }
    .chat-msg.user {
      text-align: right;
      color: #f5c542;
    }
    .chat-msg.bot {
      text-align: left;
      color: #eee;
    }
    .chat-input {
      display: flex;
      gap: 10px;
    }
    .chat-input input {
      flex: 1;
      padding: 10px;
      border-radius: 8px;
      border: none;
      font-size: 14px;
    }
    .chat-input button {
      background-color: #f5c542;
      border: none;
      padding: 10px 16px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Menu</h2>
    <button class="menu-button" onclick="showItems('Appetizer')">Appetizer</button>
    <button class="menu-button" onclick="showItems('Main')">Main Course</button>
    <button class="menu-button" onclick="showItems('Dessert')">Desserts</button>
    <button class="menu-button" onclick="showItems('Drink')">Drinks</button>
  </div>
  <div class="content">
    <h1>Select a Category</h1>
    <div id="itemList" class="menu-grid"></div>
    <div>
      <button class="order-button" onclick="reviewOrder()">👀 Review Order</button>
      <button class="order-button" onclick="placeOrder()">🛒 Confirm Order</button>
    </div>
  </div>
  <div class="chatbot">
    <h2>Chatbot</h2>
    <div class="chat-window" id="chatWindow"></div>
    <div class="chat-input">
      <input type="text" id="chatInput" placeholder="e.g., Add 2 Coke" />
      <button onclick="sendChat()">Send</button>
    </div>
  </div>

  <script>
    const menuItems = <?php echo json_encode($menuItems); ?>;

    function showItems(category) {
      const container = document.getElementById("itemList");
      const items = menuItems[category] || [];
      container.innerHTML = '';

      items.forEach((item, index) => {
        item.stock = parseInt(item.quantity);
        if (item.orderQty === undefined) item.orderQty = 0;

        container.innerHTML += `
          <div class="item-card">
            <img src="${item.image}" alt="${item.name}" />
            <div class="item-name">${item.name}</div>
            <div class="quantity-controls">
              <button class="qty-btn" onclick="changeQty('${category}', ${index}, -1)">−</button>
              <div class="qty" id="${category}-qty-${index}">${item.orderQty}</div>
              <button class="qty-btn" onclick="changeQty('${category}', ${index}, 1)">+</button>
            </div>
            <div class="price">${item.price} PHP</div>
          </div>
        `;
      });
    }

    function changeQty(category, index, amount) {
      const item = menuItems[category][index];
      item.orderQty = item.orderQty || 0;
      let newQty = item.orderQty + amount;
      newQty = Math.max(0, Math.min(item.stock, newQty));
      item.orderQty = newQty;
      document.getElementById(`${category}-qty-${index}`).innerText = item.orderQty;
    }

    function reviewOrder() {
      let summary = '';
      let total = 0;

      for (const cat in menuItems) {
        menuItems[cat].forEach(item => {
          if (item.orderQty > 0) {
            summary += `${item.name} x${item.orderQty} = ${(item.orderQty * item.price).toFixed(2)} PHP\n`;
            total += item.orderQty * item.price;
          }
        });
      }

      if (!summary) {
        alert("🚫 No items selected.");
      } else {
        alert(`📝 Review Your Order:\n\n${summary}\nTotal: ${total.toFixed(2)} PHP`);
      }
    }

    function placeOrder() {
      let orderItems = [];

      for (const cat in menuItems) {
        menuItems[cat].forEach(item => {
          if (item.orderQty > 0) {
            orderItems.push({
              id: item.id,
              name: item.name,
              qty: item.orderQty,
              price: item.price
            });
          }
        });
      }

      if (orderItems.length === 0) {
        alert("🚫 No items selected.");
        return;
      }

      const form = document.createElement("form");
      form.method = "POST";
      form.action = ""; // Same file

      const input = document.createElement("input");
      input.type = "hidden";
      input.name = "order_data";
      input.value = JSON.stringify(orderItems);
      form.appendChild(input);

      document.body.appendChild(form);
      form.submit();
    }

    function sendChat() {
      const input = document.getElementById("chatInput");
      const msg = input.value.trim();
      if (!msg) return;

      appendMessage("user", msg);

      const match = msg.match(/(?:add|order|i want)?\s*(\d+)?\s*(.+)/i);
      if (match) {
        const quantity = parseInt(match[1]) || 1;
        const name = match[2].toLowerCase();
        let found = false;

        for (const cat in menuItems) {
          menuItems[cat].forEach((item, i) => {
            if (item.name.toLowerCase().includes(name)) {
              item.orderQty = Math.min(item.stock, (item.orderQty || 0) + quantity);
              const qtyElem = document.getElementById(`${cat}-qty-${i}`);
              if (qtyElem) qtyElem.innerText = item.orderQty;
              appendMessage("bot", `✅ Added ${quantity} ${item.name}(s).`);
              found = true;
            }
          });
        }

        if (!found) appendMessage("bot", "🚫 I couldn't find that item.");
      }
      input.value = "";
    }

    function appendMessage(sender, message) {
      const chatWindow = document.getElementById("chatWindow");
      const msgElem = document.createElement("div");
      msgElem.classList.add("chat-msg", sender);
      msgElem.innerText = message;
      chatWindow.appendChild(msgElem);
      chatWindow.scrollTop = chatWindow.scrollHeight;
    }
  </script>
</body>
</html>
