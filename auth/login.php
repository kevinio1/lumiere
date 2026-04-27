<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include __DIR__ . "/../database/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $message = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, pswd FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $username;
                $message = "Login successful!";
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "No user found with that email.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>

<body>

<div class="auth-container">
    <div class="auth-box">

        <h2>Login</h2>

        <?php if ($message): ?>
            <p class="auth-message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>
        </form>

        <p class="auth-link">
            Don’t have an account?
            <a href="register.php">Register</a>
        </p>

    </div>
</div>

</body>
</html>