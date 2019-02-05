<?php
	$servername = "localhost";
	$username = "riddha";
	$password = "rms_my_first_project000";
	$dbname = "rms";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
?>