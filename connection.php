<?php

$connection = mysqli_connect("localhost", "root", "", "tours");
    if(mysqli_connect_errno()){
        echo "Failed to connect to MySQL" . mysqli_connect_errno();
    }

?>