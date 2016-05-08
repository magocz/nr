<?php
$servername = "188.40.44.195:3306";
$username = "neldam_nr";
$password = "Puma1234";
$dbname = "testDB";
$checkConnection = false;

$isLoginRequired = false;

// Create connection
$dbcon = mysqli_connect("188.40.44.195:3306", "neldam_nr","Puma1234");
mysqli_select_db($dbcon, "neldam_nr");

// Check connection
if($checkConnection){
    if ($dbcon->connect_error) {
        die("Connection failed: " . $dbcon->connect_error);
    }
    echo "Connected successfully";
}