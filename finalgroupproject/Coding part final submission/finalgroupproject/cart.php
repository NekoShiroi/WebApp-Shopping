<?php
session_start();

$hn = 'localhost:3306';
$db = 'suburban_outfitters';
$un = 'root';
$pw = ''; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=cart.php");
    exit();
}

$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product = null; 
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
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    $sql = "SELECT * FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product['quantity'] = $quantity; 
        $product['total'] = $product['price'] * $quantity; 

        $_SESSION['cart'] = $product;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
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
        .user-status {
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
        h2 {
            text-align: center;
            color: #333;
        }
        .cart-item {
            margin: 20px 0;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .cart-item img {
            width: 250px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .cart-item p {
            margin: 5px 0;
            font-size: 16px;
        }
        .total {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        .checkout-btn {
            display: block;
            width: 30%; 
            text-align: center;
            padding: 10px;
            background-color: #28a745;
            color: white;
            font-size: 18px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px auto 0 auto; 
            position: relative; 
        }
        .checkout-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div id="banner">
        <h1>Suburban Outfitters</h1>
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
        <h2>Shopping Cart</h2>
        <?php if ($product): ?>
            <div class="cart-item">
                <img src="<?= htmlspecialchars($product['image_url'] ?? 'https://images.unsplash.com/photo-1453486030486-0a5ffcd82cd9?q=80&w=1926&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D?w=500') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div>
                    <p><strong>Product:</strong> <?= htmlspecialchars($product['name']) ?></p>
                    <p><strong>Quantity:</strong> <?= htmlspecialchars($product['quantity']) ?></p>
                    <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
                </div>
            </div>
            <div class="total">
                Grand Total: $<?= number_format($product['total'], 2) ?>
            </div>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        <?php else: ?>
            <p>No product added to the cart.</p>
        <?php endif; ?>
    </div>

</body>
</html>
