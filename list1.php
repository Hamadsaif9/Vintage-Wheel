<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all vehicles
$sql = "SELECT v.*, b.bid_amount AS current_bid, b.user_id AS bidder_id
        FROM vehicles v
        LEFT JOIN (
            SELECT vehicle_id, MAX(bid_amount) AS bid_amount, user_id
            FROM bids
            GROUP BY vehicle_id
        ) b ON v.id = b.vehicle_id";
$result = $conn->query($sql);

$vehicles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Generate random auction end time if not already set
        if (!isset($row['auction_end']) || empty($row['auction_end'])) {
            $row['auction_end'] = date("Y-m-d H:i:s", strtotime('+' . rand(1, 7) . ' days'));
            $vehicle_id = $row['id'];
            $auction_end = $row['auction_end'];
            $initial_bid_price = $row['initial_bid_price'];
            $conn->query("INSERT INTO bids (vehicle_id, bid_amount) VALUES ('$vehicle_id', '$initial_bid_price')");
        }
        $vehicles[] = $row;
    }
} else {
    echo "0 results";
}

$conn->close();

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$logged_in_user_id = $is_logged_in ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .auction-container {
            margin-top: 50px;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .footer {
            background-color: #212529;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .countdown {
            font-size: 1.25em;
            font-weight: bold;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var countdownElements = document.querySelectorAll('.countdown');
            countdownElements.forEach(function(element) {
                var endTime = new Date(element.getAttribute('data-end')).getTime();
                var vehicleId = element.getAttribute('data-vehicle-id');
                var bidderId = element.getAttribute('data-bidder-id');

                function updateCountdown() {
                    var now = new Date().getTime();
                    var distance = endTime - now;

                    if (distance < 0) {
                        clearInterval(countdownInterval);
                        element.innerHTML = "Auction Ended";
                        window.location.href = 'payment.php?vehicle_id=' + vehicleId + '&bidder_id=' + bidderId;
                        return;
                    }

                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    element.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                }

                updateCountdown();
                var countdownInterval = setInterval(updateCountdown, 1000);
            });
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Vintage Wheel Auctions</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.html">Search</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container auction-container">
        <h2>Current Auctions</h2>

        <!-- Display message if exists -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="img/<?php echo htmlspecialchars($vehicle['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($vehicle['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($vehicle['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($vehicle['description']); ?></p>
                            <p><strong>Model:</strong> <?php echo htmlspecialchars($vehicle['model']); ?></p>
                            <p><strong>Brand:</strong> <?php echo htmlspecialchars($vehicle['brand']); ?></p>
                            <p><strong>Initial Bid Price:</strong> <?php echo number_format($vehicle['initial_bid_price'], 2); ?> OMR</p>
                            <p><strong>Current Bid:</strong> <?php echo number_format($vehicle['current_bid'] ?? $vehicle['initial_bid_price'], 2); ?> OMR</p>
                            <p><strong>Auction Ends:</strong> <span class="countdown" data-end="<?php echo htmlspecialchars($vehicle['auction_end']); ?>" data-vehicle-id="<?php echo htmlspecialchars($vehicle['id']); ?>" data-bidder-id="<?php echo htmlspecialchars($vehicle['bidder_id']); ?>"></span></p>
                            <form action="place_bid.php" method="post">
                                <input type="hidden" name="vehicle_id" value="<?php echo htmlspecialchars($vehicle['id']); ?>">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($logged_in_user_id); ?>">
                                <input type="number" class="form-control mb-2" name="bid_amount" placeholder="Enter your bid" required <?php if (!$is_logged_in) echo 'disabled'; ?>>
                                <button type="submit" class="btn btn-primary" <?php if (!$is_logged_in) echo 'disabled'; ?>>Place Bid</button>
                            </form>
                            <?php if (!$is_logged_in): ?>
                                <p class="text-danger">You must be logged in to place a bid.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="footer bg-dark text-white mt-5 p-4 text-center">
        <p>© 2024 Vintage Wheel Auctions</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
