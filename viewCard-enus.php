<!--
cCopyright 2016 Y.S. Li
Dependencies: jquery.js, recorder.js, bootstrap, animate.css
iHappyU 
-->
<?php

$servername = $_ENV["DB_SERVERNAME"];
$username = $_ENV["DB_USERNAME"];
$password = $_ENV["DB_PASSWORD"];
$dbname = $_ENV["DB_DBNAME"];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
    $safecode = mysqli_real_escape_string($conn, strtoupper($_GET["code"]));
  mysqli_set_charset($conn,"utf8mb4");
  
  $sql="SELECT unixtimestamp FROM c$safecode WHERE ID=0;";
  $result = $conn->query($sql);
if ($result && mysqli_num_rows($result) > 0)
{
  $questionno = $result->fetch_assoc()["unixtimestamp"];

  $sql="SELECT ID, sender FROM c$safecode WHERE ID=(SELECT MAX(ID) FROM c$safecode)";

  $result = $conn->query($sql);
  $maxmsg=$result->fetch_assoc()["ID"];

  $conn->select_db("ihappyun_misc");
  $result = $conn->query("SELECT data FROM questions WHERE ID=$questionno;");
if (!$result) {
     echo "<script>window.location = \"\";</script>";
die();
}
$row = $result->fetch_assoc();

  

}
else
{
     echo "<script>window.location = \"\";</script>";
die();
}
  $conn->close();

?>
<!doctype html>

<html lang="en">
  <head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>iHappyU</title>
    <meta http-equiv="cleartype" content="on">
    <meta name="theme-color" content="#660099">
    <meta name="MobileOptimized" content="320">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <meta name="HandheldFriendly" content="True">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="css/viewCard.css">
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/theme.min.css">
    <link rel="stylesheet" type="text/css" href="css/shared.css">
    <link rel="stylesheet" href="bower_components/animate.css/animate.min.css">
	<script src="js/slideout.min.js"></script>
    <script src="js/recorder.js"></script>
	<script src="js/jquery.js"></script>
	<script src="js/shared.js"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/hammer.js"></script>
    <script src="js/jquery.hammer.js"></script>
<script src="js/jquery.mobile.custom.min.js"></script>
<script src="js/google-analytics.js"></script>
	<script>
    //var stranger = false;
    //var recorded = false;
    //var audioprocessing = false;
    //var recording = false;
    //var vntoggable = true;
    var sending = false;
    var sent = false;

    $(function(){
        initDrag();
        init();
        newinit();
        
        $("#txtMsg").focus(function() {
    if ($(this).text() == placeholder) {
        $(this).text("");
    }
}).focusout(function() {
    if (!$(this).text().length) {
        $(this).text(placeholder);
    }
});});
function closeInstruction()
{
    $("#instruction").addClass("fadeOutLeft animated");
}
function newinit()
{
    setTimeout(function(){$('.greeting').addClass('animated fadeOut');},2250);
    setTimeout(function(){$('.greeting').css('display','none');$('#panel').css('display','inline');$("#instruction").css({"opacity":"0.6"},{"transition":"1s ease"});drawHexs();},3250);
    setTimeout(function(){zoomTo("#hex-" + maxMsg);setTimeout(function(){enlarge("#hex-" + maxMsg,1.8,true);},1000);},3750+drawHexTime*1000);
    setTimeout(function(){$(".hexagon").each(function(){$(this).removeClass('animated fadeIn');})},4250+drawHexTime*1000);
    setTimeout(function(){openContentWrapper();},4750+drawHexTime*1000);
    originalWidth = $(window).width();
    originalHeight = $(window).height();
    $("#vidmax").html(maxMsg);
    $("#vidmax2").html(maxMsg);
    $("[name='question']").each(function(){$(this).html(question)});
    getMsg(maxMsg,function(){
        //set lasts
        $("#last-id").html($("#vid").html());
        $("#last-sender").html($("#vsender").html());
    });
    $("[name='code']").each(function(){$(this).html(code)});
    
    
    $( window ).resize(function() {
   centerx = $(window).width() / 2;
centery = $(window).height() / 2;
var output = centerx + "px" + " " + centery + "px";
addVendorCss("#hexfield","transform-origin", output);
zoomTo("#hex-" + currentMsg,false);

setNewHexSize();
});

//input enter
/*$("#srecipient").keyup(function(e){
        if (e.which == 13)
        {
	        nextPage();
        }
        return false;
			
	  });
$("#ssender").keyup(function(e){
        if (e.which == 13)
        {
	        sendMessage();
        }
        return false;
			
	  });*/
$("#holder-confirm").click(function(){
getMsg(currentMsg,function(){});
subPage("viewmsg",true);
});
$("#holder-deny").click(function(){
closeContentWrapper();
});
$("#btn-send").click(function(){
sendMessage();
});
$("#btn-chinese").click(function(){
var temp = window.location.href.replace("&en","&zh");
window.location = temp;
});
currentMsg = maxMsg;
setNewHexSize();
} //end of new init

    function updateCharCount()
    {
        $('#charcount').html($('#txtMsg').text().length);
    }

    
    
	function setNewHexSize()
    {
        var temp;
        temp = Math.max($(window).width()/length,$(window).height()/length);
        $("#hex-new").css("transform","scale("+temp+")");
    }
        
  function sendMessage()
  {
     document.activeElement.blur();
     $("input").blur();
      //check validity
      if (sending) return;
      
        if ($('#txtMsg').text() == placeholder)
        {
          showMessage("Message cannot be empty!");return;
        }

      if ($('#txtMsg').text().length > 3000){showMessage("Character count limit is 3000!");return;}
      if (!($("#srecipient").val().indexOf('=') == -1 && $("#srecipient").val().indexOf('&') == -1))
      { showMessage("Sorry, =, ? and | are not accepted."); return;}

      sending = true;
      showMessage("Sending message...",false);
      //prepare data
      
      var msg = $('#txtMsg').html().replace(/<div>/g, '\n');
      msg = msg.replace(/<\/div>/g, '');
      msg = msg.replace(/<br>/g, '\n');
      

      //start sending
      var objdata = {code: $("#code").val(), sender: $("#ssender").val(), recipient: $("#srecipient").val(), msg: msg};
      $.ajax({
      type: "POST",
      url: "sendMessage.php",
      data: objdata,
      dataType: 'text',
      success: function(response){
          
          if (response == "1")
          {
              //document.cookie += document.getElementById('code').value.toUpperCase() + "=" + (document.getElementById('latestID')+1)+";expires=Tue, 19 Jan 2038 03:14:07 UTC;";
              //add message sent code here
              showMessage("Thank you for keeping this line alive!<br>Please now give the card to the recipient and keep this line going!",true,8000);
              sent = true;
              $("#vidmax").html(maxMsg+1);
              $("#vidmax2").html(maxMsg+1);

              animateAddNewHexagon();
          }
          else
          {
              showMessage("Oops, something went wrong! Please try again.");
              
              sending = false;
              
          }
      },
      error: function(xhr, ajaxOptions, thrownError){
          showMessage("Cannot reach the server, please try again later.");
          sending = false;
          
      },
      complete: function(){sending = false;}
      });
  }
  function animateAddNewHexagon()
  {
      var temp;
        temp = Math.max($(window).width()/length,$(window).height()/length);
        var ghex="#ghex-"+(maxMsg+1);
      $("#content-wrapper").css({"transform": "scale(1.5)","transition": "1s ease-in"});
      $("#hex-new").addClass("animated fadeIn").css({"display":"block","animation-duration":"0.8s","animation-delay":"0.2s"});
      retract("#hex-"+maxMsg);
      setTimeout(function(){
        zoomTo(ghex,false);
        
        removeVendorCss(ghex,"transition");
        $(ghex).removeClass("ghost-hexagon").addClass("hexagon").css({"transform":"scale("+temp+")","background-color":"#FFB429","opacity":"1"});
      },1000);
      setTimeout(function(){
        $("#content-wrapper").css({"display":"none","transition":"","transform":""});
        $("#hex-new").css({"display":"none"});
        addVendorCss(ghex,"transition","1s ease-out");
        $(ghex).css({"transform":"scale(1)","z-index":"100"});
      },1050);
      
      //set color,canDrag,canSelect
      setTimeout(function(){
          zoomTo(ghex);
          $(ghex).css({"transform":"scale(1)","z-index":"0"});
          addVendorCss("#hex-"+maxMsg,"transition","background-color 1000ms");
          $("#hex-"+maxMsg).css("background-color","#F3D6FA");
          $("#hex-t-"+(maxMsg+1)).css("display","block");
          
          canDrag=true;
          canSelect=true;
          
      
      $(ghex).attr("id","hex-"+(maxMsg+1));
      },2850);
      setTimeout(function(){
          alert("Please give the card to the recipient :) Otherwise, this iHappyU card line will come to a stop");
      },4850);
  }

function getMsg(id, callback, viewmsg2)
{
    var urlmo = "getMsg.php?code=" + $("#code").val() + "&id=" + id;
    if (questionno == 9){urlmo += "&en";}
    $.ajax({
        type: "GET",
        url: urlmo,
        dataType:'text',
        success: function(response){
            var results = response.split("{e}");
            if (results[0] == "1")
            {
                var addon = (viewmsg2?"2":"");
                var arr = results[1].split("|");
                //success
                $("#vid" + addon).html(arr[0]);
                $("#vdate" + addon).html(getDate(arr[1]));
                $("#vsender" + addon).html(arr[2] == "" ? "(anonymous)" : arr[2]);
                $("#vrecipient" + addon).html(arr[3] == "" ? "(reader)" : arr[3]);
                $("#vmsg" + addon).html(decodeEntities(arr[4]).replace(/\n/g, '<br>'));
                if (id == maxMsg && !sent)
                {
                    $("#vnote" + addon).html("Only <b>you</b> can extend this line - all you gotta do is write a short message and give (or slip) the card to someone!<div style=\"padding-top:20px;\"><div onClick=\"javascript:nextPage();\" class=\"btn btn-primary purple-background white\" style=\"white-space: normal;\">Send a new Message</div></div>");
                }else
                {
                    $("#vnote" + addon).html("Click the arrow to read other messages passed through this card.");
                    $("#vnote").html("Click the arrow to read other messages passed through this card.");
                }
                hideMessage();
                callback();
            }
            else
            {
                showMessage("Oops, something went wrong!");
                
            }
        },
        error: function(xhr, ajaxOptions, thrownError){
            showMessage("Cannot reach the server, please try again later.");
        }
    });
}

    function getDate(timestamp)
    {
    var date = new Date(timestamp * 1000);
var formattedDate = ('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear() + ' ' + ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2);
return formattedDate;
    }
    
// new functions


</script>

<!--hexs-->

<?php

  echo "<script>var questionno=" . $questionno . ";</script>";
  echo "<script>var maxMsg=" . $maxmsg . ";var code=\"" . strtolower($safecode) . "\";var question=\"#" . ($questionno+1) . " " . $row["data"] . "\";</script>";
  echo "<input type=\"text\" id=\"code\" style=\"display: none;\" value=\"" . $safecode . "\">";


?>
<input type="text" id="code" style="display: none;" value="hehehe">
<script>
//var maxMsg=10;
//var question="test";


var currentHex = 1;
var lastHex = false;

var centerColor = "#6A1C8A";
var fromColor = {r:186, g:101, b:219};
var toColor = {r:243, g:214, b:250};//
var hexoffsetx;
var hexoffsety;
var centerx;
var centery;
var length = 28.87;
var hexDimension = 50;
var drawHexTime = 1.2;
function drawHexs()
{
var offsetx = hexDimension/2;
var offsety = hexDimension/2;
//var x = $(window).width() / 2 - offsetx;
//var y = $(window).height() / 2 - offsety;
var x = 0 - offsetx;
var y = 0 - offsety;
var ring = 0;

var currentDelay = 0;
var sqrt3 = 1.73205080757;
var l15 = length * 1.5;
var lsqrt3 = sqrt3 * length;

var stepDelay = drawHexTime / maxMsg;

centerx=$(window).width() / 2;
centery=$(window).height() / 2;

//center piece
addHexagon(x,y,currentDelay,centerColor);
//setOrigin
var output = centerx + "px" + " " + centery + "px";
addVendorCss("#hexfield","transform-origin", output);
addVendorCss("#hexfield","transform","translate(" + centerx + "px," + centery + "px)");
adjustDeltaX = centerx;
currentDeltaX = centerx;
adjustDeltaY = centery;
currentDeltaY = centery;
while(++ring)
{
//right once
	currentDelay += stepDelay;
	x += lsqrt3;
	addHexagon(x,y,currentDelay);
	for(i = 0; i < ring - 1; i++)
	{
		if (lastHex)break;
		x += lsqrt3 / 2;
		y += l15;
		currentDelay += stepDelay;
		addHexagon(x,y,currentDelay);
		
	}
	for(i = 0; i < ring; i++)
	{
	if (lastHex)break;
		x -= lsqrt3 / 2;
		y += l15;
		currentDelay += stepDelay;
		addHexagon(x,y,currentDelay);
	}
	for(i = 0; i < ring; i++)
	{
	if (lastHex)break;
		x -= lsqrt3;
		currentDelay += stepDelay;
		addHexagon(x,y,currentDelay);
	}
	for(i = 0; i < ring; i++)
	{
	if (lastHex)break;
		x -= lsqrt3 / 2;
		y -= l15;
		currentDelay += stepDelay;
		addHexagon(x,y,currentDelay);
	}
	for(i = 0; i < ring; i++)
	{
	if (lastHex)break;
		x += lsqrt3 / 2;
		y -= l15;
		currentDelay += stepDelay;
		addHexagon(x,y,currentDelay);
	}
	for(i = 0; i < ring; i++)
	{
	if (lastHex)break;
		x += lsqrt3;
		currentDelay += stepDelay;
		addHexagon(x,y,currentDelay);
	}
	if (lastHex)break;
}
setTimeout(function(){
        $(".ghost-hexagon").each(function(){
            
            $(this).css("opacity", 1-(parseInt(this.id.replace("ghex-",""))/ 12));
            
            });
        
        } 
        ,drawHexTime*1000);

//canDrag = true;
function addHexagon(x, y, delay, color)
{
    var real = true;
    var css = "animation-delay: " + delay + "s;";
    if (color === undefined) color = makeGradientColor(fromColor,toColor,currentHex/maxMsg);
    
if (currentHex == maxMsg){
    color="#FFB429";
}
if (currentHex > maxMsg)
{
    
    if (currentHex > 11) lastHex = true;
    real = false;
}
css += "top: " + y + "px; left: "+x+"px;";
if (currentHex == maxMsg + 1)
{
    if (currentHex>11)lastHex=true;
$("#hexfield").append("<div id=\"hex-t-" + currentHex+"\" class=\"hex-t animated fadeIn\"style=\"display:none;" + css +"\">#" + currentHex + "</div>");
        
}    

	
	
    if (real){
        $("#hexfield").append("<div id=\"hex-t-" + currentHex+"\" class=\"hex-t animated fadeIn\"style=\"" + css +"\">#" + currentHex + "</div>");
        css += "background-color: "+color+";";
	$("#hexfield").append("<div id=\"hex-"+currentHex+"\" class=\"hexagon animated fadeIn\" style=\""+ css + "\"></div>");
    }else
    {

        css += "background-color: "+color+";";
        css += "opacity: 0;"
        css += "transition: ease 0.71s";
	$("#hexfield").append("<div id=\"ghex-"+currentHex+"\" class=\"ghost-hexagon\" style=\""+ css + "\"></div>");
    
    }
	
	currentHex++;
	
}
$(".hex-t").mouseenter(function(){
    if (canSelect)
enlarge("#"+this.id.replace("-t",""),1.2);
}).mouseout(function(){
    if (canSelect)
retract("#"+this.id.replace("-t",""));
}).on("vclick",function(){
    if (canSelect && !hammering)
    {
        var id = this.id.replace("hex-t-","");
        /*if (id == maxMsg && !sent)
        {
        setPage("holder");
        }
        else{*/
        setPage("viewmsg");
        //}
        showMessage("Retreiving data...",false);
        enlarge("#hex-" + id,1.8);
        getMsg(id, function(){zoomTo("#hex-" + id);  openContentWrapper();});
    }
});

}

function makeGradientColor(color1, color2, percent) {
    var newColor = {};

    function makeChannel(a, b) {
        return(a + Math.round((b-a)*(percent)));
    }

    function makeColorPiece(num) {
        num = Math.min(num, 255);   // not more than 255
        num = Math.max(num, 0);     // not less than 0
        var str = num.toString(16);
        if (str.length < 2) {
            str = "0" + str;
        }
        return(str);
    }

    newColor.r = makeChannel(color1.r, color2.r);
    newColor.g = makeChannel(color1.g, color2.g);
    newColor.b = makeChannel(color1.b, color2.b);
    newColor.cssColor = "#" + 
                        makeColorPiece(newColor.r) + 
                        makeColorPiece(newColor.g) + 
                        makeColorPiece(newColor.b);
    return(newColor.cssColor);
}

//dragging and camera


var currentScale = 1;
var adjustScale = 1;
var adjustDeltaX = 0;
var adjustDeltaY = 0;



var currentDeltaX = null;
var currentDeltaY = null;
var canDrag = false;
var canSelect = false;
var hammering = false;
var currentMsg = 0;
function enlarge(hexid,data,solo)
{
    addVendorCss(hexid,"transition","0.25s ease-out");
    addVendorCss(hexid,"transform","scale("+data+")");
    $(hexid).css("z-index",2);
    if (solo) {$(hexid).css("z-index",11);
    canSelect = false;}
}
function retract(hexid)
{
    addVendorCss(hexid,"transition","0.25s ease-out");
    addVendorCss(hexid,"transform","scale(1)");
    $(hexid).css("z-index",0);
}
function zoomTo(hexid,duration)
{
    duration = !duration;
    currentMsg = parseInt(hexid.replace("#hex-","").replace("#ghex-",""));
   
    var toY = centery - parseFloat($(hexid).css("top")) -  hexDimension/2;
    //console.log(centery,parseFloat($("#hex-"+hexid).css("top")),hexDimension/2);
    var toX = centerx - parseFloat($(hexid).css("left"))-  hexDimension/2;
    if (duration)addVendorCss("#hexfield","transition","1s ease-in-out");
    else
    {
        removeVendorCss("#hexfield","transition");
    }
    $("#hexfield").css("transform", "scale(4) translate(" + toX + "px, " + toY + "px)");
    currentScale = 4;
    adjustScale = 4;
    adjustDeltaX = toX;
    currentDeltaX = toX;
    adjustDeltaY = toY;
    currentDeltaY = toY;
    setTimeout(function(){addVendorCss("#hexfield","transition","linear");},1000);
}
function addVendorCss(id, index, data)
{   
    $(id).css(index,data);
    $(id).css("-moz-" + index,data);  
    $(id).css("-o-" + index,data);  
    $(id).css("-webkit-" + index,data);
    $(id).css("-ms-" + index,data);    
    
}
function removeVendorCss(id, index)
{
$(id).css(index,"");
    $(id).css("-moz-" + index,"");  
    $(id).css("-o-" + index,"");  
    $(id).css("-webkit-" + index,"");
    $(id).css("-ms-" + index,"");    
}
function initDrag()
{
    
var field = document.querySelector('.container-fluid');

var mc = new Hammer.Manager(field);

var pinch = new Hammer.Pinch();
var pan = new Hammer.Pan();

pinch.recognizeWith(pan);

mc.add([pinch, pan]);




// Prevent long press saving on mobiles.

var webpage = document.getElementById('hexfield');
webpage.addEventListener('touchstart', function (e) {
    e.preventDefault();
});

// Handles pinch and pan events/transforming at the same time;

mc.on("pinch pan", function (ev) {
    if (canDrag)
    {
    hammering = true;
    var transforms = [];

    // Adjusting the current pinch/pan event properties using the previous ones set when they finished touching
    currentScale = adjustScale * ev.scale;
    currentDeltaX = adjustDeltaX + (ev.deltaX / currentScale);
    currentDeltaY = adjustDeltaY + (ev.deltaY / currentScale);

    // Concatinating and applying parameters.
    transforms.push('scale(' + currentScale + ')');
    transforms.push('translate(' + currentDeltaX + 'px,' + currentDeltaY + 'px)');
    webpage.style.transform = transforms.join(' ');
    }
});


mc.on("panend pinchend", function (ev) {
    setTimeout(function(){hammering = false;},300);
    // Saving the final transforms for adjustment next time the user interacts.
    adjustScale = currentScale;
    adjustDeltaX = currentDeltaX;
    adjustDeltaY = currentDeltaY;}

);

$(window).on("mousewheel", function(e) {
    if (!(/Mobi/.test(navigator.userAgent))) {
    if (canDrag && e.ctrlKey) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var transforms = [];
        
        // perform desired zoom action here
        currentScale = adjustScale * (e.originalEvent.wheelDelta > 0 ? 1.02 : 0.98);
        transforms.push('scale(' + currentScale + ')');
        transforms.push('translate(' + currentDeltaX + 'px,' + currentDeltaY + 'px)');
        
        webpage.style.transform = transforms.join(' ');
        adjustScale = currentScale;
    }}
    
    
    }
);

 
}

//manipulate pages

//
var currentPage="viewmsg";
function openContentWrapper()
{
    $("#content-wrapper").addClass("animated fadeInDown");
    $("#content-wrapper").css("display","block");
    setTimeout(function(){$("#content-wrapper").removeClass("animated fadeInDown");},1000);
    canSelect = false;
    canDrag = false;
}
function closeContentWrapper()
{
    $("#content-wrapper").addClass("animated fadeOutDown");
    setTimeout(function(){$("#content-wrapper").css("display","none");$("#content-wrapper").removeClass("animated fadeOutDown");canSelect = true;
    canDrag = true;},1000);
    
    retract("#hex-"+currentMsg);
    hideMessage();
}
function subPage(to,right)
{
    if (right){
    $("#content-" + currentPage).removeClass("animated fadeInRight fadeInLeft").addClass("animated fadeOutLeft");
    setTimeout(function(){$("#content-" + currentPage).css("display","none");currentPage=to;$(".content").each(function(){$(this).removeClass("animated fadeInRight fadeInLeft fadeOutRight fadeOutLeft");});},1000);
    $("#content-" + to).addClass("animated fadeInRight").css("display","block");
    }else
    {
        $("#content-" + currentPage).removeClass("animated fadeInRight fadeInLeft").addClass("animated fadeOutRight");
    setTimeout(function(){$("#content-" + currentPage).css("display","none");currentPage=to;$(".content").each(function(){$(this).removeClass("animated fadeInRight fadeInLeft fadeOutRight fadeOutLeft");});},1000);
    $("#content-" + to).addClass("animated fadeInLeft").css("display","block");
    }
    
}
function setPage(to)
{
    currentPage = to;
    $(".content").each(function(){$(this).css("display","none");});
    $("#content-"+to).css("display","block");
}
function confirmHolder()
{
    subPage("viewmsg",false);
}
function decodeEntities(encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
}
function prevPage()
{
    switch (currentPage)
    {
        case "viewmsg":
            if (currentMsg > 1){
            retract("#hex-" + currentMsg);
            currentMsg = parseInt(currentMsg) - 1;
            showMessage("Retreiving data...",false);
            getMsg(currentMsg,function(){
                subPage("viewmsg2",false);
                setTimeout(function(){
                    $("#vid").html($("#vid2").html());
                    $("#vdate").html($("#vdate2").html());
                    $("#vsender").html($("#vsender2").html());
                    $("#vrecipient").html($("#vrecipient2").html());
                    $("#vmsg").html($("#vmsg2").html());
                    $("#vnote").html($("#vnote2").html());
                    setPage("viewmsg");},1000);
            },true);
            enlarge("#hex-" + currentMsg,1.8);
            zoomTo("#hex-" + currentMsg);
            }
        
        break;
        case "holder":
        showMessage("Nothing to go back to!");
        break;
        case "send1":
        subPage("viewmsg",false);
        break;
        case "send2":
        subPage("send1",false);
        break;
        case "send3":
        subPage("send2",false);
        break;
        case "send4":
        subPage("send3",false);
        break;
    }
}
var placeholder = "Content of the message";
function nextPage()
{
    switch (currentPage)
    {
        case "viewmsg":
        if (currentMsg == maxMsg && !sent){
            subPage("send1",true);
        }
        /*else if (currentMsg == maxMsg - 1 && !sent){
            retract("#hex-" + currentMsg);
            subPage("holder",true);
            currentMsg = maxMsg;
            
            enlarge("#hex-" + currentMsg,1.8);
            zoomTo("#hex-" + currentMsg);
        }*/
        else{
            if (currentMsg < maxMsg || (sent&&(currentMsg==maxMsg))){
            retract("#hex-" + currentMsg);
            currentMsg = parseInt(currentMsg) + 1;
            showMessage("Retreiving data...",false);
            getMsg(currentMsg,function(){
                subPage("viewmsg2",true);
                setTimeout(function(){
                    $("#vid").html($("#vid2").html());
                    $("#vdate").html($("#vdate2").html());
                    $("#vsender").html($("#vsender2").html());
                    $("#vrecipient").html($("#vrecipient2").html());
                    $("#vmsg").html($("#vmsg2").html());
                    $("#vnote").html($("#vnote2").html());
                    setPage("viewmsg");},1000);
            },true);
            enlarge("#hex-" + currentMsg,1.8);
            zoomTo("#hex-" + currentMsg);
            }
        }
        
        break;
        case "holder":
        showMessage("You must confirm before proceeding!");
        break;
        case "send1":
        
            sendMessage();
        //subPage("send2",true);
        break;
        case "send2":
        
        subPage("send3",true);
        break;
        case "send3":
        subPage("send4",true);
        break;
        case "send4":

        break;
    }
}
</script>
    <style>
    .lighter {
    font-weight: 300;
}

.hexagon {
  position: absolute;
  width: 50px; 
  height: 28.87px;
  margin: 14.43px 0;
  border-left: solid 2px #ffffff;
  border-right: solid 2px #ffffff;
  display: inline-block;

   animation-duration: 0.4s;
   transform-origin: 25px 25px;
   -webkit-transform: translateZ(0);
   -moz-transform: translateZ(0);
   -ms-transform: translateZ(0);
   -o-transform: translateZ(0);
   transform: translateZ(0);
}
.hexagon:before,
.hexagon:after {
  content: "";
  position: absolute;
  z-index: 1;
  width: 35.36px;
  height: 35.36px;
  -webkit-transform: scaleY(0.5774) rotate(-45deg);
  -ms-transform: scaleY(0.5774) rotate(-45deg);
  transform: scaleY(0.5774) rotate(-45deg);
  background-color: inherit;
  left: 5.3223px;
}

.hexagon:before {
  top: -17.6777px;
  border-top: solid 2.8284px #ffffff;
  border-right: solid 2.8284px #ffffff;
}

.hexagon:after {
  bottom: -17.6777px;
  border-bottom: solid 2.8284px #ffffff;
  border-left: solid 2.8284px #ffffff;
}

.ghost-hexagon {
  position: absolute;
  width: 50px; 
  height: 28.87px;
  background-color: #ffffff;
  margin: 14.43px 0;
  border-left: solid 2px #333333;
  border-right: solid 2px #333333;
    display: inline-block;

   animation-duration: 0.4s;
   transform-origin: 25px 25px;
  -webkit-transform: translateZ(0);
   -moz-transform: translateZ(0);
   -ms-transform: translateZ(0);
   -o-transform: translateZ(0);
   transform: translateZ(0);
}

.ghost-hexagon:before,
.ghost-hexagon:after {
  content: "";
  position: absolute;
  z-index: 1;
  width: 35.36px;
  height: 35.36px;
  -webkit-transform: scaleY(0.5774) rotate(-45deg);
  -ms-transform: scaleY(0.5774) rotate(-45deg);
  transform: scaleY(0.5774) rotate(-45deg);
  background-color: inherit;
  left: 5.3223px;
}

.ghost-hexagon:before {
  top: -17.6777px;
  border-top: solid 2.8284px #333333;
  border-right: solid 2.8284px #333333;
}

.ghost-hexagon:after {
  bottom: -17.6777px;
  border-bottom: solid 2.8284px #333333;
  border-left: solid 2.8284px #333333;
}

*[contenteditable] {
    -webkit-user-select: text !important;
    user-select: text !important;
}

.purple-background
{
    background-color: #6A1C8A;
    border-color: #803889;
}
.white
{
    color: #FFFFFF;
}

</style>
	  </head>
  
 <body>
	
    <!-- fb like -->
    <script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_PI/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    <!-- end -->
    


	
	<div class="container-fluid" style="height: 100vh; overflow: hidden;">
    
<!-- main content - canvas-->
	 
   


    
    


    <div class="greeting">
    <div id="greet-title" class="animated fadeInDown">
    <h1><span class="lighter">Welcome To iHappyU Card</span></h1>
    <div id="greet-subtitle" class="animated fadeInDown">
    <h4>Line <span name="code" style="color: #FF8F8F"></span></h4>
    </div>
    </div>
    </div>

<div id="hex-wrapper" style="width: 100vw; height: 100vh;overflow: hidden;posiiton: fixed;">
    <div id="hexfield" style="width: 100vw, height: 100vh; position: fixed; top:0px;left:0px;touch-action: pan-y; -webkit-user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
	</div>
    </div>
    <a href="http://ihappyu.org" style="position: fixed; opacity: 0.6; top: 10px; left: 10px;" class="btn btn-default btn-circle"><span class="glyphicon glyphicon-home"></span></a>
    <a id="btn-chinese" style="position: fixed; opacity: 0.6; top: 10px; left: 50px;" class="btn btn-default btn-circle"><span>中</span></a>
    

    <div id="hex-new" style="background-color:#FFB429;display:none;position: fixed;left:0;right:0;opacity:0;width:100vw;height:100vh;z-index:200;"></div>
   
    <div class="lineno animated fadeInLeft">
    <span class="lighter">#<span name="code">93849</span></span>
    </div>

    <div id="instruction" style="position: fixed; background-color: black; opacity: 0;  padding: 20px; transition: 0.7s ease; display: block;bottom:20px;left:0;color:white;">
    Each cell in this honeycomb is a message in the line!<br>Swipe: To move around the honeycomb.<br>Pinch: To zoom in and out.<br>Click: To view the message
    
    <a style="position:absolute; bottom:10px;right:10px;" href="javascript:closeInstruction();" class="btn btn-default btn-circle"><span class="glyphicon glyphicon-ok"></span></a>
      
    </div>
    </div>
    <div id="content-wrapper" style="display: none;">

    <!--<div class="content" id="content-holder">
<div style="top: 20%; padding:10px; text-align: center;color: #FF5757;font-size: small;font-weight:lighter; position: absolute; width:100%; height:100%;">#<span id="last-id" style="font-size: xx-large;">50</span> <span style="font-weight: lighter; font-size: small;color: #AAAAAA">from</span> <span style="font-weight: lighter; font-size: x-large;color: #5F00AD" id="last-sender">neli</span></div>
    
    <div style="position:absolute; bottom:0; width:100%;">
    <div style="padding:10px; text-align: center;color: #FF8F8F;">Are you the current card-holder?<br>Please do not open this message if you are not the current card-holder!</div>
    <div id="btnwrapper" style="text-align: center;">
    <div id="holder-confirm" class="btn btn-default" style="white-space: normal;">Yes, I am holding the card.</div>
    <div id="holder-deny" class="btn btn-default" style="white-space: normal;">No, I just want to take a look at the history.</div>
    </div>
    </div>
    </div>-->

    <div class="content" id="content-viewmsg" style="overflow: auto;-webkit-overflow-scrolling:touch;">
    <p name="question" style="font-weight:300; color: #FF5757;">If theres something foidjsofisf?</p>
    <div style="padding: 5px; font-size: small; font-weight:300;"><div style="display: inline-block;">#<span id="vid" style="font-size: large;">17</span>/<span id="vidmax"></span></div><div style="display: inline-block; float: right;"><span id="vdate">65454</span></div></div>
    <div style="padding: 10px;"><span style="font-weight: 300;">Dear </span><span id="vrecipient" style="color: #FF5757; font-size: xx-large;font-weight: 300;">sun</span>,</div>
    <div style="padding: 10px; word-wrap: break-word;" id="vmsg">
    Retreiving Content
    </div>
    <div style="float:right;text-align: right; word-wrap: break-word;width:100%;">
    <span id="vsender" style="font-weight:300; color:#FFA200; font-size: x-large; ">neli</span>
    </div>
    <div style="margin:10% 10% 0% 10%;text-align: center; word-wrap: break-word;color:#444444;font-size: x-small;font-weight:300;"><span id="vnote">Click the arrow to read other messages passed through this card.</span> 
    <br /><br />By the way, please support us by liking us :)<br /><div class="fb-like" data-href="https://www.facebook.com/ihappyuorg" data-width="150" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div></div>
    </div>


    <div class="content" id="content-viewmsg2" style="display: none; overflow: auto;-webkit-overflow-scrolling:touch;">
    <p name="question" style="font-weight:300; color: #FF5757;">If theres something foidjsofisf?</p>
    <div style="padding: 5px; font-size: small; font-weight:300;"><div style="display: inline-block;">#<span id="vid2" style="font-size: large;">17</span>/<span id="vidmax2"></span></div><div style="display: inline-block; float: right;"><span id="vdate2">65454</span></div></div>
    <div style="padding: 10px;"><span style="font-weight: 300;">Dear </span><span id="vrecipient2" style="color: #FF5757; font-size: xx-large;font-weight: 300;">sun</span>,</div>
    <div style="padding: 10px; word-wrap: break-word;" id="vmsg2">
    Retreiving Content
    </div>
   <div style="float:right;text-align: right; word-wrap: break-word;width:100%;">
    <span id="vsender2" style="font-weight:300; color:#FFA200; font-size: x-large; ">neli</span>
    </div>
        <div style="margin:10% 10% 0% 10%;text-align: center; word-wrap: break-word;color:#444444;font-size: x-small;font-weight:300;"><span id="vnote2">Click the arrow to read other messages passed through this card.</span> 
    <br /><br />By the way, please support us by liking us :)<br /><div class="fb-like" data-href="https://www.facebook.com/ihappyuorg" data-width="150" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div></div></div>



  
    <div class="content" id="content-send1" style="display: none; overflow: auto; -webkit-overflow-scrolling:touch;">
    <!--<div style="margin: 15% auto 15% auto; text-align: middle">
    <p>Your friend <span style="color: #6A1C8A; font-weight: 300; font-size: x-large;">paid it forward</span>. We hope that you can pay it forward as well by writing a message to another person to keep this card going!</p>
    <p>Start by thinking for whom your message will be!</p>
    </div>-->
    <div style="text-align: middle; margin: 0 auto 0 auto;">
    <p name="question" style="font-size: x-large;color: #FF5757;">If theres something foidjsofisf?</p>
    <div class="materialgroup"><input type="text" id="srecipient" required><span class="highlight"></span><span class="bar"></span><label style="font-weight:300;">Recipient's name</label><span style="font-weight:300">You may leave this blank.</span></div>
    
    </div>
	<div>
	<div id="txtMsg" style="color: black; width: 90%;overflow: auto;-webkit-overflow-scrolling:touch; min-height: 50vh;" onkeyup="updateCharCount()" contenteditable="true" class="dotted full">Content of the message</div>
       <span style="color: #BABABA; font-size: 0.7em;"><i>Character count: <span id="charcount"></span>/3000</i></span>
	</div>
	
	    
    
    <div style="text-align: middle; margin: 0 auto 0 auto;">
    
    <div class="materialgroup"><input type="text" id="ssender" required><span class="highlight"></span><span class="bar"></span><label style="font-weight:300;">Your name</label><span style="font-weight:300">Leave it blank if you wish to be anonymous.<div style="padding-top:20px;"><div id="btn-send" class="btn btn-primary purple-background white" style="white-space: normal;">Add this message to the line</div></div></span>
    </div>
    
	</div>
	   
    </div>

	<!--endofsend-->
    <div class="content" id="content-send2" style="display: none;">
    <span style="color: #555555">Start typing your message here!</span>
    <div id="txtMsg" style="color: black; width: 100%;overflow: auto;-webkit-overflow-scrolling:touch; height: 80%;" onkeyup="updateCharCount()" contenteditable="true" class="dotted full">Content of the message</div>
       
       <span style="color: #BABABA; font-size: 0.7em;"><i>Character count: <span id="charcount"></span>/3000.</i></span>
    </div>

    <div class="content" id="content-send3"style="display: none;">
    <div style="margin: 15% auto 15% auto; text-align: middle">
    <p><span style="color: #6A1C8A; font-weight: 300; font-size: x-large;">How would you sign it off?</span></p>
    <span style="font-weight:300">Leave it blank if you wish to be anonymous.</span>
    </div>
    <div style="text-align: middle; margin: 0 auto 0 auto;">
    
    <div class="materialgroup"><input type="text" id="ssender" required><span class="highlight"></span><span class="bar"></span><label style="font-weight:300;">Name of sender</label></div>
    </div>
    </div>
    <div class="content" id="content-send4" style="display: none;">

    </div>
    
<a href="javascript:prevPage();" style="position: absolute; opacity: 0.6; left: 20px; bottom: 20px;" class="btn btn-default btn-circle"><span class="glyphicon glyphicon-arrow-left"></span></a>
    <a href="javascript:nextPage();" style="position: absolute; opacity: 0.6; right: 20px; bottom: 20px;" class="btn btn-default btn-circle"><span class="glyphicon glyphicon-arrow-right"></span></a>
<a href="javascript:closeContentWrapper();" style="position: absolute; opacity: 0.8; right: 20px; top: 20px;" class="btn btn-default btn-circle"><span class="glyphicon glyphicon-remove"></span></a>


    </div>
	<!--Message box-->
    <div class="msg" id="fixedmsg"></div>
    
</body>

</html>
