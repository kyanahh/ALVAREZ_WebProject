<?php

include("connection.php");

$firstname  = $lastname = $email = $password  = $confirmpass = $errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpass = $_POST["confirmpass"];

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirmpass)) {
        $errorMessage = "All fields are required";
    } elseif ($password !== $confirmpass) {
        $errorMessage = "Passwords do not match";
    } else {
        $result = $connection->query("INSERT INTO login (firstname, lastname, email, password, confirmpass) VALUES('$firstname', '$lastname', '$email', '$password', '$confirmpass')");

        if (!$result) {
            $errorMessage = "Invalid query " . $connection->error;
        } else {
            $successMessage = "Client added successfully";
            header("location: /ALVAREZ/Login.php");
            exit;
        }
    }
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

    <div class="container-fluid" style="margin-top: 100px;">
        
        <div class="card-group card p-2 mx-auto" style="width: 600px;">
            <div class="card-body">
            
                <?php
                if (!empty($errorMessage)) {
                    echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>$errorMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                    ";
                }
                ?>
                    <form method="POST" action="<?php htmlspecialchars("SELF_PHP"); ?>">
                    <div class="row">
                        <div class="col-sm-6 mt-2">
                            <label class="visually-hidden" for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname" placeholder="First Name" name="firstname" value="<?php echo $firstname; ?>" required>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <label class="visually-hidden" for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" placeholder="Last Name" name="lastname" value="<?php echo $lastname; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 mt-2">
                            <label class="visually-hidden" for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?php echo $email; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 mt-2">
                            <label class="visually-hidden" for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Password" name="password" value="<?php echo $password; ?>" required>
                            <?php if (isset($error)) { ?>
                            <p class="text-danger"><?php echo $error; ?></p>
                            <?php } ?>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <label class="visually-hidden" for="confirmpass">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmpass" placeholder="Confirm Password" name="confirmpass" value="<?php echo $confirmpass; ?>" required>
                        </div>
                    </div>
                        <div class="form-text">
                            Must be 8-20 characters long, contain letters, numbers and special characters, but must not contain spaces.
                        </div>
                        
                        <?php

                        if (!empty($successMessage)) {
                            echo "
                            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                <strong>$successMessage</strong>
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>
                            ";
                        }

                        ?>

                        <div class="col-auto mt-4">
                            <button type="submit" class="btn btn-primary p-2" href="/ALVAREZ/Login.php" value="Submit">Create Account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br><br><br><br><br><br>
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

