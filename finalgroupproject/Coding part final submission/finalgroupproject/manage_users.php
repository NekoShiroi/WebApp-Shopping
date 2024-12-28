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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user_id'])) {
    $updateUserId = intval($_POST['update_user_id']);
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    $updateCustomerQuery = "UPDATE customer SET username = ?, email = ?, address = ?, phone_number = ? WHERE user_id = ?";
    $stmt = $conn->prepare($updateCustomerQuery);
    $stmt->bind_param("ssssi", $username, $email, $address, $phone_number, $updateUserId);

    if ($stmt->execute()) {
        $message = "User ID $updateUserId updated successfully!";
    } else {
        $message = "Failed to update User ID $updateUserId: " . $stmt->error;
    }

    $stmt->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $deleteUserId = intval($_POST['delete_user_id']);
    $deleteCustomerQuery = "DELETE FROM customer WHERE user_id = ?";
    $stmt = $conn->prepare($deleteCustomerQuery);
    $stmt->bind_param("i", $deleteUserId);

    if ($stmt->execute()) {
        $message = "User ID $deleteUserId deleted successfully!";
    } else {
        $message = "Failed to delete User ID $deleteUserId: " . $stmt->error;
    }

    $stmt->close();
}

$query = "
    SELECT 
        c.user_id, c.username, c.email, c.address, c.phone_number,
        o.order_id, o.status AS order_status, o.total_amount, o.shipping_address
    FROM 
        customer c
    LEFT JOIN 
        `order` o ON c.user_id = o.user_id
    ORDER BY c.user_id, o.order_id
";
$result = $conn->query($query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
            position: relative;
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
            cursor: pointer;
            font-size: 14px;
        }
        #exit-btn:hover {
            background-color: #c82333;
        }
        .container {
            width: 90%;
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
        .delete-btn {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .message {
            text-align: center;
            font-size: 16px;
            margin: 10px 0;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div id="banner">
        <h1>Manage Users</h1>
        <button id="exit-btn" onclick="window.location.href='homepage.php';">Exit</button>
    </div>
    <div class="container">
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        <h2>User Details</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Order ID</th>
                        <th>Order Status</th>
                        <th>Total Amount</th>
                        <th>Shipping Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <form method="post">
                                <td>
                                    <?= htmlspecialchars($row['user_id']) ?>
                                    <input type="hidden" name="update_user_id" value="<?= htmlspecialchars($row['user_id']) ?>">
                                </td>
                                <td><input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>"></td>
                                <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
                                <td><input type="text" name="address" value="<?= htmlspecialchars($row['address']) ?>"></td>
                                <td><input type="tel" name="phone_number" value="<?= htmlspecialchars($row['phone_number']) ?>"></td>
                                <td><?= htmlspecialchars($row['order_id'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['order_status'] ?? 'N/A') ?></td>
                                <td>$<?= htmlspecialchars(number_format($row['total_amount'] ?? 0, 2)) ?></td>
                                <td><?= htmlspecialchars($row['shipping_address'] ?? 'N/A') ?></td>
                                <td>
                                    <button type="submit" class="update-btn">Update</button>
                                    <button type="submit" name="delete_user_id" value="<?= htmlspecialchars($row['user_id']) ?>" class="delete-btn">Delete</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users or orders available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
