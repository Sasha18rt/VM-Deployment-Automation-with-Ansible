<?php
$login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
$pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);
$role = filter_var(trim($_POST['role']), FILTER_SANITIZE_STRING);

$pass = md5($pass . "cfifhjnf'yrj123");
$mysqli = new mysqli('localhost', 'root', '', 'remote-med');

$tableName = ($role === 'doctor') ? 'doctor' : 'patient';

// Use a generic placeholder for the username column
$usernameColumn = ($role === 'doctor') ? 'Login' : 'Username';

$result = $mysqli->query("SELECT * FROM `$tableName` WHERE `$usernameColumn` = '$login' AND `Password`= '$pass'");

$user = $result->fetch_assoc();
if ($user === null || count($user) == 0) {
    echo "Wrong login or password";
    exit();
}

setcookie('user', $user['Name'], time() + 3600 * 24, "/");
setcookie('p_id', $user['p_id'], time() + 3600 * 24, "/");
setcookie('role', $_POST['role'], time() + 3600 * 24, "/");
setcookie('d_id', $user['d_id'], time() + 3600 * 24, "/");

$mysqli->close();
header('Location: /');
?>
