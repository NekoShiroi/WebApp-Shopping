<?php
session_start();

$hn = 'localhost:3306';
$db = 'suburban_outfitters';
$un = 'root';
$pw = ''; 

$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>No products in the cart. Please add products to the cart.</p>";
    exit();
}

$product = $_SESSION['cart'];

$username = "";
$role = "";

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT username, role FROM customer WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $role = $row['role'];
    }

    $stmt->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = "Pending"; 
    $totalAmount = $product['total'];
    $shippingAddress = $_POST['shipping_address'] ?? 'No address provided';


    $orderQuery = "INSERT INTO `order` (user_id, order_date, status, total_amount, shipping_address) VALUES (?, NOW(), ?, ?, ?)";
    $orderStmt = $conn->prepare($orderQuery);
    $orderStmt->bind_param("isds", $userId, $status, $totalAmount, $shippingAddress);
    $orderStmt->execute();


    if ($orderStmt->affected_rows === 1) {

        unset($_SESSION['cart']);
        header("Location: order_history.php");
        exit();
    } else {
        echo "<p>Failed to place the order. Please try again later.</p>";
    }
    $orderStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        #banner {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        #nav {
            background-color: #333;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 10px;
            height: 50px;
        }
        #nav a {
            color: white;
            text-decoration: none;
            padding: 14px 16px;
        }
        #nav a:hover {
            background-color: #ddd;
            color: black;
        }
        #nav .user-status {
            color: white;
            font-size: 14px;
        }
        .container {
            width: 60%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .cart-item img {
            width: 250px;
            height: auto;
            margin-bottom: 10px;
        }
        .total {
            font-size: 20px;
            margin-top: 20px;
            font-weight: bold;
        }
        .confirm-btn {
            display: block;
            margin: 20px auto 0;
            width: 30%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .confirm-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div id="banner">
        <h1>Suburban Outfitters - Checkout</h1>
    </div>

    <div id="nav">
        <div>
            <a href="homepage.php">Shop</a>
            <a href="login.php">Login</a>
        </div>
        <div class="user-status">
            <?php if (!isset($_SESSION['user_id'])): ?>
                Not logged in
            <?php else: ?>
                <?= htmlspecialchars($role) ?>: <?= htmlspecialchars($username) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <h2>Review Your Order</h2>
        <form method="POST" action="">
            <div class="cart-item">
                <img src="<?= htmlspecialchars($product['image_url'] ?? 'https://images.unsplash.com/photo-1453486030486-0a5ffcd82cd9?q=80&w=1926&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D?w=500') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <p><strong>Product:</strong> <?= htmlspecialchars($product['name']) ?></p>
                <p><strong>Quantity:</strong> <?= htmlspecialchars($product['quantity']) ?></p>
                <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
                <p><strong>Total:</strong> $<?= number_format($product['total'], 2) ?></p>
            </div>
            <div>
                <label for="shipping_address"><strong>Shipping Address:</strong></label>
                <input type="text" id="shipping_address" name="shipping_address" required style="width: 30%; padding: 10px; margin: 10px 0;">
            </div>
            <div class="total">Grand Total: $<?= number_format($product['total'], 2) ?></div>
            <button type="submit" class="confirm-btn">Confirm & Place Order</button>
        </form>
    </div>
</body>
</html>
