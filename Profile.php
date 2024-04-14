<?php

session_start();

if(isset($_SESSION["logged_in"])){
    if(isset($_SESSION["username"]) && ($_SESSION["lastname"] && ($_SESSION["email"]))){
        $textaccount = $_SESSION["username"];
        $lastname = $_SESSION["lastname"];
        $email = $_SESSION["email"];
    }else{
        $textaccount = "Account";
    }
}else{
    $textaccount = "Account";
}

include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION["email"];
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $result = $connection->query("SELECT password FROM login WHERE email = '$email'");
    $record = $result->fetch_assoc();
    $stored_password = $record["password"];
    if ($old_password == $stored_password) {
      $connection->query("UPDATE login SET password = '$new_password', confirmpass = '$new_password' WHERE email = '$email'");
      $_SESSION["success_message"] = "Password changed successfully";
    } else {
      $_SESSION["error_message"] = "Old password does not match";
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

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <div class="container-fluid">
                <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><i class="bi bi-list"></i></button>
                <div class="offcanvas offcanvas-start bg-dark text-white" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title ms-3 mt-3" id="offcanvasWithBothOptionsLabel">TOURS</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start ms-3" id="menu">
                        <li class="nav-item">
                            <a href="/ALVAREZ/Account.php" class="nav-link align-middle px-0">
                                <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="/ALVAREZ/About.php" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-info-circle"></i> <span class="ms-1 d-none d-sm-inline">About</span> </a>
                        </li>
                        <li>
                            <a href="/ALVAREZ/Contact.php" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-telephone"></i> <span class="ms-1 d-none d-sm-inline">Contact Us</span> </a>
                        </li>
                    </ul>
                </div>
                </div>

                <a href="/ALVAREZ/Account.php" class="navbar-brand">TOURS</a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mynavbar">
                    <div class="navbar-nav ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-2"></i><?php  echo $textaccount; ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="/ALVAREZ/Profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="/ALVAREZ/Bookings.php">Bookings</a></li>
                            <li><a class="dropdown-item" href="/ALVAREZ/Logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <br>
    <div class="card bg-light mb-3 mx-auto" style="max-width: 40rem; margin-top: 80px;">
        <div class="card-header fw-bold">My Profile</div>
        <div class="card-body p-4">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for="firstname">First Name</label>
                        <input type="text" class="form-control" id="firstname" placeholder="First Name" value="<?php echo $textaccount; ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for="lastname">Last Name</label>
                        <input type="text" class="form-control" id="lastname" placeholder="Last Name" value="<?php echo $lastname; ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for="email">Email</label>
                        <input type="text" class="form-control" id="email" placeholder="Email" value="<?php echo $email; ?>" readonly>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="old_password">Old Password</label>
                        <input type="password" class="form-control" name="old_password" placeholder="Old Password">
                        <?php
                        if (isset($_SESSION["success_message"])) {
                            echo "<label>" . $_SESSION["success_message"] . "</label>";
                            unset($_SESSION["success_message"]);
                        } elseif (isset($_SESSION["error_message"])) {
                            echo "<label>" . $_SESSION["error_message"] . "</label>";
                            unset($_SESSION["error_message"]);
                        }
                        ?>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="new_password">New Password</label>
                        <input type="password" class="form-control" name="new_password" placeholder="New Password">
                    </div>
                </div>
                <button class="btn fw-bold text-white ps-3" href="Profile.php" style="background-color: #42b72a;" value="Submit">Save Changes</button>
                </div>
            </form>
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