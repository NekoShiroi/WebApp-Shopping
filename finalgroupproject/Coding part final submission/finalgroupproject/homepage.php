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

$productQuery = "SELECT product_id, name, description, price, stock_quantity FROM product";
$productResult = $conn->query($productQuery);

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

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suburban Outfitters</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        #nav .right-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #nav .logout-btn {
            background-color: #dc3545;
            padding: 8px 12px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        #nav .logout-btn:hover {
            background-color: #c82333;
        }
        .product-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px 0;
        }
        .product {
            flex: 1 1 30%;
            box-sizing: border-box;
            padding: 10px;
            max-width: 300px;
            text-align: center;
            background-color: #f9f9f9;
            border-radius: 8px;
            margin: 10px;
        }
        .product img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }
        .product img:hover {
            transform: scale(1.05);
        }
        .product-name {
            font-size: 1.2em;
            margin: 10px 0 5px;
        }
        .product-description {
            font-size: 0.9em;
            color: #555;
            margin: 5px 0;
        }
        .product-price {
            font-size: 1em;
            color: #333;
        }
        .product-stock {
            font-size: 0.9em;
            color: #888;
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
        <div class="right-section">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span style="color: white;"><?= htmlspecialchars($role) ?>: <?= htmlspecialchars($username) ?></span>
                <?php if ($role === 'admin'): ?>
                    <a href="add_products.php">Add Product</a>
                    <a href="delete_products.php">Delete Product</a>
                    <a href="update_products.php">Update Product</a>
                    <a href="manage_users.php">Manage User</a>
                <?php elseif ($role === 'customer'): ?>
                    <a href="update_account.php">Update Account</a>
                    <a href="order_history.php">Order History</a>
                <?php endif; ?>
                <a href="?logout=true" class="logout-btn">Logout</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="product-gallery">
        <?php
        if ($productResult->num_rows > 0) {
            while ($row = $productResult->fetch_assoc()) {
                echo '<div class="product">';
                echo '<a href="product-details.php?id=' . $row['product_id'] . '">';
                echo '<img src="https://images.unsplash.com/photo-1453486030486-0a5ffcd82cd9?q=80&w=1926&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D?w=500" alt="' . htmlspecialchars($row['name']) . '">';
                echo '</a>';
                echo '<p class="product-name">' . htmlspecialchars($row['name']) . '</p>';
                echo '<p class="product-description">' . htmlspecialchars($row['description']) . '</p>';
                echo '<p class="product-price">Price: $' . htmlspecialchars($row['price']) . '</p>';
                echo '<p class="product-stock">In Stock: ' . htmlspecialchars($row['stock_quantity']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No products available.</p>';
        }
        ?>
    </div>

</body>
</html>
