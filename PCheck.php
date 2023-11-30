<?php
$login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$surname = filter_var(trim($_POST['surname']), FILTER_SANITIZE_STRING);
$dob = filter_var(trim($_POST['dob']), FILTER_SANITIZE_STRING);
$gender = filter_var(trim($_POST['gender']), FILTER_SANITIZE_STRING);
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
$pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);

if (mb_strlen($login) < 2 || mb_strlen($login) > 20) {
    echo "Wrong length of login";
    exit();
} elseif (mb_strlen($name) < 2 || mb_strlen($name) > 20) {
    echo "Wrong length of name";
    exit();
} elseif (mb_strlen($surname) < 5 || mb_strlen($surname) > 20) {
    echo "Wrong length of surname";
    exit();
} elseif (mb_strlen($dob) === 0) {
    echo "Date of Birth is required";
    exit();
} elseif (mb_strlen($gender) === 0) {
    echo "Gender is required";
    exit();
} elseif (mb_strlen($email) === 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format";
    exit();
} elseif (mb_strlen($phone) === 0) {
    echo "Phone number is required";
    exit();
} elseif (mb_strlen($pass) < 3 || mb_strlen($pass) > 20) {
    echo "Wrong length of password (3 - 20 characters)";
    exit();
}

$pass = md5($pass . "cfifhjnf'yrj123");

$mysqli = new mysqli('localhost', 'root', '', 'remote-med');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the login already exists
$checkLoginQuery = "SELECT * FROM `patient` WHERE `Username` = ?";
$checkLoginStmt = $mysqli->prepare($checkLoginQuery);
$checkLoginStmt->bind_param("s", $login);
$checkLoginStmt->execute();
$checkLoginResult = $checkLoginStmt->get_result();



// Use prepared statements to prevent SQL injection
$sql = "INSERT INTO `patient` (`Username`, `Password`, `Name`, `Surname`, `Date_of_birth`, `Gender`, `Email`, `Phone_number`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

// Set parameters
$stmt->bind_param("ssssssss", $login, $pass, $name, $surname, $dob, $gender, $email, $phone);

if ($stmt->execute()) {
    echo "Data successfully inserted into the table.";
} else {
    echo "Error: " . $stmt->error;
}
if ($checkLoginResult->num_rows > 0) {
    echo "Login already exists. Please choose another login.";
    exit();
}
$patient_id = $mysqli->insert_id; // Get the ID of the newly inserted patient
$sql = "INSERT INTO patient_card (p_id, medical_history, current_med, allergies) VALUES (?, '', '', '')";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$stmt->close();
$mysqli->close();
header('Location: /');
?>