<?php
$conn = null;

header('Content-Type: text/html; charset=utf-8');


run();
function run()
{
$servername = $_ENV["DB_SERVERNAME"];
$username = $_ENV["DB_USERNAME"];
$password = $_ENV["DB_PASSWORD"];
$dbname = $_ENV["DB_DBNAME"];
// Preliminary checking

// Create connection
global $conn;
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection

if (isset($_GET["inccount"]))
{
    if(increaseCount()) echo "OK";
    $conn->close();
    die();
}

if ($conn->connect_error) {
	echo "0";
    die("Connection failed: " . $conn->connect_error);
} 
mysqli_set_charset($conn,"utf8mb4");
//$lastrecipient = $_POST["lastrecipient"];
$safecode = mysqli_real_escape_string($conn, strtoupper($_POST["code"]));
$safesender = mysqli_real_escape_string($conn, $_POST["sender"]);
$saferecipient = mysqli_real_escape_string($conn, $_POST["recipient"]);
$safemsg = mysqli_real_escape_string($conn, $_POST["msg"]);
//$safevn = mysqli_real_escape_string($conn, $_POST["vn"]);
//$safephoto = mysqli_real_escape_string($conn, $_POST["photo"]);
//if (strlen(utf8_decode($safemsg)) > 1000){echo "0"; return;}
//$safeanon = mysqli_real_escape_string($conn, $_POST["anon"]);
$safecode = str_replace("|","",$safecode);
$safesender = str_replace("|","",$safesender);
$saferecipient = str_replace("|","",$saferecipient);
$safemsg = str_replace("|","",$safemsg);
//$safevn = str_replace("|","",$safevn);
//$safephoto = str_replace("|","",$safephoto);
//$safecode = str_replace("|","",$safecode);
//$safesender = str_replace("<?php","",$safesender);
//$saferecipient = str_replace("<?php","",$saferecipient);
//$safemsg = str_replace("<?php","",$safemsg);
//$safevn = str_replace("<?php","",$safevn);
//$safephoto = str_replace("<?php","",$safephoto);
$sql = "SELECT ID, recipient FROM c$safecode WHERE ID=(SELECT MAX(ID) FROM c$safecode)";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
        /*if ($_POST["vn"] == 1) 
        {
            $safevn = mysqli_real_escape_string($conn, "$safecode-" + $row["ID"] + "-" + generateRandomString());
        }
        else 
        {
            $safevn = "na";
        }
        
        if ($_POST["photo"] == 1)
        {
            $safephoto = mysqli_real_escape_string($conn, "$safecode-" + $row["ID"] + "-" + generateRandomString());
        }
        else 
        {
            $safevn = "na";
        }*/
        $prevrow = ($conn->query("SELECT COUNT(ID) FROM c$safecode")->fetch_assoc()["COUNT(ID)"]);
        $safetime = time();
            //$safevnblob = mysqli_real_escape_string($conn, $_POST["vnblob"]);
            //$safevnblob = str_replace("<?php","",$safevnblob);
            $sql = "INSERT INTO c$safecode (unixtimestamp, sender, recipient, msg) VALUES ($safetime,'$safesender','$saferecipient','$safemsg');";
        
        
        if ($conn->query($sql) == TRUE) 
        {
                echo "1";
                increaseCount($conn);
            
        } else {
            echo "0";
        }
    

$conn->close();
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function increaseCount()
{
    global $conn;
    $conn->select_db("ihappyun_misc");
    if (($conn->query("UPDATE vars SET value=value+1 WHERE data='msg_count';")))
    {
        return true;
    }
    
    return false;
}
