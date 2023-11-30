<?php

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "remote-med";

// Create connection
$conn = new mysqli('localhost', 'root', '', 'remote-med');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchD_id = isset($_GET['search_D_id']) ? $_GET['search_D_id'] : '';
$searchAppointment = isset($_GET['search_appointment']) ? $_GET['search_appointment'] : '';


// Define opening days and hours
$openingDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$openingHours = range(8, 17); // 8 AM to 5 PM

// Get the current month and year
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$d_id = $_COOKIE["d_id"];
// Calculate the number of days in the month
$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

function getNextMonthYear($month, $year, $d_id) {
    $date = DateTime::createFromFormat('Y-m', "$year-$month")->modify('+1 month');
    return ['month' => $date->format('m'), 'year' => $date->format('Y'), 'd_id' => $d_id];
}

// Function to get the previous month and year
function getPrevMonthYear($month, $year, $d_id) {
    $date = DateTime::createFromFormat('Y-m', "$year-$month")->modify('-1 month');
    return ['month' => $date->format('m'), 'year' => $date->format('Y'), 'd_id' => $d_id];
}

// Calculate next and previous months
$nextMonthYear = getNextMonthYear($month, $year, $d_id);
$prevMonthYear = getPrevMonthYear($month, $year, $d_id);


function getSessionsFromDatabase() {
    return [
        '2023-01-15 09:00' => 'Booked',
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Calendar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            width: 40px;
            height: 40px;
        }
        th {
            background-color: #f0f0f0;
        }
        td:hover {
            cursor: pointer;
            background-color: #f4f4f4;
        }
        #openingHours {
            margin-top: 20px;
            display: none;
        }
    </style>
</head>
<body> <h2>Monthly Calendar: <?php echo "$month/$year"; ?></h2>

<script>
        function showOpeningHours(day) {
            var d_id = <?php echo json_encode($d_id); ?>;
            var date = new Date(<?php echo $year; ?>, <?php echo $month - 1; ?>, day + 1);
            var dateString = date.toISOString().split('T')[0];
            var formData = new FormData();
                formData.append('date', dateString);
                formData.append('d_id', d_id);

                fetch('/get_event.php', {
                method: 'POST',
                body: formData
                })
                .then(response => response.json()) // Parse the response as JSON
                .then(data => {
                    var hourArray = data; // Set the hourArray with the parsed data
                    // You may want to handle the data here, depending on your requirements
                    
                    var hoursContainer = document.getElementById('hoursList');
                hoursContainer.innerHTML = '';
                
                var openingHours = <?php echo json_encode($openingHours); ?>;
                openingHours.forEach(function(hour) {
                var reserved = false;
                if (hourArray['error'] !== '0 results'){
                    console.log(hourArray);
                    hourArray.forEach(function(reservedHour){
                if (hour==reservedHour['Hour']){
                    reserved = true;
                }
                })
                }
                if (reserved){
                var hourElement = document.createElement('div');
                hourElement.textContent = hour + ':00';
                
                hoursContainer.appendChild(hourElement);
                reserved = false;
            }
            });

            });
            

            document.getElementById('openingHours').style.display = 'block';
        }

        function registerSession(day, hour, d_id, p_id) {
            var date = new Date(<?php echo $year; ?>, <?php echo $month - 1; ?>, day, hour, 0, 0);
            var dateString = date.toISOString().split('T')[0];
            var hourString = hour < 10 ? '0' + hour : hour;

            if (confirm('Register for a session on ' + dateString + ' at ' + hourString + ':00?')) {
                // Send a POST request to the server
                var formData = new FormData();
                formData.append('registerSession', true);
                formData.append('date', dateString);
                formData.append('hour', hourString);
                formData.append('d_id', d_id);
                formData.append('p_id', p_id);

                fetch('/save_event.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.text())
                  .then(data => window.location.href = data);
            }
        }
    </script>

<!-- Navigation Buttons -->
<div>
    <a href="?month=<?php echo $prevMonthYear['month']; ?>&year=<?php echo $prevMonthYear['year']; ?>&d_id=<?php echo $d_id?>">Previous Month</a>
    <a href="?month=<?php echo $nextMonthYear['month']; ?>&year=<?php echo $nextMonthYear['year']; ?>&d_id=<?php echo $d_id?>">Next Month</a>
</div>

    <table>
        <tr>
            <th>Sun</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
        </tr>
        <tr>
        <?php
        // Create the calendar
        $date = new DateTime("$year-$month-01");
        while ($date->format('m') == $month) {
            // Add empty cells at the start of the month
            if ($date->format('j') == 1) {
                for ($i = 0; $i < $date->format('w'); $i++) {
                    echo "<td></td>";
                }
            }

            // Show the day
            echo '<td onclick="showOpeningHours(' . $date->format('j') . ')">' . $date->format('j') . '</td>';

            // Wrap to the next row every 7 days
            if ($date->format('w') == 6) {
                echo '</tr><tr>';
            }

            $date->add(new DateInterval('P1D')); // Add one day
        }
        ?>
        </tr>
    </table>

    <div id="openingHours">
        <strong>Scheduled appointments:</strong>
        <div id="hoursList"></div>
    </div>

    
</body>
</html>
