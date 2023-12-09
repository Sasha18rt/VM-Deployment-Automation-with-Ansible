<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> 
    <title>Simple Website</title>
</head>
<body>
    
    <div class="container">
        <?php if (!isset($_COOKIE['user']) || $_COOKIE['user'] == ''):?>
            <div class="row d-flex align-items-center justify-content-center" style="min-height: 100vh;"> 
                <div class="col-10 mx-auto registration-container">
                    <h1>Login</h1>
                    <form action="auth.php" method="post">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="login" id="login" placeholder="Input Login">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" name="pass" id="pass" placeholder="Input Password">
                        </div>
                        <div class="mb-3">
                            <label for="role">Select Role:</label>
                            <select class="form-control" name="role" id="role">
                                <option value="patient">Patient</option>
                                <option value="doctor">Doctor</option>
                            </select>
                        </div>
                        <button class="btn btn-success" type="submit">Sign in</button> 
                        <p class="text-muted">You don't have an account? <a href="/web/role.php">Sign up here</a>.</p>
                    </form>
                </div>
            </div>
        <?php elseif(isset($_COOKIE['p_id'])): ?>
            <div class="container-fluid">
            <header>
                <h1>HOSPITAL</h1>
            </header>

            <nav>
                <a href="/web/MyAccount.php">My Account</a>
                <a href="/web/doctorsList.php">Make an appointment</a>
                <a href="/web/exit.php">Logout</a>
              
            </nav>

            <section id="my-account">
           
            <h2><?php echo ucfirst($_COOKIE['role']); ?> Account</h2>
             <p>Hello <?php echo $_COOKIE['user']; ?>!</p>
            </section>
            


            <section id="schedule-meeting">
                <h2>About us</h2>
                <p>some info.</p>
                
            </section>
        </div>
        <?php elseif(isset($_COOKIE['d_id'])): ?>
            <div class="container-fluid">
            <header>
                <h1>HOSPITAL</h1>
            </header>

            <nav>
                <a href="/web/MyAccount.php">My Account</a>
                <a href="/web/doctorSchedule.php">See your schedule</a>
                <a href="/web/exit.php">Logout</a>
              
            </nav>

            <section id="my-account">
           
            <h2><?php echo ucfirst($_COOKIE['role']); ?> Account</h2>
             <p>Hello <?php echo $_COOKIE['user']; ?>!</p>
            </section>
            


            <section id="schedule-meeting">
                <h2>About us</h2>
                <p>some info.</p>
                
            </section>
        </div>
        <?php endif; ?>
        </div>
</body>


</html>
