<?php
// Create connection
require_once 'connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerSession']) && !is_null($_POST['d_id'])) {
    $selectedDate = $_POST['date'];
    $selectedHour = $_POST['hour'];
    $selectedD_id = $_POST['d_id'];
    $selectedP_id = $_COOKIE["p_id"];
    $sql = "INSERT INTO `appointment` (`Date`, `Hour`, `d_id`, `p_id`) VALUES ('$selectedDate', '$selectedHour', '$selectedD_id', '$selectedP_id')";

    if ($conn->query($sql)) {
        echo "Data successfully inserted into the table.";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
    // echo "<script>alert('$result');</script>"; // Display result in an alert (for demonstration)
}


?>