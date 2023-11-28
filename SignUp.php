<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Your Page Title</title>
</head>

<body class="text-center">
    <div class="col-6 mx-auto">
        <h1>Registration</h1>
        <form action="check.php" method="post">
            <div class="mb-3">
                <input type="text" class="form-control" name="login" id="login" placeholder="Input Login">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="name" id="name" placeholder="Input Name">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="pass" id="pass" placeholder="Input Password">
            </div>
            <button class="btn btn-success" type="submit">Sign Up</button>
        </form>
    </div>
</body>

</html>
