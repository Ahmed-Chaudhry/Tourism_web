<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION["user_id"]) && isset($_SESSION["username"])) {
    // User is logged in
    $loggedIn = true;
    $username = $_SESSION["username"];
} else {
    // User is not logged in
    $loggedIn = false;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tourism Application - Home</title>
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
                    <a href="ticket.php" class="list-group-item">Tickets</a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="container mt-5 bg-dark opacity-75 ">
                    <h2 >Welcome to the Tourism Application</h2>
                    <?php if ($loggedIn): ?>
                        <h2 class="">Hello, <?php echo $username; ?>! You are logged in.</h2>
                    <?php else: ?>
                        <h2>Please login to access the features of the Tourism Application.</h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>


    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
