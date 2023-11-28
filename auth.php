<?php
 $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
 $pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);
 
 $pass = md5($pass."cfifhjnf'yrj123");
$mysqli = new mysqli('localhost', 'root', '', 'register-bd');

$result = $mysqli->query("SELECT * FROM `users` WHERE
 `login` = '$login' AND `Password`= '$pass'");
$user = $result->fetch_assoc();
if ($user === null || count($user) == 0){
    echo "wrong login or password";
    exit();
}

setcookie('user', $user['Name'], time() + 3600 * 24, "/");


$mysqli->close();
header('Location: /virt/')

?>