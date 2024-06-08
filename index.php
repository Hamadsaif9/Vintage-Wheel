<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : 0;
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classic Vehicle Auction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .banner {
            background: url('R6.jpg') no-repeat center center;
            background-size: cover;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-shadow: 2px 2px 4px #000000;
        }

        .featured {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .footer {
            background-color: #212529;
            color: white;
            padding: 10px;
            text-align: center;
        }

        .navbar-nav .welcome-message {
            color: #fff;
            display: flex;
            align-items: center;
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
                        <a class="nav-link" href="list1.php">Auctions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.html">Search</a>
                    </li>
                    <?php if ($is_logged_in && $user_id >= 1 && $user_id <= 5): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add_vehicle.php">Add/Delete Vehicle</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="readMsg.php">show meassages</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="confirmLogout()">Sign Out</a>
                        </li>
                        <li class="nav-item welcome-message">
                            Welcome, <?php echo htmlspecialchars($user_name); ?>!
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="banner">
        <h1>Discover Classic Gems</h1>
    </div>

    <div class="container mt-4">
        <h2>Featured Vehicles</h2>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="bilAir.jpg" class="card-img-top" alt="Vehicle Image" width="600" height="300">
                    <div class="card-body">
                        <h5 class="card-title">1957 Chevrolet Bel Air</h5>
                        <p class="card-text">Discover the timeless elegance of the 1957 Chevrolet Bel Air, a true icon of classic American automotive design, now available for auction.</p>
                        <a href="vehicle.php?id=4" class="btn btn-primary">Go to list page</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="FordM.jpg" class="card-img-top" alt="Vehicle Image" width="600" height="300">
                    <div class="card-body">
                        <h5 class="card-title">1966 Ford Mustang</h5>
                        <p class="card-text">Experience the classic allure of the 1966 Ford Mustang, a true masterpiece of American muscle, featured in our latest auction lineup.</p>
                        <a href="vehicle.php?id=8" class="btn btn-primary">Go to list page</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="dodge.jpg" class="card-img-top" alt="Vehicle Image" width="600" height="300">
                    <div class="card-body">
                        <h5 class="card-title">1970 Dodge Charger</h5>
                        <p class="card-text">Experience the raw power and timeless elegance of the 1970 Dodge Charger, a true classic that epitomizes the golden era of American muscle cars.</p>
                        <a href="vehicle.php?id=9" class="btn btn-primary">Go to list page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>© 2024 Vintage Wheel Auctions</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to sign out?")) {
                window.location.href = 'logout.php';
            }
        }
    </script>
</body>
</html>
