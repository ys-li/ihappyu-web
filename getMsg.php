<?php


run();
function run(){
$servername = $_ENV["DB_SERVERNAME"];
$username = $_ENV["DB_USERNAME"];
$password = $_ENV["DB_PASSWORD"];
$dbname = $_ENV["DB_DBNAME"];
// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
     echo "0{e}";
     die("Connection failed: " . $conn->connect_error);
} 
mysqli_set_charset($conn,"utf8mb4");
$safecode = mysqli_real_escape_string($conn, strtoupper($_GET["code"]));
$msgid = $_GET["id"];
$err = true;
if (is_numeric($msgid) && $msgid > 0)
{
$maxrow = ($conn->query("SELECT MAX(ID) FROM c$safecode")->fetch_assoc()["MAX(ID)"]);
if ($msgid < $maxrow + 1)
{
$sql = "SELECT ID, unixtimestamp, sender, recipient, msg FROM c$safecode WHERE ID=$msgid";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
     // output data of each row
     echo "1{e}";

     while($row = $result->fetch_assoc()) {
        $sender = $row["sender"];
        $recipient = $row["recipient"];
        $msg = $row["msg"];
        if ($row["ID"] == 1)
        {
            
            if ($sender == "") $sender = "iHappyU Team";
            if ($msg == "") {
                if (isset($_GET["en"]))
                {
                $msg = "Thank you so much for joining our event! We really need your further support by passing this card to a another person after you have added a message to the chain so that your recipient can see what you wrote. \n\nEvery iHappyu card comes with a question at the top, and we hope that this can help you choose the recipient. Whatever you write, it is important that you eventually pass the card to that person so that you can “Pay it Forward”.\n\nWe really hope that you can use a few minutes of your time to make all of these sustainable. \n\n All messages will accumulate and eventually you can see how your act of paying it forward allowed tens of others to do the same - and even bringing a little bit of change to their lives perhaps.";
                }
                else{
                $msg = "如果你睇到呢個 message，咁即係話你已經行左第一步去了解下咩係 iHappyU。我地好感激你嘅主動，亦好需要你嘅幫手，將呢張卡傳俾下一個，等快樂可以傳開去。\n\n
每張 iHappyU card 上面都有條問題，而家你就可以透過條問題，寫啲鼓勵嘅嘢俾你嘅朋友，或者直接同佢講你有幾珍惜佢。無論你寫咩，最緊要係傳張卡俾嗰個人，等佢可以睇下你寫左啲咩。\n\n
所以真係好希望你可以用搭程車、等個 friend 嘅時間打少少野，傳張卡俾朋友，再叫佢繼續傳出去，咁先可以令每張卡一路延續落去。\n\n
我地會將一張卡入面嘅所有 messages 累積，等你可以見到自己嘅一小步係點樣為朋友，甚至陌生人帶來一啲快樂， 一啲改變。\n\n
希望我地都做到 iHappyU，令你願意傳張卡落去啦！:)";
            }}
            if ($recipient == "") $recipient = "First Card Holder";

            
        }
        
        echo htmlspecialchars($row["ID"] . "|" . $row["unixtimestamp"] . "|" . $sender . "|" . $recipient . "|" .$msg ."{e}");
		
     }
     $err = false;
}}}
if ($err) echo "0{e}";

$conn->close();
}

?>