<?php

// $dbname = "todo";
// $server = "localhost";
// $username = "root";
// $password = "";

// $conn = new mysqli($server, $username, $password);
// if ($conn->connect_error) {
// 	die("Error Connection" . $conn->connect_error);
// }

$dbname = "todo";
$server = getenv('DB_SERVER');
$port=getenv('DB_PORT');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

$conn  = mysqli_init();
if (!$conn) {
	die("mysqli_init failed!");
}
$conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
$conn->ssl_set(NULL, NULL, NULL, "ca.pem", NULL);

if (!$conn->real_connect($server, $username, $password, NULL, $port, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT)) {
	die("Error Connection" . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS todo;";

if (!$conn->query($sql)) {
	die("Error Database Make!" . $conn->connect_error);
}

$users_sql = "CREATE TABLE IF NOT EXISTS $dbname.users (
		userid int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		useremail varchar(255),
    username varchar(50),
		userpassword varchar(50)
	);";

$todos_sql = "CREATE TABLE IF NOT EXISTS $dbname.todos (
		todoid int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    userid int(6) UNSIGNED,
		todotitle varchar(50),
		tododescription varchar(255),
		isdone tinyint(1) DEFAULT 0,
    FOREIGN KEY (userid) REFERENCES $dbname.users(userid)
	);";

if (!$conn->query($users_sql)) {
	die("Error Creating Table: " . $conn->connect_error);
}
if (!$conn->query($todos_sql)) {
	die("Error Creating Table: " . $conn->connect_error);
}
