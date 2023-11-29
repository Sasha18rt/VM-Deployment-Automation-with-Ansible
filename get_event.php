<?php
// Create connection
$conn = new mysqli('localhost', 'root', '', 'remote-med');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_null($_POST['d_id'])) {
    $selectedDate = $_POST['date'];
    $selectedD_id = $_POST['d_id'];
    $stmt = $conn->prepare("SELECT * FROM appointment WHERE Date = ? AND d_id = ?");
    $stmt->bind_param("si", $selectedDate, $selectedD_id); // "si" denotes string and integer types

    $stmt->execute();
    $result = $stmt->get_result();
$data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        $data["error"] = "0 results";
    }

    // Return JSON data
    header('Content-Type: application/json');
    echo json_encode($data);

    $stmt->close();
    $conn->close();
    exit; 
}

?>