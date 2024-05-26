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

// Check if the user is logged in


// Get the destination ID from the query string
if (isset($_GET['id'])) {
    $destinationId = $_GET['id'];

    // Retrieve destination details from the database
    $stmt = $conn->prepare("SELECT Name FROM touristdestination WHERE id = ?");
    $stmt->bind_param("i", $destinationId);
    $stmt->execute();
    $destination = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Retrieve reviews for the destination
    $stmt = $conn->prepare("SELECT r.rating, r.comment, u.username FROM review AS r
                            INNER JOIN user AS u ON r.user_id = u.id
                            WHERE r.dest_id = ?");
    $stmt->bind_param("i", $destinationId);
    $stmt->execute();
    $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Calculate the total rating
    $stmt = $conn->prepare("SELECT SUM(rating) AS total_rating FROM review WHERE dest_id = ?");
    $stmt->bind_param("i", $destinationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalRating = $result->fetch_assoc()['total_rating'];
    $totalReviews = $result->num_rows;
    $averageRating = ($totalReviews > 0) ? round($totalRating / $totalReviews, 2) : 0;
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted rating and comment
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];

    // Validate rating (1-5)
    if ($rating < 1 || $rating > 5) {
        $error = "Rating must be between 1 and 5.";
    } else {
        // Insert the review into the database
        $stmt = $conn->prepare("INSERT INTO review (user_id, dest_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $userId, $destinationId, $rating, $comment);
        $stmt->execute();
        $stmt->close();

        // Redirect back to the destination_reviews.php page to prevent form resubmission
        header("Location: destination_reviews.php?id=$destinationId");
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tourism Application - Destination Reviews</title>
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
                    <h2><?php echo $destination['Name']; ?></h2>
                    <p class="bg-white opacity-75 text-black rounded">Total Rating: <?php echo $averageRating; ?>/5 (Based on <?php echo $totalReviews; ?> reviews)</p>

                    <?php if ($loggedIn): ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                Add a Review
                            </div>
                            <div class="card-body ">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label for="rating">Rating (1-5):</label>
                                        <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="comment">Comment:</label>
                                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <h4 class="bg-white opacity-75 text-black rounded">Reviews:</h4>
                    <?php foreach ($reviews as $review): ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <?php echo $review['username']; ?>
                            </div>
                            <div class="card-body">
                                <p>Rating: <?php echo $review['rating']; ?>/5</p>
                                <p><?php echo $review['comment']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
