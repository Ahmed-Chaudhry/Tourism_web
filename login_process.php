<?php
// Import the dbconnection.php file
require_once "dbconnect.php";

// Start a session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the query returned a row
    if ($result->num_rows == 1) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Store user data in the session
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        // Redirect to home.php or any other page
        header("Location: home.php");
        exit;
    } else {
        // Invalid credentials, display an error message
        echo "Invalid username or password.";
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>
