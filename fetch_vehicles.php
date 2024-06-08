<?php
$host = 'localhost'; 
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM vehicles";
$result = $conn->query($sql);

$vehicles = [];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();
?>
