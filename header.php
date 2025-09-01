<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="shortcut icon" href="images/carcloud_icon.png" type="image/x-icon">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="title" value="Global IT consulting services">
  <meta name="description" value="Global ICT Consulting services">
  <meta name="keywords" value="IT consulting,ICT consulting,IT consultant,ICT consultant,IT consulting services,project management,IT development standards,risk management,business analysis,IT,ICT,computer consutlant in japan,IT consultants in japan,computer,software,computer systems,Japan,日本" >
<?php
date_default_timezone_set('Australia/Sydney');
$script_tz = date_default_timezone_get();
$today=date('Y-m-d H:i:s');
$todayDate=date('d/m/Y');
$todayDateSQL=date('Y-m-d');
$today_us=date('m/d/Y');
$notify_email     = 'terrysmith56@hotmail.com';     //email address to which debug emails are sent to
$sender_email     = 'admin@synagen.net';    //email address to which debug emails are sent to
?>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 
	<link href="style_default.css" rel="stylesheet" type="text/css">

  <script src="Script.js"></script>

<script>
  var chatid = 'synagen';
</script>
<script src="https://synagen.net/chatbot/chat.js"></script>

<script>
$(document).ready(function(){  
	<?php 
	if (isset($subtitle) && strlen($subtitle)>0) echo "document.getElementById('subheader1').innerHTML='$subtitle'; 
	document.getElementById('subheader2').innerHTML='$subtitle'; 
	";
	?>
     
	// Makes the nav bar transparent
    var scroll_pos = 0;
    $(document).scroll(function() { 
        scroll_pos = $(this).scrollTop();
        if(scroll_pos > 500 || scroll_pos<100) {
            $(".navbar").css('background-color', 'black');
        } else {
            $(".navbar").css('background-color', 'transparent');
        }
    });
});
</script>
 	
</head>
<body  data-spy="scroll" data-target=".navbar" data-offset="100">

<nav class="navbar  navbar-inverse fixed-top navbar-expand-md bg-dark navbar-dark ">

	<button class="navbar-toggler" style="color:black" type="button" data-toggle="collapse" data-target="#myPage">
	<span class="navbar-toggler-icon"></span>
	</button>

    <div class="collapse navbar-collapse" id="myPage" >
      <ul class="nav navbar-nav navbar-right ">
		<li class="nav-item "><a class="nav-link" href="index.php">HOME</a></li>
		<li class="nav-item "><a class="nav-link" href="about.php">About Us</a></li>
        <li class="nav-item "><a class="nav-link" href="software.php">Our Software</a></li>
        <li class="nav-item "><a class="nav-link" href="consulting.php">Consulting</a></li>
		<li class="nav-item "><a class="nav-link" href="development.php">Software Development</a></li>
      </ul>
    </div>
		<a href="contact.php" ><button class="btn btn-info navbar-btn hidden-sm hidden-md" style="float:right; margin-right:20px;">CONTACT</button></a>
</nav>

<center>
<!-- Jumbotron Header -->

<div id="top" class="jumbotron text-center star" style="height:500px; background-color:#c0d0e0; background-image:url('images/splash1_1950x700px_b.jpg'); background-position:center;">
  <img style="padding-top:50px; max-height:400px; max-width:50%;" src="images/splash1_1950x700px_b.jpg" >
  <div id="header1" >Synagen Systems</div>
  <div id="header2" >Synagen Systems</div>
  <!-- img src="images/globe_100px_trans2.png" style="width:50px; position:relative; top:-390px; left:-268px; z-index:1002;" border=0 --> 
  <div id="subheader1">&nbsp; &nbsp;</div>
  <div id="subheader2">&nbsp; &nbsp;</div>
</div>
