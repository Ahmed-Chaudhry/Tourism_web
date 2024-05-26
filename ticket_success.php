
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tourism Application - Ticket Purchase Successful</title>
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
                    <h2>Ticket Purchase Successful</h2>
                    <div class="alert alert-success mt-3">
                        <p>Your ticket purchase was successful.</p>
                        <p>Thank you for choosing our Tourism Application!</p>
                    </div>
                    <a href="destinations.php" class="btn btn-primary">Back to Destinations</a>
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
