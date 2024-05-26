<?php
// Import the dbconnection.php file
require_once "dbconnect.php";

// Start a session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted username, password, and other necessary fields
    $username = $_POST["username"];
    $password = $_POST["password"];


    // Perform basic validation
    if (empty($username) || empty($password) ) {
        echo "All fields are required.";
      }
    else {
        // Check if the username or email already exists
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username or email already exists.";
        } else {
            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO user (username, password) VALUES (?, ?)");

            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                // Registration successful, store user data in the session
                $_SESSION["user_id"] = $stmt->insert_id;
                $_SESSION["username"] = $username;

                // Redirect to home.php or any other page
                header("Location: home.php");
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    }
}
?>
