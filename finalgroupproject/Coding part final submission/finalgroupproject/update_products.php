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
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$role = "";

$stmt = $conn->prepare("SELECT role FROM customer WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $role = $row['role'];
}

$stmt->close();

if ($role !== 'admin') {
    echo "<p>You do not have permission to access this page.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);

    $updateQuery = "UPDATE product SET name = ?, description = ?, price = ?, stock_quantity = ? WHERE product_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssdii", $name, $description, $price, $stock_quantity, $productId);

    if ($stmt->execute()) {
        $message = "Product ID $productId updated successfully!";
    } else {
        $message = "Failed to update Product ID $productId: " . $stmt->error;
    }

    $stmt->close();
}

$productQuery = "SELECT product_id, name, description, price, stock_quantity FROM product";
$productResult = $conn->query($productQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Products</title>
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
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .update-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .update-btn:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            font-size: 16px;
            margin: 10px 0;
            color: #28a745;
        }
        .error-message {
            text-align: center;
            font-size: 16px;
            margin: 10px 0;
            color: #dc3545;
        }
        #exit-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            background-color: #dc3545;
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 50px; 
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            transition: all 0.3s ease-in-out;
        }

        #exit-btn:hover {
            background-color: #c82333;
            transform: scale(1.1); 
        }
    </style>
</head>
<body>
    <div id="banner">
        <h1>Update Products</h1>
        <button id="exit-btn" onclick="window.location.href='homepage.php';">Exit</button>
    </div>
    <div class="container">
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        <h2>Product List</h2>
        <?php if ($productResult->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $productResult->fetch_assoc()): ?>
                        <tr>
                            <form method="post">
                                <td>
                                    <?= htmlspecialchars($row['product_id']) ?>
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['product_id']) ?>">
                                </td>
                                <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>"></td>
                                <td><input type="text" name="description" value="<?= htmlspecialchars($row['description']) ?>"></td>
                                <td><input type="number" step="0.01" name="price" value="<?= htmlspecialchars($row['price']) ?>"></td>
                                <td><input type="number" name="stock_quantity" value="<?= htmlspecialchars($row['stock_quantity']) ?>"></td>
                                <td><button type="submit" class="update-btn">Update</button></td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
