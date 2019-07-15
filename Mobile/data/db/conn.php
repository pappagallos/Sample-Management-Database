<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smdb";

//Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->query("set session character_set_connection=utf8;");
$conn->query("set session character_set_results=utf8;");
$conn->query("set session character_set_client=utf8;");

//Check connection
if($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}
?>