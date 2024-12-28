<?php
session_start();

$hn = 'localhost:3306';
$db = 'suburban_outfitters';
$un = 'root';
$pw = ''; 

$conn = new mysqli($hn, $un, $pw, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
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
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password FROM customer WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        if (password_verify($password, $storedPassword)) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $username;

            $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'homepage.php';
            header("Location: " . htmlspecialchars($redirect));
            exit;
        } elseif ($password === $storedPassword) { 
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $username;

            $redirect = isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : 'homepage.php';
            header("Location: " . htmlspecialchars($redirect));
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Suburban Outfitters</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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
            display: flex;
            align-items: center;
        }
        #nav a:hover {
            background-color: #ddd;
            color: black;
        }
        #nav .user-status {
            color: white;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .login-container {
            width: 300px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 91%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container .btn {
            display: block;
            padding: 10px 0;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            color: white;
            font-size: 16px;
            width: 100%;
            margin-bottom: 10px;
        }
        .btn-login {
            background-color: #4CAF50;
            border: none;
        }
        .btn-login:hover {
            background-color: #45a049;
        }
        .btn-create-account {
            background-color: #007bff;
            border: none;
        }
        .btn-create-account:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div id="banner">
        <h1>Suburban Outfitters</h1>
    </div>

    <div id="nav">
        <div style="display: flex;">
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

    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="login.php<?= isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn btn-login">Login</button>
        </form>
        <a href="create_account.php" class="btn btn-create-account">Create Account</a>
    </div>

</body>
</html>
