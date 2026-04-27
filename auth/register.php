<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../database/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    if (empty($username) || empty($email) || empty($_POST["password"])) {
        $message = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, pswd, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            $message = "User registered successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>

<body>

<div class="auth-container">
    <div class="auth-box">

        <h2>Register</h2>

        <?php if ($message): ?>
            <p class="auth-message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Register</button>
        </form>

        <p class="auth-link">
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>
</div>

</body>
</html>