<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])) {
    // User is not logged in, redirect to home page
    header("Location: home.php");
    exit();
}

// Import the dbconnect.php file
require_once "dbconnect.php";

// Get the user ID from the session
$userID = $_SESSION["user_id"];

// Retrieve the user's tickets from the database
$stmt = $conn->prepare("SELECT touristdestination.Name, ticket.price, ticket.D_date, ticket.Dest_id
                       FROM ticket
                       INNER JOIN touristdestination ON ticket.Dest_id = touristdestination.id
                       WHERE ticket.User_id = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$tickets = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tourism Application - Tickets</title>
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
                    <a href="ticket.php" class="list-group-item">Tickets</a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="container mt-5 ">
                    <h2>Tickets</h2>
                    <?php if (count($tickets) > 0): ?>
                        <table class="table mt-3 opacity-75 text-black rounded">
                            <thead>
                                <tr>
                                    <th>Tourist Destination</th>
                                    <th>Price</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tickets as $ticket): ?>
                                    <tr>
                                        <td><?php echo $ticket['Name']; ?></td>
                                        <td><?php echo $ticket['price']; ?></td>
                                        <td><?php echo $ticket['D_date']; ?></td>
                                        <td>
                                            <form action="delete_ticket.php" method="POST">
                                                <input type="hidden" name="user_id" value="<?php echo $userID; ?>">
                                                <input type="hidden" name="dest_id" value="<?php echo $ticket['Dest_id']; ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No tickets found.</p>
                    <?php endif; ?>
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
