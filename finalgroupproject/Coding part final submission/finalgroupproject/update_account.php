<?php
session_start();

$hn = 'localhost:3306';
$db = 'suburban_outfitters';
$un = 'root';
$pw = ''; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=update_account.php");
    exit();
}

$conn = new mysqli($hn, $un, $pw, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];
$error = "";
$success = "";

$customerQuery = "SELECT username, email, address, phone_number FROM customer WHERE user_id = ?";
$stmt = $conn->prepare($customerQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$currentUser = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $address = trim($_POST['address']);
    $phoneNumber = trim($_POST['phone_number']);

    if (empty($username) || empty($email) || empty($address) || empty($phoneNumber)) {
        $error = "All fields except password are required.";
    } else {
        $updateQuery = "
            UPDATE customer 
            SET username = ?, email = ?, " . ($password ? "password = ?, " : "") . "address = ?, phone_number = ? 
            WHERE user_id = ?
        ";
        $stmt = $conn->prepare($updateQuery);

        if ($password) {
            $stmt->bind_param("sssssi", $username, $email, $password, $address, $phoneNumber, $userId);
        } else {
            $stmt->bind_param("sssii", $username, $email, $address, $phoneNumber, $userId);
        }

        if ($stmt->execute()) {
            $success = "Account updated successfully.";
            $_SESSION['username'] = $username;
        } else {
            $error = "Failed to update account. Please try again.";
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
    <title>Update Account</title>
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
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
        }
        input {
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
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
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
    <div class="container">
        <h2>Update Account</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <form action="update_account.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($currentUser['username']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($currentUser['email']) ?>" required>

            <label for="password">Password (leave blank to keep current password):</label>
            <input type="password" id="password" name="password">

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($currentUser['address']) ?>" required>

            <label for="phone_number">Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" value="<?= htmlspecialchars($currentUser['phone_number']) ?>" required>

            <button type="submit">Update Account</button>
        </form>
        <a href="homepage.php" class="back-link">Back to Homepage</a>
    </div>
</body>
</html>
