<?php
// Import the dbconnect.php file
session_start();
if (isset($_SESSION["user_id"]) && isset($_SESSION["username"])) {
    // User is logged in
    $loggedIn = true;
    $username = $_SESSION["username"];
} else {
    // User is not logged in
    $loggedIn = false;
}
require_once "dbconnect.php";
// Retrieve destinations from the database
$stmt = $conn->prepare("SELECT id, Name FROM touristdestination");
$stmt->execute();
$destinations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tourism Application - Destinations</title>
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
                    <?php if ($loggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Sign Out</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                        <a class="nav-link" href="signup.html">Sign up</a>
                    </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.html">Login</a>
                        </li>
                    <?php endif; ?>
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
                    <h2>Destinations</h2>
                    <div class="list-group mt-3 opacity-75  ">
                        <?php foreach ($destinations as $destination): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><?php echo $destination['Name']; ?></span>
                                    <div>
                                        <a href="destination_reviews.php?id=<?php echo $destination['id']; ?>" class="btn btn-primary me-2">Destination Review</a>
                                        <a href="buy_ticket.php?id=<?php echo $destination['id']; ?>" class="btn btn-primary">Buy Ticket</a>                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
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
