<?php
$host = "localhost";
$user = "root";
$pass = "your_root_password";
$dbname = "math_gineer_db";

$conn = new mysqli($host, $user, $pass, $dbname);

//  if ($conn->connect_error) {
//     die("<div class='message error'>Check Database Connection: " . $conn->connect_error . "</div>");
//  } else {
//      echo "<div class='message success'>You are now connected to Math Gineer!</div>";
//  }

