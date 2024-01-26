<?php

$conn = mysqli_connect("localhost", "root", "", "university");
if ($conn) {
    // echo "Database connected";
} else {
    echo "Database connection failed";
}
