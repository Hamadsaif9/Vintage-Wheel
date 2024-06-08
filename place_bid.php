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

// Check if the form data is set
if (!isset($_POST['vehicle_id']) || !isset($_POST['bid_amount'])) {
    $_SESSION['message'] = "Invalid input";
    $_SESSION['message_type'] = "danger";
    header("Location: list1.php");
    exit();
}

$vehicle_id = intval($_POST['vehicle_id']);
$bid_amount = floatval($_POST['bid_amount']);

if ($vehicle_id <= 0 || $bid_amount <= 0) {
    $_SESSION['message'] = "Invalid input";
    $_SESSION['message_type'] = "danger";
    header("Location: list1.php");
    exit();
}

// Get the current highest bid for the vehicle
$sql = "SELECT MAX(bid_amount) AS max_bid FROM bids WHERE vehicle_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$current_highest_bid = $row['max_bid'] ?? 0;

// Check if the new bid is higher than the current highest bid
if ($bid_amount <= $current_highest_bid) {
    $_SESSION['message'] = "Your bid must be higher than the current highest bid.";
    $_SESSION['message_type'] = "danger";
    header("Location: list1.php");
    exit();
}

// Insert the new bid into the bids table
$sql = "INSERT INTO bids (vehicle_id, bid_amount, user_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$user_id = $_SESSION['user_id']; // Ensure user_id is set in the session
$stmt->bind_param("idi", $vehicle_id, $bid_amount, $user_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Your bid has been placed successfully.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Failed to place your bid. Please try again.";
    $_SESSION['message_type'] = "danger";
}

$stmt->close();
$conn->close();

header("Location: list1.php");
exit();
?>

