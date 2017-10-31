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
	echo("err|");
	die("Connection failed: " . $conn->connect_error);
	return;
} 
mysqli_set_charset($conn,"utf8mb4");
$safecode = mysqli_real_escape_string($conn, strtoupper($_GET["code"]));
$sql = "SHOW TABLES LIKE 'c$safecode'";
$result = $conn->query($sql);
if ($result->num_rows > 0) { //code exists

if (isset($_GET["recipient"])){
$sql = "SELECT ID, unixtimestamp, sender, recipient, msg, vn, photo FROM c$safecode WHERE ID=(SELECT MAX(ID) FROM c$safecode)";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if (($row["recipient"] == "!str") || (strtoupper($_GET["recipient"]) == strtoupper($row["recipient"])))
{
echo htmlspecialchars("3|" . $row["ID"] . "|" . $row["unixtimestamp"] . "|" .$row["sender"] . "|" .$row["recipient"] . "|" .$row["msg"] . "|" .$row["vn"] . "|" .$row["photo"]);
}
else
{
echo "2|"; //wrong name
}
}
else
{
echo "1|"; //found
}

}
else
{
echo "0|"; //not found
}
$conn->close();
}
?>