<link rel="stylesheet" href="style.css">

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password
    $role = $_POST['role'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "Lab_5b");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if matric already exists
    $checkSql = "SELECT * FROM users WHERE matric = '$matric'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Duplicate matric found
        echo "<p style='color: red;'>Error: Matric number already exists. Please use a different matric number.</p>";
    } else {
        // Insert into the database
        $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $matric, $name, $password, $role);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Registration successful!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration</title>
</head>
<body>
    <h1>User Registration</h1>
    <form method="POST">
        Matric: <input type="text" name="matric" required><br>
        Name: <input type="text" name="name" required><br>
        Password: <input type="password" name="password" required><br>
        Role: <select name="role" id="role" required>
                <option value="">Please select</option>
                <option value="lecturer">Lecturer</option>
                <option value="student">Student</option>
              </select><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
