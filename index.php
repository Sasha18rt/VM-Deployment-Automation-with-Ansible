<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> 
    <title>Sign Up</title>
</head>
<body>
    <div class="container">
        <?php
          if(!isset($_COOKIE['user']) || $_COOKIE['user'] == ''):
        ?>
       <div class="row"> 
        <div class="col-6 mx-auto">
            <h1>Login</h1>
            <form action="auth.php" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" name="login" id="login" placeholder="Input Login">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="pass" id="pass" placeholder="Input Password">
                </div>
                <button class="btn btn-success" type="submit">Sign in</button> 
                <p style="display: inline-block; margin-left: 10px;">You don't have an account? <a href="/role.php">Sign up here</a>.</p>
                
            </form>
        </div>
       </div>
       <?php else: ?>
        <p> Hello <?= $_COOKIE['user'] ?>. To make an appointment, press <a href="/doctorsList.php/">here</a>. To log out, press <a href="/exit.php/">here</a>.</p>
       <?php endif; ?>
    </div>
</body>
</html>