<?php
session_start();

$hn = 'localhost:3306'; 
$db = 'suburban_outfitters'; 
$un = 'root'; 
$pw = ''; 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=order_history.php");
    exit();
}

$conn = new mysqli($hn, $un, $pw, $db);

$userId = $_SESSION['user_id'];
$username = "";
$role = "";

$userQuery = "SELECT username, role FROM customer WHERE user_id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
if ($userResult->num_rows === 1) {
    $user = $userResult->fetch_assoc();
    $username = $user['username'];
    $role = $user['role'];
}
$userStmt->close();

$orderQuery = "
    SELECT o.order_id, o.order_date, o.status, o.total_amount, o.shipping_address, 
           c.username, c.email
    FROM `order` o
    INNER JOIN customer c ON o.user_id = c.user_id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC
";
$orderStmt = $conn->prepare($orderQuery);
$orderStmt->bind_param("i", $userId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        #banner { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
        #nav { background-color: #333; display: flex; justify-content: space-between; align-items: center; padding: 0 10px; height: 50px; }
        #nav a { color: white; text-decoration: none; padding: 14px 16px; }
        #nav a:hover { background-color: #ddd; color: black; }
        .user-status { color: white; font-size: 14px; }
        .container { width: 80%; margin: 30px auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div id="banner">
        <h1>Order History</h1>
    </div>
    <div id="nav">
        <div>
            <a href="homepage.php">Shop</a>
            <a href="login.php">Login</a>
        </div>
        <div class="user-status">
            <?= htmlspecialchars($role) ?>: <?= htmlspecialchars($username) ?>
        </div>
    </div>

    <div class="container">
        <h2>Your Order History</h2>
        <?php if ($orderResult->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Shipping Address</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orderResult->fetch_assoc()): ?>
                        <tr>
                            <form method="POST" action="order_process.php">
                                <td><?= htmlspecialchars($order['order_id']) ?></td>
                                <td><?= htmlspecialchars($order['order_date']) ?></td>
                                <td>
                                    <input type="text" name="status" value="<?= htmlspecialchars($order['status']) ?>" required>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="total_amount" value="<?= htmlspecialchars($order['total_amount']) ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="shipping_address" value="<?= htmlspecialchars($order['shipping_address']) ?>" required>
                                </td>
                                <td><?= htmlspecialchars($order['username']) ?></td>
                                <td><?= htmlspecialchars($order['email']) ?></td>
                                <td>
                                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                                    <button type="submit" name="action" value="update">Update</button>
                                    <button type="submit" name="action" value="delete" onclick="return confirm('Are you sure you want to delete this order?');">Delete</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No order history found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
