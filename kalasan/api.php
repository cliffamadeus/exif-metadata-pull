<?php
header('Content-Type: application/json');

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dev_exif";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("error" => "Connection failed: " . $conn->connect_error)));
}

// SQL query to fetch the required fields
$sql = "SELECT record_lat, record_lon, record_date, record_loc FROM records";
$result = $conn->query($sql);

$response = array();

if ($result->num_rows > 0) {
    // Fetch all records and store in the response array
    while($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    $response = array("message" => "No records found");
}

// Close the database connection
$conn->close();

// Return the JSON response
echo json_encode($response);
?>
