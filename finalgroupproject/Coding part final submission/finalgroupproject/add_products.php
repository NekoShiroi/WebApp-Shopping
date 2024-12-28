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

$categoryQuery = "SELECT category_id, name FROM category";
$categoryResult = $conn->query($categoryQuery);

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stockQuantity = intval($_POST['stock_quantity']);
    $categoryId = intval($_POST['category_id']);
    $createdAt = date("Y-m-d H:i:s");

    if (empty($name) || empty($description) || $price <= 0 || $stockQuantity < 0 || $categoryId <= 0) {
        $error = "Please provide valid inputs for all fields.";
    } else {
        $insertProductQuery = "
            INSERT INTO product (name, description, price, stock_quantity, category_id, created_at)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $stmt = $conn->prepare($insertProductQuery);
        $stmt->bind_param("ssdiss", $name, $description, $price, $stockQuantity, $categoryId, $createdAt);

        if ($stmt->execute()) {
            header("Location: homepage.php"); 
            exit();
        } else {
            $error = "Failed to add product. Please try again.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 500px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 95%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
            color: white;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="add_products.php" method="post">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" required>

            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <option value="" disabled selected>Select a category</option>
                <?php
                if ($categoryResult->num_rows > 0) {
                    while ($row = $categoryResult->fetch_assoc()) {
                        echo '<option value="' . $row['category_id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No categories available</option>';
                }
                ?>
            </select>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
