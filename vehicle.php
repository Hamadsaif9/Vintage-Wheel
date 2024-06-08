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

// Fetch vehicle details by ID
$vehicle_id = isset($_GET['id']) ? $_GET['id'] : 0;
$sql = "SELECT v.*, b.bid_amount AS current_bid
        FROM vehicles v
        LEFT JOIN (
            SELECT vehicle_id, MAX(bid_amount) AS bid_amount
            FROM bids
            GROUP BY vehicle_id
        ) b ON v.id = b.vehicle_id
        WHERE v.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $vehicle = $result->fetch_assoc();
} else {
    echo "Vehicle not found";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $vehicle['name']; ?> - Vintage Wheel Auctions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .vehicle-container {
            margin-top: 50px;
        }
        .card-img-top {
            height: 400px;
            object-fit: cover;
        }
        .footer {
            background-color: #212529;
            color: white;
            padding: 10px;
            text-align: center;
        }
    </style>
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
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list1.php">Auctions</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="add_vehicle.html">Add</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container vehicle-container">
        <h2><?php echo $vehicle['name']; ?></h2>
        <div class="card mb-3">
            <img src="img/<?php echo $vehicle['image']; ?>" class="card-img-top" alt="<?php echo $vehicle['name']; ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo $vehicle['name']; ?></h5>
                <p class="card-text"><?php echo $vehicle['description']; ?></p>
                <p><strong>Model:</strong> <?php echo $vehicle['model']; ?></p>
                <p><strong>Brand:</strong> <?php echo $vehicle['brand']; ?></p>
                <p><strong>Initial Bid Price:</strong> <?php echo number_format($vehicle['initial_bid_price'], 2); ?> OMR</p>
                <p><strong>Current Bid:</strong> <?php echo number_format($vehicle['current_bid'] ?? $vehicle['initial_bid_price'], 2); ?> OMR</p>
                <form action="place_bid.php" method="post">
                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
                    <input type="number" class="form-control mb-2" name="bid_amount" placeholder="Enter your bid" required>
                    <button type="submit" class="btn btn-primary">Place Bid</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer bg-dark text-white mt-5 p-4 text-center">
        <p>© 2024 Vintage Wheel Auctions</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
