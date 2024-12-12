<?php
header('Content-Type: application/json');

// Include the configuration file
require_once './exif/config-live.php';

// SQL query to fetch the required fields
$sql = "SELECT record_lat, record_lon, record_date, record_loc FROM records";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $response = array();

    // Check if there are records and fetch them
    if ($stmt->rowCount() > 0) {
        // Fetch all records and store in the response array
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response[] = $row;
        }
    } else {
        $response = array("message" => "No records found");
    }
} catch (PDOException $e) {
    $response = array("error" => "Query failed: " . $e->getMessage());
}

// Close the database connection
$pdo = null;

// Return the JSON response
echo json_encode($response);
?>
