<link rel="stylesheet" href="style.css">

<?php
// Start session to secure the page
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

// Handle deletion if 'delete' parameter exists in the URL
if (isset($_GET['delete'])) {
    $matricToDelete = $_GET['delete'];
    $deleteSql = "DELETE FROM users WHERE matric='$matricToDelete'";
    if ($conn->query($deleteSql) === TRUE) {
        echo "<p style='color: green;'>Record deleted successfully.</p>";
    } else {
        echo "<p style='color: red;'>Error deleting record: " . $conn->error . "</p>";
    }
}

// Fetch user data
$sql = "SELECT matric, name, role FROM users";
$result = $conn->query($sql);

// Display the logged-in user
echo "<h1>Welcome, " . $_SESSION['name'] . "!</h1>";
echo "<h2>User List</h2>";

// Display user data in a table
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Matric</th>
                <th>Name</th>
                <th>Level</th>
                <th>Action</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["matric"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["role"] . "</td>
                <td>
                    <a href='update.php?matric=" . $row["matric"] . "'>Update</a> | 
                    <a href='display.php?delete=" . $row["matric"] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No records found.</p>";
}

$conn->close();
?>
<p><a href="logout.php">Logout</a></p>
