<link rel="stylesheet" href="style.css">

<?php
// Start session
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "Lab_5b");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$matric = "";
$name = "";
$role = "";

// Handle form submission for updating the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    $updateSql = "UPDATE users SET name='$name', role='$role' WHERE matric='$matric'";
    if ($conn->query($updateSql) === TRUE) {
        echo "<p style='color: green;'>User updated successfully!</p>";
        header("Location: display.php");
        exit;
    } else {
        echo "<p style='color: red;'>Error updating user: " . $conn->error . "</p>";
    }
}

// Fetch the user's current data to pre-fill the form
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];
    $fetchSql = "SELECT * FROM users WHERE matric='$matric'";
    $result = $conn->query($fetchSql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $role = $row['role'];
    } else {
        echo "<p style='color: red;'>User not found.</p>";
        header("Location: display.php");
        exit;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update User</title>
</head>
<body>
    <h1>Update User</h1>
    <form method="POST">
        <!-- Hidden matric field (readonly) -->
        Matric: <input type="text" name="matric" value="<?php echo $matric; ?>" readonly><br>
        
        <!-- Editable fields -->
        Name: <input type="text" name="name" value="<?php echo $name; ?>" required><br>
        Role: 
        <select name="role" required>
            <option value="lecturer" <?php if ($role == 'lecturer') echo 'selected'; ?>>Lecturer</option>
            <option value="student" <?php if ($role == 'student') echo 'selected'; ?>>Student</option>
        </select><br>
        
        <!-- Submit button -->
        <button type="submit">Update</button>
    </form>
    <p><a href="display.php">Back to User List</a></p>
</body>
</html>
