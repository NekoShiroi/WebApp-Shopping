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

$error = "";
$success = "";

$productQuery = "SELECT product_id, name FROM product";
$productResult = $conn->query($productQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id']);

    if ($productId <= 0) {
        $error = "Invalid product selected.";
    } else {
        $deleteQuery = "DELETE FROM product WHERE product_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            $success = "Product deleted successfully.";
        } else {
            $error = "Failed to delete the product. Please try again.";
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
    <title>Delete Product</title>
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
            position: relative;
        }
        .exit-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        .exit-btn:hover {
            background-color: #0056b3;
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
        select {
            width: 100%;
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
            background-color: #dc3545;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #c82333;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="homepage.php" class="exit-btn">Exit</a>
        <h2>Delete Product</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <form action="delete_products.php" method="post">
            <label for="product_id">Select Product:</label>
            <select id="product_id" name="product_id" required>
                <option value="" disabled selected>Select a product</option>
                <?php
                if ($productResult->num_rows > 0) {
                    while ($row = $productResult->fetch_assoc()) {
                        echo '<option value="' . $row['product_id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No products available</option>';
                }
                ?>
            </select>
            <button type="submit">Delete</button>
        </form>
    </div>
</body>
</html>
