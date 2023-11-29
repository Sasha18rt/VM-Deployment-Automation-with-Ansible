<?php
$login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
$pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);

$pass = md5($pass . "cfifhjnf'yrj123");
$mysqli = new mysqli('localhost', 'root', '', 'remote-med');

// Assuming you meant to use $result instead of $user here
$result = $mysqli->query("SELECT * FROM `patient` WHERE `Username` = '$login' AND `Password`= '$pass'");

// Check if there are no results for patients, then check for doctors
if ($result === null || $result->num_rows == 0) {
    $result = $mysqli->query("SELECT * FROM `doctor` WHERE `Login` = '$login' AND `Password`= '$pass'");
}

$user = $result->fetch_assoc();
if ($user === null || count($user) == 0) {
    echo "wrong login or password";
    exit();
}

setcookie('user', $user['Name'], time() + 3600 * 24, "/");
setcookie('p_id', $user['p_id'], time() + 3600 * 24, "/");

$mysqli->close();
header('Location: /');
?>
