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
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $address = trim($_POST['address']);
    $phone_number = trim($_POST['phone_number']);
    $role = $_POST['role']; 
    $created_at = date("Y-m-d H:i:s");

    $checkQuery = "SELECT * FROM customer WHERE username = ? OR email = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $error = "Username or email already exists.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery = "
            INSERT INTO customer (username, email, password, address, phone_number, role, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sssssss", $username, $email, $hashedPassword, $address, $phone_number, $role, $created_at);

        if ($insertStmt->execute()) {
            $success = "Account created successfully! You can now log in.";
        } else {
            $error = "An error occurred. Please try again.";
        }

        $insertStmt->close();
    }

    $checkStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
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
            width: 300px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .container input[type="text"],
        .container input[type="email"],
        .container input[type="password"],
        .container input[type="tel"],
        .container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background-color: #fff;
            box-sizing: border-box;
        }

        .container select {
            cursor: pointer;
            appearance: auto;
        }

        .container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .container input[type="submit"]:hover {
            background-color: #45a049;
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

        .back-link {
            text-align: center;
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
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
        <h2>Create Account</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <form action="create_account.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="tel" name="phone_number" placeholder="Phone Number" required>
            <select name="role" required>
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>
            <input type="submit" value="Create Account">
        </form>
        <a href="login.php" class="back-link">Back to Login</a>
    </div>
</body>
</html>
