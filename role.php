<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> 
   
    <title>Registration Selection</title>
</head>

<body>
    <div class="container registration-container">
        <h1 class="mb-4">Registration Selection</h1>
        <div class="d-flex flex-column">
            <button class="btn btn-primary btn-lg mb-3"  onclick="redirectToPatientRegistration()">Patient</button>
            <button class="btn btn-success btn-lg"  onclick="redirectToDoctorRegistration()">Doctor</button>
        </div>
    </div>

    <script>
        function redirectToPatientRegistration() {
            window.location.href = 'patient.php';
        }

        function redirectToDoctorRegistration() {
            window.location.href = 'doctor.php';
        }
    </script>
</body>

</html>



