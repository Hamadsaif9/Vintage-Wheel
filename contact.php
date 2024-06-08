<?php
// Define variables for the message status
$messageSent = false;
$error = '';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $fromEmail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Check if data is valid
    if ($name && $fromEmail && $message) {
        // Store the message in the database
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

        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $fromEmail, $message);

        if ($stmt->execute()) {
            $messageSent = true;
        } else {
            $error = 'Sorry, there was an error saving your message.';
        }

        $stmt->close();
        $conn->close();
    } else {
        $error = 'Invalid input detected. Please fill all fields correctly.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Vintage Wheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .container {
            max-width: 600px;
        }
        .footer, .project-info {
            text-align: center;
            margin-top: 40px;
            background-color: #e9ecef;
            border-radius: 5px;
            padding: 20px;
        }
        .footer {
            border-top: 1px solid #ccc;
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

    <div class="container shadow-sm p-4 bg-white">
        <h2>Contact Us</h2>
        <p>If you have any questions, please feel free to drop us your questions. We'll reply back as soon as we can. That's a promise!</p>
        <div class="mb-3">
            <label for="email" class="form-label">Email us directly:</label>
            <p><a href="mailto:vintage.wheel.mec@gmail.com">vintage.wheel.mec@gmail.com</a></p>
        </div>
        <?php if ($messageSent): ?>
            <div class="alert alert-success" role="alert">
                Message sent successfully. We will respond as soon as possible.
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Your Message</label>
                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Type your message here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
        <div class="project-info">
            <h4>Project Information</h4>
            <p><strong>Project Title:</strong> Vintage Wheel: A Website for Classic Vehicles Auction</p>
            <p><strong>Team Members:</strong> Hamed AlBalushi, Nasser Alsaqri, Basma Alsiyabi, Lama Albusaidi, Maya Al Rahbi</p>
            <p><strong>Supervisor:</strong> Mr. Mohamed Samiulla Khan</p>
            <p><strong>Submitted to:</strong> Middle East College</p>
        </div>
        <div class="footer">
            <p>Contact Information: <a href="mailto:vintage.wheel.mec@gmail.com">vintage.wheel.mec@gmail.com</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
