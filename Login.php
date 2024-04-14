<?php

include("connection.php");

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    
    $query = "SELECT * FROM login WHERE email='$email' AND password='$password'";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) == 1) {
        header('Location: Login.php');
        exit();
    } else if (mysqli_num_rows($result) == 0) {
        echo '<p class="text-danger">Incorrect email or password.</p>';
        exit();
    }
    
    mysqli_close($connection);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TOURS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="background-color: #F0EFE7;">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <div class="container-fluid">
                <a href="/ALVAREZ/LandingPage.php" class="navbar-brand">TOURS</a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mynavbar">
                </div>
            </div>
        </nav>
    </div>

    <div class="container-fluid" style="margin-top: 75px;">
        <div class="card-group card p-2 mt-2 mx-auto" style="width: 400px;">
            <div class="card-body">

                <form method="POST" action="Process.php">
                    <div class="row">
                        <div class="mb-3 col-sm-12">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-sm-12">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkRemember">
                                <label class="form-check-label" for="checkRemember">Remember me</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ms-auto">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold p-2" href="D:\Others\Programming\Xampp\htdocs\ALVAREZ\Account.php" value="Submit">Sign in</button>
                            <a href="#" class="text-center mt-1 mb-0" style="text-decoration: none;">Forgot password?</a>
                            <hr>
                            <a class="btn fw-bold text-white p-2" href="/ALVAREZ/Register.php" style="background-color: #42b72a;" role="button">Create new account</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br><br>
    <hr>
    <footer>
        <div class="container-fluid row m-2">

            <div class="col-md-6">
                <p>Copyright &copy; 2023 Kianna Dominique Alvarez</p>
            </div>
        </div>
    </footer>
</body>
</html>