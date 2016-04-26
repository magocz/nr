<?php
$servername = "188.40.44.195:3306";
$username = "neldam_nr";
$password = "Puma1234";
$dbname = "testDB";
$checkConnection = true;

// Create connection
$dbconn = new mysqli("188.40.44.195:3306", "neldam_nr","Puma1234","nazwa");

// Check connection
if($checkConnection){
    if ($dbconn->connect_error) {
        die("Connection failed: " . $dbconn->connect_error);
    }
    echo "Connected successfully";
}