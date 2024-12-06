<link rel="stylesheet" href="style.css">

<?php
session_start();
$error_message = ""; // To store error messages
$success_message = ""; // To store success message for logout

// Check if logout success message exists
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $success_message = "You have successfully logged out!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "Lab_5b");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate credentials
    $sql = "SELECT * FROM users WHERE matric='$matric'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['logged_in'] = true;
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];
            header("Location: display.php");
            exit;
        } else {
            $error_message = "Invalid username or password. Please try again."; // Incorrect password
        }
    } else {
        $error_message = "Invalid username or password. Please try again."; // User does not exist
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if ($success_message): ?>
        <p style="color: green;"><?php echo $success_message; ?></p> <!-- Display logout success message -->
    <?php endif; ?>
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p> <!-- Display error message -->
    <?php endif; ?>
    <form method="POST">
        Matric: <input type="text" name="matric" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p> <!-- Link to registration page -->
</body>
</html>
