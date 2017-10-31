<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>iHappyU</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="favicon.ico" />
    <link rel="stylesheet" href="css/normalize.min.css">
    <link rel="stylesheet" href="css/theme.min.css">
    <link rel="stylesheet" href="css/jquery.fancybox.css">
    <link rel="stylesheet" href="css/flexslider.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/queries.css">
    <link rel="stylesheet" href="css/etline-font.css">
    <link rel="stylesheet" href="css/shared.css">
    <link rel="stylesheet" href="bower_components/animate.css/animate.min.css">
<meta property="og:image" content="img/cards/front.png" />
<meta property="og:description" content="我地相信只要將人拉近，就可以令呢個世界多啲快樂。所以，我地幾個正努力搞緊唔同Projects，希望拉近人與人之間嘅距離。iHappyU Team未必可以為社會帶黎啲咩大影響，但自從呢個名誕生以來，我地就努力令你快樂、我快樂、大家快樂：「iHappyU」" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="js/countUp.js"></script>
    <script src="js/shared.js"></script>
    <script src="js/google-analytics.js"></script>
    <script>
    var cardbackdown = false;
    $( window ).resize(function() {
    $('#cardfront').css("height", parseInt($('#cardfront').css("width")) * 0.64571 + "px");
    $('#cardback').css("height", parseInt($('#cardback').css("width")) * 0.64571 + "px");
});
    $(function() {
        
        
        $('#cardfront').css("height", parseInt($('#cardfront').css("width")) * 0.64571 + "px");
    $('#cardback').css("height", parseInt($('#cardback').css("width")) * 0.64571 + "px");
    addAnimation('#cardfront','fadeInDown');
    window.setTimeout(function(){addAnimation('#cardback','fadeInDown');}, 200);
    $("#code").keyup(function(e){
        if (e.which == 13)
        {
	        showMessage("Just a moment while we verify the code...");
            $.ajax({
                type: "GET",
                url: "checkCard.php?code=" + document.getElementById('code').value,
                dataType:'text',
                success: function(response){
					if (response == "1|")
                    {window.location.href = "viewCard?code=" + document.getElementById('code').value;showMessage("Got it! Now redirecting...");}
					else if (response == "0|")
                    {showMessage("Are you sure you get the code right?");}
                },
                error: function(xhr, ajaxOptions, thrownError){
                    showMessage("Cannot reach the server, please try again later.");
                    
                }
            });
        }
        return false;
			
	  });
      setMsgCount();

      $("#cardfront").on("mouseenter click",function(){
          if (cardbackdown)
          {$("#cardback").css({"transition":"0.5s ease","top":"30px"});cardbackdown=false;}
          else
          {$("#cardback").css({"transition":"0.5s ease","top":"170px"});cardbackdown=true;}
      }).mouseleave(function(){
          $("#cardback").css({"transition":"0.5s ease","top":"30px"});cardbackdown=false;
      });
});
    function addAnimation(element, animation){
        element = $(element);
        element.addClass('animated ' + animation);
    }
    var currentCount = 0;
    function setMsgCount()
    {
        $.ajax({
                type: "GET",
                url: "getMsgCount.php",
                dataType:'text',
                success: function(response){
                var options = {
  useEasing : true, 
  useGrouping : true, 
  separator : ',', 
};
var counter = new CountUp("countdis", currentCount, response, 0, 2.5, options);
currentCount = response;
counter.start();
setTimeout(setMsgCount, 10000);
                },
                error: function(xhr, ajaxOptions, thrownError){
                    showMessage("Cannot reach the server, please try again later.");
                }
            });
    }
    
    
    </script>

    <style>
    .circular {
	width: 100%;
    padding-top: 100%;
	border-radius: 50%;
	-webkit-border-radius: 50%;
	-moz-border-radius: 50%;
	background-repeat: no-repeat;
    background-size: contain;
	box-shadow: 0 0 8px rgba(0, 0, 0, .8);
	-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, .8);
	-moz-box-shadow: 0 0 8px rgba(0, 0, 0, .8);
	}

    .circular-text{
    width: 100%;
    height: 40%;
    color: black;
    position: absolute;
    top: 60%;
    left: 0;
    text-align: center;
    font-weight: 600;
    text-shadow: 1px 1px 5px #999999;
    }

    .name-text{
    width: 100%;
    color: white;
    font-size: large;
    position: relative;
    left: 0;
    text-align: center;
    font-weight: 300;
    }
    </style>
</head>
<body id="top">
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <section class="hero screencard">
        <section class="navigation">
            <header>
                <div class="header-content">
                    <div class="logo"><a href="#"><img src="img/logo.png" alt="iHappyU Logo"style="
    width:50px; 
    height: auto;
"></a></div>
                    <div class="header-nav" style="float: right;padding-top:27px;">
                        <nav>
                            <ul class="primary-nav">
                                <li><a href="#card">iHappyU Card</a></li>
                                <li><a href="#aboutus">About Us</a></li>
                                <li><a href="#vision">Our Vision</a></li>
                                <li><a href="#contact">Contact/Join Us</a></li>
                            </ul>
                            <!--<ul class="member-actions">
                                <li><a href="#download" class="login">Log in</a></li>
                                <li><a href="#download" class="btn-white btn-small">Sign up</a></li>
                            </ul>-->
                        </nav>
                    </div>
                    <div class="navicon">
                        <a class="nav-toggle" href="#"><span></span></a>
                    </div>
                </div>
            </header>
        </section>
        <div class="container">
            <div class="text-center" style="position: relative; padding-top: 160px; "><h2>The cards have been passed down <span id="countdis" style="color:purple;">0</span> times.</h2></div>
                    
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="hero-content text-center">
                        
                        <div class="col-md-7"><!-- style="width: 60%;display: table-cell;">-->
                                    <div style="height:300px;">
                                        
                                        <div id="cardfront" class ="shadowleft" style="opacity:0;width:350px; max-width:90%; height: 226px; position:relative;background-image: url('img/cards/front.png');background-repeat:no-repeat; background-size: contain;height: auto;z-index: 9;
                                          margin: 0 auto;'">
                                        <div id="cardback" class ="shadowleft" style="opacity:0;width:350px; max-width:100%; height: 226px;position:relative;left:30px;top:30px;background-image : url('img/cards/back.png');background-repeat:no-repeat;background-size: contain;z-index: 10;"></div>

                                        </div>
                                                                                
                                        
                                    </div>
                          </div>
                          <div class="col-md-5"> <!-- style="width: 40%;display: table-cell;">-->
                                    <div class="animated fadeInDown" style="float:middle">
                                        <h2>Start your iHappyU<br />journey here.</h2><br />
                                        <input placeholder="Type your card number here" class="codebox" type="text" id="code" name="code">
                                        <div style="padding-bottom: 100px;"></div>
                                    </div>
                                </div>
                            
                    </div>
                </div>
            </div>
        </div>
        <div class="down-arrow floating-arrow"><a href="#"><i class="fa fa-angle-down"></i></a></div>
    </section>
    <section class="intro section-padding fs" id="card">
        <!--A443C4--><div> <h2 style="color: #333333;text-align:center;">iHappyU Card Project</h2></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 intro-feature wp1">
                    <div class="intro-content">
                    <video id="whatisvid" loop style="width:100%;height: auto;">
  <source src="vids/whatis.webm" type="video/webm">
  <source src="vids/whatis.mp4" type="video/mp4">
  Your browser does not support HTML5 videos.
</video>
                        <!--<img src="img/chat.png" style="width:90%;height:auto;display: block;
    margin: 0 auto;" alt="Chat Screen" />
    <i>Fig: What you would see if you enter a card number.</i>-->
                    </div>
                </div>
                <div class="col-md-6 intro-feature wp2">
                    <div class="intro-content">
                        <h5>IHAPPYU CARD</h5>
                        
                        <p>iHappyU card aims to provide an alternative way to connect you to people around you, may it be a dear friend or a stranger. Using iHappyU card, you may send them your blessings with words of encouragement. But most importantly, you are telling your friends that you treasure and care about them so much that you are always willing to listen to them and accompany them. <a href="#howtouse">Click here to see how to use the card!</a></p>
                    </div>
                    <div class="intro-content">
                        <h5>PAY IT FORWARD</h5>
                        <p><i>No, not pay as in money.</i> Pay it forward just means to pass it on, and this is crucial to iHappyU card. Get a card, and pass it on. As the cards go through <b>hands of different people</b>, more messages would be chained together. With all these messages, we hope to create a synergistic effect where happiness can be accumulated, shared and multiplied. Let’s keep the spirit going! <b>We believe that love and care can circulate</b>, not only materialistic concepts like currency. <b>Would you achieve this with us?</b></p>
                    </div>

                    <div class="intro-content">
                        <h5>HOW TO GET A CARD</h5>
                        <p>As every card is <b>UNIQUE</b>, we are distributing the cards <b>(free of charge of course)</b> on a limited basis. You may acquire one at our events or through directly contacting us. <a href="https://facebook.com/ihappyuorg">Like us on Facebook to stay tuned!</a> We hope that every card holder would understand and cherish the opportunity the card signifies - it's not the card that's special, but rather the connection between you and another person. </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="features-extra section-padding fs" style="background-color: #EEEEEE;" id="howtouse">

<div class="container">
            <div style="text-align:center;"> <h2 style="color: #A443C4;">How to use?</h2>
            </div>
            <div class="row">
                <div class="col-md-4 intro-feature wp5">
                    <div class="intro-icon">
                        <span class="glyphicon glyphicon-qrcode" style="font-size: 40px;"></span>
                    </div>
                    <div class="intro-content">
                        <h5>SCAN or INPUT the code</h5>
                        <p>There is an unique code / QR code on every card. Scan it or visit our website to read your friend's message.</p>
                    </div>
                </div>
                <div class="col-md-4 intro-feature wp6">
                    <div class="intro-icon">
                        <span class="glyphicon glyphicon-pencil" style="font-size: 40px;"></span>
                    </div>
                    <div class="intro-content">
                        <h5>Write a new message</h5>
                        <p>Write a new message to another friend. This is essential to keep the line flowing.</p>
                    </div>
                </div>
                <div class="col-md-4 intro-feature wp7">
                    <div class="intro-icon">
                        <span class="glyphicon glyphicon-send" style="font-size: 40px;"></span>
                    </div>
                    <div class="intro-content last">
                        <h5>Pass the card</h5>
                        <p>Give the card to your friend physically. Your friend will then repeat the steps, eventually giving that card to another person. <i>Pay it forward</i> </p>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <section class="features section-padding fs" style="background-image: url('img/whoweare.png');background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;padding: 120px 0; " id="aboutus">
          
<div class="container">
            <div class="wp3" style="text-align:center;"> <h2>About Us</h2>
            <img src="img/circle-logo.png" style="width:100px;" />
            </div>
            
            <div class="row">
                <div class="col-md-4 intro-feature ">
                    <div class="intro-content">
                        
                        <p style="color:#FFFFFF">The iHappyU Team is started by five university students in Hong Kong who share the same ideal. It all started with a random dinner, during which we came across the issue of how stress and discontent fills the city.

Our ideal is simple. We hope that wherever the cards go, they carry positive messages coupled with love and happiness. 

iHappyU project is entirely coded, designed and ran by students. So no hate if there are bugs! Please let us know your valuable suggestions / comments!</p>
                    </div>
                </div>
                <div class="col-md-8 intro-feature">
                    <div class="intro-content">
                        
                        <p><h3 style="color:#FFFFFF">The iHappyU Team</h3></p>
                    <p>
<div class="row hidden-xs">
<div class="col-sm-4"><div class="circular" style="background-image: url('img/team/neil.jpg')"></div><div class="circular-text">Neil Chan<br>HKU<br>Co-founder (Executive)</div></div>
<div class="col-sm-4"><div class="circular" style="background-image: url('img/team/sumlik.jpg')"></div><div class="circular-text">Sum Lik Cheung<br>HKU<br>Co-founder (Operational)</div></div>
<div class="col-sm-4"><div class="circular" style="background-image: url('img/team/yal.jpg')"></div><div class="circular-text">Yalsin Li<br>HKU<br>Co-founder (Technology)</div></div>
</div>
<br>
<div class="row hidden-xs">
<div class="col-sm-3"><div class="circular" style="background-image: url('img/team/gary.jpg')"></div><div class="circular-text">Gary Fung<br>CUHK</div></div>

<div class="col-sm-3"><div class="circular" style="background-image: url('img/team/kenneth.jpg')"></div><div class="circular-text">Kenneth Lai<br>HKU</div></div>
<div class="col-sm-3"><div class="circular" style="background-image: url('img/team/tony.jpg')"></div><div class="circular-text">Tony Lam<br>UCLA</div></div>
<div class="col-sm-3"><div class="circular" style="background-color: rgba(255,255,255,0.3)"></div><div class="circular-text">Christine Huy<br>Advisor</div></div>
</div>
<div class="visible-xs"> <!--small team-->
<div class="row">
                <div class="col-md-12">
                    <div class="flexslider">
                        <ul class="slides">
                            <li>
                                <div><div class="circular" style="background-image: url('img/team/neil.jpg')"></div><div class="name-text">Neil Chan<br>HKU<br>Convener (Executive)</div></div>
                            </li>
                            <li>
                                <div><div class="circular" style="background-image: url('img/team/sumlik.jpg')"></div><div class="name-text">Sum Lik Cheung<br>HKU<br>Co-founder (Operational)</div></div>
                            </li>
                            <li>
                                <div><div class="circular" style="background-image: url('img/team/yal.jpg')"></div><div class="name-text">Yalsin Li<br>HKU<br>Co-founder (Technology)</div></div>
                            </li>
                            <li>
                                <div><div class="circular" style="background-image: url('img/team/gary.jpg')"></div><div class="name-text">Gary Fung<br>CUHK</div></div>
                            </li>
                            <li>
                                <div><div class="circular" style="background-image: url('img/team/joyce.jpg')"></div><div class="name-text">Joyce Mok<br>CUHK</div></div>
                            </li>
                            <li>
                                <div><div class="circular" style="background-image: url('img/team/kenneth.jpg')"></div><div class="name-text">Kenneth Lai<br>HKU</div></div>
                            </li>
                            <li>
                                <div><div class="circular" style="background-image: url('img/team/tony.jpg')"></div><div class="name-text">Tony Lam<br>UCLA</div></div>
                            </li>
                            <li>
                                <div><div class="circular" style="background-color: rgba(255,255,255,0.3)"></div><div class="name-text">Christine Huy<br>Advisor</div></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
</div>

</p>
                    </div>
                 </div>
            </div>
        </div>
        <!--<div class="device-showcase">
            <div class="devices">
                <div class="ipad-wrap wp1"></div>
                <div class="iphone-wrap wp2"></div>
            </div>
        </div>
        <div class="responsive-feature-img"><img src="img/devices.png" alt="responsive devices"></div>-->
        
    </section>
    
    <section class="hero-strip section-padding fs" id="vision">
        <div class="container">
            <div class="wp8" style="text-align:center;"> <h2 style="color: #000000;">Vision & Mission</h2>
            </div>
            
            <div class="row wp9">
                <div class="col-md-6 intro-feature">
                    <div class="intro-content">
                        <div style="display: table-cell"><img src="img/vision.png" style="width:80px;height:auto;"/></div>
                        <div style="display: table-cell; padding-left:20px;"><p style="color:#FFFFFF"><h5>Vision</h5>Feeling connected and being engaged in different relationships are the most powerful ways to make us happy. The iHappyU team hope to connect you to the right person, during which we build, strengthen and extend bonding between groups and individuals.
Promoting well-being and enhancing quality of life, we aim to initiate and thus the pass on of support, be it large or small efforts. Our belief is that happiness is a gift to everyone.</p>
                    </div></div>
                </div>
                <div class="col-md-6 intro-feature">
                    <div class="intro-content">
                        <div style="display: table-cell"><img src="img/mission.png" style="width:80px;height:auto;"/></div>
                        <div style="display: table-cell; padding-left:20px;"><p style="color:#FFFFFF"><h5>Mission</h5>Daily encounter of various difficulties, and being unable to find the most suitable support, inevitably add stress to our lives.
The iHappyU team hopes to support you by adopting a bottom-up approach. We first identify difficulties by listening and observing. By launching various projects, we then link you up to the most suitable person or group who offer the best support. We hope that our projects serve as simple and novel alternatives that facilitate bonding between you and many others so that you do not face the difficulties alone!
</p>
                    </div></div>
                 </div>
            </div>
        </div>
    </section>
    
    <section class="testimonial-slider section-padding text-center parallax">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="flexslider">
                        <ul class="slides">
                            <li>
                                <!--<div class="avatar"><img src="img/avatar.jpg" alt="Sedna Testimonial Avatar"></div>-->
                                <h2>Caring a person may not change the world,
but it may change the world of that person.</h2>
                                <!--<p class="author">Peter Finlan, Product Designer.</p>-->
                            </li>
                            <li>
                                <h2>Tell people that they matter.</h2>
                                
                            </li>
                            <li>
                                <h2>If I lay here,
If I just lay here,
Would you lie with me,
And just forget the world?</h2>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="sign-up section-padding text-center" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h3>Want to know more?</h3>
                        
                        <div class="intro-content">
                        <h5>Contact Us</h5>
                            <p><span class="glyphicon glyphicon-envelope"></span>  info@ihappyu.org</p>
                        </div>

                        <div class="intro-content">
                        <h5>Join our team</h5><p>
We are still at our infancy, and we desperately need more support! If you share the same ideal with us, and are positive, caring and passionate, please join us! 
In particular, we are looking for people interested in 
</p><p style="font-weight: 600;">
Graphics design<br>
Front-end / back-end (web/app) developer<br>
Marketing
</p>
<p>It doesn't matter if you are not experienced in the field - We welcome anyone who are passionate! Nonetheless, if you believe you have ideas or talents that iHappyU needs, please do not hesitate to contact us too :)</p>

                        </div>

                        <div class="intro-content">
                        <h5>Be our Community Outreachers!</h5><p>
Furthermore, we are constantly hoping to extend the happiness to more social groups and communities, including universities, secondary schools, churches and even workplace! Therefore, we are also looking for Community Outreachers who help us expand the reach of this campaign! As an Community Outreacher, you may request cards from us of various themes (e.g. Different statements that are specific to your social groups!). You may then circulate the cards in your community!
<br>
Therefore, please feel free to contact us via email if you find iHappyU meaningful and are enthusiastic on expanding the project!</p>

                        </div>

                        
                        
                    
                </div>
            </div>
        </div>
    </section>
    <section class="to-top">
        <div class="container">
            <div class="row">
                <div class="to-top-wrap">
                    <a href="#top" class="top"><i class="fa fa-angle-up"></i></a>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="footer-links">
                        <ul class="footer-group">
                            <li><a href="#">More contents coming soon!</a></li>
                        </ul>
                        <p>Copyright © 2016 <a href="#">iHappyU.org</a><br>
                        Acknowledgement to WeCare Fund, Centre for Suicide Research and Prevention, HKU for providing us the initial fund to make all of these possible.
                        </p>
                    </div>
                </div>
                <div class="social-share">
                    
                </div>
            </div>
        </div>
    </footer>
    <!--Message box-->
    <div class="msg" id="fixedmsg"></div>
    
    <script src="bower_components/retina.js/dist/retina.js"></script>
    <script src="js/jquery.fancybox.pack.js"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/scripts.js?ver=2"></script>
    <script src="js/jquery.flexslider-min.js"></script>
    <script src="bower_components/classie/classie.js"></script>
    <script src="bower_components/jquery-waypoints/lib/jquery.waypoints.min.js"></script>
    
</body>
</html>