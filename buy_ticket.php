<?php
session_start();
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])) {
    // User is not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

require_once "dbconnect.php";

// Check if the destination ID is provided in the URL
if (!isset($_GET['id'])) {
    // Destination ID is not provided, redirect to destinations page
    header("Location: destinations.php");
    exit();
}

$destinationId = $_GET['id'];

// Retrieve destination details from the database
$stmt = $conn->prepare("SELECT Name FROM touristdestination WHERE id = ?");
$stmt->bind_param("i", $destinationId);
$stmt->execute();
$destination = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected date and sanitize it
    $date = $_POST['date'];
    $date = date("Y-m-d", strtotime($date));

    // Get the user ID from the session
    $userId = $_SESSION["user_id"];

    // Retrieve price for the destination from the separate table (assuming table name is "destination_price")
    $priceQuery = $conn->prepare("SELECT price FROM destination_price WHERE Dest_id = ?");
    $priceQuery->bind_param("i", $destinationId);
    $priceQuery->execute();
    $priceResult = $priceQuery->get_result()->fetch_assoc();
    $priceQuery->close();

    if ($priceResult) {
        $price = $priceResult['price'];

        // Insert ticket information into the database
        $insertStmt = $conn->prepare("INSERT INTO ticket (user_id, dest_id, price, D_date) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("iiis", $userId, $destinationId, $price, $date);
        $insertStmt->execute();
        $insertStmt->close();

        // Ticket information saved successfully, redirect to a success page or perform further actions
        header("Location: ticket_success.php");
        exit();
    } else {
        // Price information not found, handle the error (e.g., display an error message)
        $errorMessage = "Price information not available for the selected destination.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tourism Application - Buy Ticket</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Tourism Application</a>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sign Out</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="row">
            <div class="col-md-3">
                <div class="list-group mt-3">
                    <a href="destinations.php" class="list-group-item">Destinations</a>
                    <a href="ticket.php" class="list-group-item">Ticket</a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="container mt-5">
                    <h2 class="bg-white opacity-75 text-black rounded">Buy Ticket</h2>
                    <h3 class="bg-white opacity-75 text-black rounded"><?php echo $destination['Name']; ?></h3>

                    <?php if (isset($errorMessage)): ?>
                        <div class="alert alert-danger mt-3"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3 mt-3">
                            <label for="date" class="form-label bg-white opacity-75 text-black rounded">Select Date:</label>
                            <input type="date" class="form-control opacity-75 text-black rounded" id="date" name="date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Buy Ticket</button>
                    </form>
                </div>
            </div>
        </div>

        <footer class="bg-light text-center mt-5 py-3">
            <div class="container">
                &copy; Heavy Weight Group 1. All Rights Reserved.
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
