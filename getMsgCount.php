<?php

header('Content-Type: text/html; charset=utf-8');


run();
function run()
{
$servername = $_ENV["DB_SERVERNAME"];
$username = $_ENV["DB_USERNAME"];
$password = $_ENV["DB_PASSWORD"];
$dbname = $_ENV["DB_DBNAME"];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	echo("[err]");
	die("Connection failed: " . $conn->connect_error);
	return;
} 
mysqli_set_charset($conn,"utf8mb4");
$result = $conn->query("SELECT value FROM vars WHERE data='msg_count';");
if (!$result) {
    //echo $sql;
    //echo $conn->error;
    echo "[err]";
}
$row = $result->fetch_assoc();

echo $row["value"];

$conn->close();
}
?>
