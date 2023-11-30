<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> 
    <title>My Account</title>
</head>
<body>
    <div class="container">
        <?php 
        // Підключення до бази даних
        $mysqli = new mysqli('localhost', 'root', '', 'remote-med');

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        if (!isset($_COOKIE['user']) || $_COOKIE['user'] == ''): ?>
            <div class="row d-flex align-items-center justify-content-center" style="min-height: 100vh;"> 
                <div class="col-10 mx-auto registration-container">
                    <h1>Login</h1>
                    <form action="auth.php" method="post">
                        
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="container-fluid">
                <header>
                    <h1>HOSPITAL</h1>
                </header>

                <nav>
                    <a href="/MyAccount.php">My Account</a>
                    <?php if (isset($_COOKIE['d_id'])): ?>
                        <a href="/doctorSchedule.php">See your schedule</a>
                    <?php elseif (isset($_COOKIE['p_id'])): ?>
                        <a href="/doctorsList.php">Make an appointment</a>
                    <?php endif; ?>
                    <a href="/exit.php">Logout</a>
                </nav>

                <section id="my-account">
                    <h2><?php echo ucfirst($_COOKIE['role']); ?> Account</h2>
                    <p>Hello <?php echo $_COOKIE['user']; ?>!</p>
                </section>

                <section id="schedule-meeting">
                    <?php
                    $patient_id = isset($_COOKIE['p_id']) ? $_COOKIE['p_id'] : null; // Assuming you store patient ID in a cookie
                    
                    if (!is_null($patient_id)) {
                        // Verify if the patient card exists
                        $sql_check_card = "SELECT * FROM patient_card WHERE p_id = ?";
                        $stmt_check_card = $mysqli->prepare($sql_check_card);
                        $stmt_check_card->bind_param("i", $patient_id);
                        $stmt_check_card->execute();
                        $result_check_card = $stmt_check_card->get_result();
                        $patient_card_exists = $result_check_card->num_rows > 0;
                        $stmt_check_card->close();
                        
                        if ($patient_card_exists) {
                            // Fetch patient card information based on patient ID
                            $sql_get_card = "SELECT * FROM patient_card WHERE p_id = ?";
                            $stmt_get_card = $mysqli->prepare($sql_get_card);
                            $stmt_get_card->bind_param("i", $patient_id);
                            $stmt_get_card->execute();
                            $result_get_card = $stmt_get_card->get_result();
                            $patient_card = $result_get_card->fetch_assoc();
                            $stmt_get_card->close();
                            ?>
                            <h2>Patient Card</h2>
                            <p><strong>Medical History:</strong> <?php echo nl2br($patient_card['Medical_history']); ?></p>
                            <p><strong>Current Medications:</strong> <?php echo nl2br($patient_card['Current_med']); ?></p>
                            <p><strong>Allergies:</strong> <?php echo nl2br($patient_card['Allergies']); ?></p>
                            <?php
                        } else {
                            echo '<div class="alert " role="alert">
                                <a href="/fill_card.php" class="btn btn-primary">Fill Patient Card</a>
                              </div>';
                        }
                    }
                    ?>
                </section>
            </div>
        <?php endif; ?>

        <?php
        $mysqli->close();
        ?>
    </div>
</body>
</html>
