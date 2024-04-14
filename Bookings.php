<?php

include("connection.php");

session_start();

if (!isset($_SESSION["username"]) || !isset($_SESSION["email"])) {
    header("location: Login.php");
    exit();
}

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

$username = $_SESSION["username"];
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if (empty($email)) {
    $errorMessage = "Email parameter is missing.";
} else {
    $sql = "SELECT login.firstname, login.lastname, login.email, bookings.bookid, bookings.departure,
    bookings.destination, bookings.passengers, bookings.departdate, bookings.returndate, bookings.class 
    FROM bookings 
    INNER JOIN login ON bookings.loginid = login.loginid
    WHERE login.firstname = '$username'";
}

// Retrieve the user's bookings
$sql = "SELECT bookings.*, login.firstname, login.lastname, login.email FROM bookings 
        INNER JOIN login ON bookings.loginid = login.loginid 
        WHERE login.firstname = '$username'";
$result = mysqli_query($connection, $sql);

$departure = $destination = $people = $depart = $return = $type = $successMessage = $errorMessage = "";

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departure = mysqli_real_escape_string($connection, $_POST["departure"]);
    $destination = mysqli_real_escape_string($connection, $_POST["destination"]);
    $people = mysqli_real_escape_string($connection, $_POST["passengers"]);
    $depart = mysqli_real_escape_string($connection, $_POST["departdate"]);
    $return = mysqli_real_escape_string($connection, $_POST["returndate"]);
    $type = mysqli_real_escape_string($connection, $_POST["class"]);

    // Check if any field is empty
    if (empty($departure) || empty($destination) || empty($people) || empty($depart) || empty($return) || empty($type)) {
        $errorMessage = "All fields are required";
    } else {
        // Retrieve the loginid based on the username
        $username = $_SESSION['username'];
        $query = "SELECT loginid FROM login WHERE firstname = '$username'";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $loginid = $row['loginid'];

        // Insert the booking into the database
        $booknow = "INSERT INTO bookings (loginid, departure, destination, passengers, departdate, returndate, class) 
                    VALUES ('$loginid', '$departure', '$destination', '$people', '$depart', '$return', '$type')";
        if (mysqli_query($connection, $booknow)) {
            $_SESSION["success_message"] = "Booking added successfully";
            header("location: Bookings.php");
            exit();
        } else {
            $_SESSION["error_message"] = "Error: " . mysqli_error($connection);
        }
    }
}

// Handle delete button click
if(isset($_POST['delete'])) {
    $bookid = mysqli_real_escape_string($connection, $_POST['bookid']);

    // Delete booking from database
    $sql = "DELETE FROM bookings WHERE bookid='$bookid'";
    if(mysqli_query($connection, $sql)) {
        $_SESSION['success_message'] = "Booking deleted successfully";
        header("location: Bookings.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error deleting booking: " . mysqli_error($connection);
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

    <script>
		function hideForm() {
			var form = document.getElementById("myForm");
			form.style.display = "none";
		}

        function showForm() {
			var form = document.getElementById("myForm");
			form.style.display = "block";
		}
	</script>
</head>

<body class="bg-light">
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
                            <a href="/ALVAREZ/Account.php" class="nav-link align-middle px-0"><i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span></a>
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
    
    <div class="container-fluid" style="margin-top: 80px;">
        <div class="card mx-5">
            <div class="card-header">Book a Flight</div>
            <div class="card-body p-4">

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
                <div class="form-check pb-5 pt-3">
                    <div class="row ps-4 pb-4">
                        <div class="col-auto">
                            <input class="form-check-input" type="radio"name="hideForm" id="hideForm" onclick="hideForm()">
                            <label class="form-check-label" for="Trip" name="hideForm" id="hideForm" onclick="hideForm()">One Way</label>
                        </div>
                        <div class="col-auto ms-3">
                            <input class="form-check-input" type="radio" name="hideForm" id="showForm" onclick="showForm()">
                            <label class="form-check-label" for="Trip" name="hideForm" id="showForm" onclick="showForm()">Round Trip</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                                <label class="form-label" for="departure">From</label>
                                <input type="text" class="form-control" id="departure" name="departure" placeholder="Departure" value="<?php echo $departure; ?>">
                        </div>
                        <div class="col-sm-4" id="myForm">
                            <label class="form-label" for="destination">To</label>
                            <input type="text" class="form-control" id="destination" name="destination" placeholder="Destination" value="<?php echo $destination; ?>">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="passengers">No. of Passengers</label>
                            <input type="text" class="form-control" id="passengers" name="passengers" placeholder="No. of Passengers" value="<?php echo $people; ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-4">
                                <label class="form-label" for="departdate">Departure Date</label>
                                <input type="text" class="form-control" id="departdate" name="departdate" placeholder="YYYY-MM-DD" value="<?php echo $depart; ?>">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="returndate">Return Date</label>
                                <input type="text" class="form-control" id="returndate" name="returndate" placeholder="YYYY-MM-DD" value="<?php echo $return; ?>">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="class">Seat Class</label>
                            <select class="form-select" name="class" id="class" value="<?php echo $type; ?>">
                                <option selected>Please Choose Seat Class</option>
                                <option value="Economy">Economy</option>
                                <option value="Premium Economy">Premium Economy</option>
                                <option value="Business">Business</option>
                                <option value="First Class">First Class</option>
                              </select>
                        </div>
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
                    <div class="row mt-3">
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary fw-bold p-2 px-4 mt-3" href="/ALVAREZ/Bookings.php">Book Now</button>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
    <br>

    <div class="container-fluid mt-3">
        <div class="card mx-5" style="height: 500px;">
            <div class="card-header text-center fw-bold">BOOKING HISTORY</div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-sm-4">
                        <input type="text" class="form-control form-label col-sm-3" id="myInput" onkeyup="myFunction()" placeholder="Search">
                    </div>
                </div>
                <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-dark">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Booking ID</th>
                                <th>Departure</th>
                                <th>Destination</th>
                                <th>No. of Passengers</th>
                                <th>Departure Date</th>
                                <th>Return Date</th>
                                <th>Seat Class</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr><td>" . $row["firstname"] . "</td><td>" . $row["lastname"] . "</td><td>". $row["email"] . 
                                    "</td><td>" . $row["bookid"]. "</td><td>" . $row["departure"] . "</td><td>" . $row["destination"] .
                                    "</td><td>" . $row["passengers"] . "</td><td>" . $row["departdate"] . "</td><td>" . $row["returndate"] .
                                    "</td><td>" . $row["class"] . "</td>" . "<td>
                                    <form method='POST'>
                                        <input type='hidden' name='bookid' value='" . $row['bookid'] . "'>
                                        <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#exampleModal'>Delete</button>
                                        
                                        <div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                          <div class='modal-dialog'>
                                            <div class='modal-content'>
                                              <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalLabel'>Confirm Delete</h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                              </div>
                                              <div class='modal-body'>
                                                Are you sure you want to delete this booking?
                                              </div>
                                              <div class='modal-footer'>
                                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                                <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </form>
                                  </td>";
                                echo "</tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
    <hr>
    <footer>
        <div class="container-fluid row m-2">
            <div class="col-md-6">
                <p>Copyright &copy; 2023 Kianna Dominique Alvarez</p>
            </div>
        </div>
    </footer>

    <script>
        function myFunction() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those that don't match the search query
            for (i = 0; i < tr.length; i++) {
                var display = false;
                // Loop through all table columns, and check if any column matches the search query
                for (j = 0; j < tr[i].getElementsByTagName("td").length; j++) {
                td = tr[i].getElementsByTagName("td")[j];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    display = true;
                    break;
                    }
                }
                }
                // Set the row display style based on whether any column matches the search query
                if (display) {
                tr[i].style.display = "";
                } else {
                tr[i].style.display = "none";
                }
            }

            // If the search field is empty, show all rows
            if (filter.length === 0) {
                for (i = 0; i < tr.length; i++) {
                tr[i].style.display = "";
                }
            }
        }


    </script>
</body>
</html>