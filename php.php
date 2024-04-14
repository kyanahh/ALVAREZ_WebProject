<?php
include("connection.php");

if (!isset($_GET['studid'])) {
    echo "Invalid request";
    exit();
}

$studid = $_GET['studid'];

$fname = $lname = $image = $cno = $address = $email = $gender = $course = "";
$image = "default.jpg";

$sql = "SELECT * FROM tbl_contacts WHERE studid = '$studid'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $fname = $row["fname"];
    $lname = $row["lname"];
    $image = $row["image"];
    $cno = $row["cno"];
    $address = $row["address"];
    $gender = $row["gender"];
    $course = $row["course"];
} else {
    echo "No record found";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    $fname = mysqli_real_escape_string($conn, ucwords($_POST["first"]));
    $lname = mysqli_real_escape_string($conn, ucwords($_POST["last"]));
    $cno = mysqli_real_escape_string($conn, $_POST["contact"]);
    $address = mysqli_real_escape_string($conn, ucwords($_POST["address"]));
    $gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $course = mysqli_real_escape_string($conn, $_POST["course"]);

    // Check if an image was uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["name"]) {
        // Get the uploaded file name and extension
        $filename = basename($_FILES["image"]["name"]);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");

        // Check if the uploaded file is a valid image file
        if (!in_array($extension, $allowed_extensions)) {
            echo "Invalid image file. Allowed extensions: " . implode(", ", $allowed_extensions);
            exit();
        }

        // Generate a unique filename for the uploaded image
        $new_filename = $studid . "." . $extension;

        // Move the uploaded image to the uploads directory
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $new_filename)) {
            echo "Error uploading image file";
            exit();
        }

        // Delete the previous image file
        if ($image != "default.jpg" && file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }

        // Set the image filename to the new filename
        $image = "uploads/" . $new_filename;
    }

    // Prepare SQL statement
    $sql = "UPDATE tbl_contacts SET fname = '$fname', lname = '$lname', cno = '$cno', address = '$address', gender = '$gender', course = '$course', image = '$image' WHERE studid = '$studid'";

    // Execute SQL statement
    if (mysqli_query($conn, $sql)) {
        // Redirect to view page with success message
        header("Location: ContactList.php?studid=$studid&message=success");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Last Name:</label><br>
        <input type="text" name="last" value="<?php echo $lname; ?>"><br><br>

        <label>First Name:</label><br>
        <input type="text" name="first" value="<?php echo $fname; ?>"><br><br>

        <label>Address:</label><br>
        <textarea name="address"><?php echo $address; ?></textarea><br><br>

        <label>Contact Number:</label><br>
        <input type="text" name="contact" value="<?php echo $cno; ?>"><br><br>

        <label>Email Address:</label><br>
        <input type="email" name="email" value="<?php echo $email; ?>"><br><br>

        <label>Gender:</label><br>
        <input type="radio" name="gender" value="Male" <?php if($gender=="Male") echo "checked"; ?>>Male
        <input type="radio" name="gender" value="Female" <?php if($gender=="Female") echo "checked"; ?>>Female<br><br>
        <label>Course:</label><br>
        <select name="course">
            <option value="">Select Course</option>
            <option value="BSIT" <?php if($course=="BSIT") echo "selected"; ?>>BSIT</option>
            <option value="BSCS" <?php if($course=="BSCS") echo "selected"; ?>>BSCS</option>
            <option value="ACT" <?php if($course=="ACT") echo "selected"; ?>>ACT</option>
        </select><br><br>
        <label>Profile Picture:</label><br>
        <img id="preview" src="<?php echo $image; ?>" width="100" height="100"><br>
        <input type="file" name="image" onchange="previewImage(event)"><br><br>
        <input type="submit" value="Update">
        <input type="button" value="Cancel" onclick="location.href='profile.php?studid=<?php echo $studid; ?>'">
        
    </form>


    <script>
		function previewImage(event){
			var reader = new FileReader();
			reader.onload = function(){
				var output = document.getElementById('preview');
				output.src = reader.result;
			}
			reader.readAsDataURL(event.target.files[0]);
		}
	</script>
</body>
</html>
