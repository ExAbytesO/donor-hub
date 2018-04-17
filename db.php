<?php
/* Database connection settings */
define("DB_HOST", "host_address");
define("DB_USER", "user");
define("DB_PASS", "password");
define("DB", "accounts"); //database
$conn= new mysqli(DB_HOST,DB_USER,DB_PASS,DB);

//Check connection
if($conn -> connect_errno){
	printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}
?>