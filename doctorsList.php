<?php

// Create connection
$conn = new mysqli('localhost', 'root', '', 'remote-med');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for search
$searchName = isset($_GET['search_name']) ? $_GET['search_name'] : '';
$searchSurname = isset($_GET['search_surname']) ? $_GET['search_surname'] : '';
$searchSpecialization = isset($_GET['search_specialization']) ? $_GET['search_specialization'] : '';

$searchCondition = "";
if (!empty($searchName) && empty($searchSurname) && empty($searchSpecialization)) {
    $searchCondition = "WHERE Name LIKE '%$searchName%'";
} 
if (empty($searchName) && empty($searchSurname) && !empty($searchSpecialization)) {
    $searchCondition = "WHERE Spec LIKE '%$searchSpecialization%'";
} 
if (empty($searchName) && !empty($searchSurname) && empty($searchSpecialization)) {
    $searchCondition = "WHERE Surname LIKE '%$searchSurname%'";
}
if (!empty($searchName) && !empty($searchSurname) && empty($searchSpecialization)) {
    $searchCondition = "WHERE Name LIKE '%$searchName%' OR Surname LIKE '%$searchSurname%'";
}
if (empty($searchName) && !empty($searchSurname) && !empty($searchSpecialization)) {
    $searchCondition = "WHERE Surname LIKE '%$searchSurname%' OR Spec LIKE '%$searchSpecialization%'";
}
if (!empty($searchName) && empty($searchSurname) && !empty($searchSpecialization)) {
    $searchCondition = "WHERE Name LIKE '%$searchName%' OR Spec LIKE '%$searchSpecialization%'";
}
if (!empty($searchName) && !empty($searchSurname) && !empty($searchSpecialization)) {
    $searchCondition = "WHERE Name LIKE '%$searchName%' OR Surname LIKE '%$searchSurname%' OR Spec LIKE '%$searchSpecialization%'";
}



// SQL query to retrieve doctors based on search parameters
$sql = "SELECT * FROM doctor $searchCondition";
$result = $conn->query($sql);

// Check if there are doctors
if ($result->num_rows > 0) {
    echo "<h2>List of Doctors</h2>";

    // Display search form
    echo "<form method='get' action=''>
            <div>
                Search by Name: <input type='text' name='search_name' value='$searchName'>
            </div>
            <div>
                Search by Surname: <input type='text' name='search_surname' value='$searchSurname'>
            </div>
            <div>
                Search by Specialization: <input type='text' name='search_specialization' value='$searchSpecialization'>
            </div>
            <div>
                <input type='submit' value='Search'>
            </div>
          </form>";

    echo "<table border='1'>
            <tr>
                <th>Doctor ID</th>
                <th>Doctor Name</th>
                <th>Doctor Surname</th>
                <th>Specialization</th>
                <th>Work Schedule</th>
            </tr>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['d_id']}</td>
                <td>{$row['Name']}</td>
                <td>{$row['Surname']}</td>
                <td>{$row['Spec']}</td>
                <td><a href='/calendar.php?d_id={$row['d_id']}'>View Schedule</a></td>
              </tr>";
    }

    echo "</table>";
} else {

    echo "<h2>List of Doctors</h2>";

    echo "<form method='get' action=''>
            <div>
                Search by Name: <input type='text' name='search_name' value='$searchName'>
            </div>
            <div>
                Search by Surname: <input type='text' name='search_surname' value='$searchSurname'>
            </div>
            <div>
                Search by Specialization: <input type='text' name='search_specialization' value='$searchSpecialization'>
            </div>
            <div>
                <input type='submit' value='Search'>
            </div>
          </form>";

    echo "No doctors found.";
}

// Close the database connection
$conn->close();
?>
