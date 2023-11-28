<?php
$login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);

if (mb_strlen($login) < 5 || mb_strlen($login) > 20) {
    echo "Wrong length of login";
    exit();
} elseif (mb_strlen($name) < 5 || mb_strlen($name) > 20) {
    echo "Wrong length of name";
    exit();
} elseif (mb_strlen($pass) < 3 || mb_strlen($pass) > 20) {
    echo "Wrong length of password (3 - 20 characters)";
    exit();
}
$pass = md5($pass."cfifhjnf'yrj123");
$mysqli = new mysqli('localhost', 'root', '', 'register-bd');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "INSERT INTO `users` (`login`, `Password`, `name`) VALUES ('$login', '$pass', '$name')";

if ($mysqli->query($sql)) {
    echo "Data successfully inserted into the table.";
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
header('Location: /virt/')
?>

