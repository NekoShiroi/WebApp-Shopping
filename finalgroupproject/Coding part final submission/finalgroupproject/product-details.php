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

$productId = intval($_GET['id']);
$sql = "SELECT * FROM product WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "<p>Product not found.</p>";
    $conn->close();
    exit();
}

$username = "";
$role = "";
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userQuery = "SELECT username, role FROM customer WHERE user_id = ?";
    $userStmt = $conn->prepare($userQuery);
    $userStmt->bind_param("i", $userId);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    if ($userResult && $userResult->num_rows === 1) {
        $user = $userResult->fetch_assoc();
        $username = $user['username'];
        $role = $user['role'];
    }
    $userStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
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
            width: 50%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .product-details img {
            width: 300px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .product-details p {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
        }
        .quantity-box {
            width: 100px;
            padding: 10px;
            font-size: 16px;
            margin: 10px auto;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .buy-now-btn {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .buy-now-btn:hover {
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
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <div class="product-details">
            <img src="https://images.unsplash.com/photo-1453486030486-0a5ffcd82cd9?q=80&w=1926&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D?w=500" alt="<?= htmlspecialchars($product['name']) ?>">
            <p>Description: <?= htmlspecialchars($product['description']) ?></p>
            <p>Price: $<?= htmlspecialchars($product['price']) ?></p>
            <p>In Stock: <?= htmlspecialchars($product['stock_quantity']) ?></p>
        </div>
        <form action="cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $productId ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="quantity-box" value="1" min="1" required>
            <button type="submit" class="buy-now-btn">Buy Now</button>
        </form>
    </div>
</body>
</html>
