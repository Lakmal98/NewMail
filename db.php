<?php

$server = "localhost";
$user = "root";
$password = "";
$database = "newmail";

$dbConn = new mysqli($server, $user, $password,$database);

// Check connection
if ($dbConn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}
