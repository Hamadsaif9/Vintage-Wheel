<?php
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

$vehicle_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'add') {
        // Sanitize input data
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $model = mysqli_real_escape_string($conn, $_POST['model']);
        $brand = mysqli_real_escape_string($conn, $_POST['brand']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $initial_bid_price = mysqli_real_escape_string($conn, $_POST['initial_bid_price']);
        $image = $_FILES['image']['name'];

        // Validate and upload image file
        $target_dir = "C:/xampp/htdocs/VintageWheel/img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions_arr = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $extensions_arr)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Insert record
                $sql = "INSERT INTO vehicles (name, model, brand, description, initial_bid_price, image) VALUES ('$name', '$model', '$brand', '$description', '$initial_bid_price', '$image')";
                if ($conn->query($sql) === TRUE) {
                    $vehicle_message = "New vehicle added successfully!";
                } else {
                    $vehicle_message = "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                $vehicle_message = "Failed to upload image.";
            }
        } else {
            $vehicle_message = "Invalid file extension. Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    } elseif ($_POST['action'] == 'delete') {
        $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);

        // Delete bids associated with the vehicle
        $sql = "DELETE FROM bids WHERE vehicle_id='$vehicle_id'";
        if ($conn->query($sql) === TRUE) {
            // Now delete the vehicle
            $sql = "DELETE FROM vehicles WHERE id='$vehicle_id'";
            if ($conn->query($sql) === TRUE) {
                // Delete the image file and any other cleanup
                $vehicle_message = "Vehicle deleted successfully!";
            } else {
                $vehicle_message = "Error deleting vehicle: " . $conn->error;
            }
        } else {
            $vehicle_message = "Error deleting bids: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Delete Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .form-container, .list-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .footer {
            background-color: #212529;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        
        .vehicle-image {
            width: 150px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            margin-bottom: 20px;
            color: #343a40;
        }

        .btn-primary, .btn-danger {
            width: 100%;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
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
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list1.php">Auctions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.html">Search</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Add a New Vehicle</h2>
        <div class="form-container">
            <form action="add_vehicle.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Vehicle Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="model" class="form-label">Model</label>
                    <input type="text" class="form-control" id="model" name="model" required>
                </div>
                <div class="mb-3">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" id="brand" name="brand" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="initial_bid_price" class="form-label">Initial Bid Price</label>
                    <input type="number" class="form-control" id="initial_bid_price" name="initial_bid_price" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary" name="action" value="add">Add Vehicle</button>
            </form>
        </div>

        <h2>Delete an Existing Vehicle</h2>
        <div class="list-container">
            <form action="add_vehicle.php" method="post">
                <div class="mb-3">
                    <label for="vehicle_id" class="form-label">Select Vehicle</label>
                    <select class="form-control" id="vehicle_id" name="vehicle_id" required>
                        <?php
                        // PHP code to fetch vehicle list from database
                        $conn = new mysqli("localhost", "root", "", "project");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $sql = "SELECT id, name, image FROM vehicles";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "' data-image='img/" . $row['image'] . "'>" . $row['name'] . "</option>";
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="mb-3 text-center">
                    <img id="vehicle_image" src="" alt="Vehicle Image" class="vehicle-image d-none">
                </div>
                <button type="submit" class="btn btn-danger" name="action" value="delete">Delete Vehicle</button>
            </form>
        </div>
        <?php if ($vehicle_message): ?>
            <div class="alert alert-success" role="alert">
                <?= $vehicle_message ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>© 2024 Vintage Wheel Auctions</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('vehicle_id').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var imageSrc = selectedOption.getAttribute('data-image');
            var vehicleImage = document.getElementById('vehicle_image');
            if (imageSrc) {
                vehicleImage.src = imageSrc;
                vehicleImage.classList.remove('d-none');
            } else {
                vehicleImage.classList.add('d-none');
            }
        });
    </script>
</body>
</html>
