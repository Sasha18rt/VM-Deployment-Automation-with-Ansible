<?php
setcookie( 'user',$user['name'], time() - 3600 * 24, "/" );
setcookie('p_id', $user['p_id'], time() - 3600 * 24, "/");
setcookie('d_id', $user['d_id'], time() - 3600 * 24, "/");
setcookie('role', $_POST['role'], time() - 3600 * 24, "/");
header('Location: /')

?>