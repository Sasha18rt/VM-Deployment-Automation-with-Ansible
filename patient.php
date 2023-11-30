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
        <div class="row"> 
            <div class="col-6 mx-auto">
                <h1>Registration</h1>
                <form action="Pcheck.php" method="post">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="login" id="login" placeholder="Input Username">
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Input Name">
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="surname" id="surname" placeholder="Input Surname">
                    </div>
                    <div class="mb-3">
                        <!-- Use type="date" for the date input -->
                        <input type="date" class="form-control" name="dob" id="dob" placeholder="Input Date of Birth">
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="gender" id="gender" aria-label="Select Gender">
                            <option value="" selected>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Input Email">
                    </div>
                    <div class="mb-3">
                        <input type="tel" class="form-control" name="phone" id="phone" placeholder="Input Phone Number">
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="pass" id="pass" placeholder="Input Password">
                    </div>
                    <button class="btn btn-success" type="submit">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
