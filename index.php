<?php 
include('header.php');
include_once("globals.php");
include "analytics.php";  // Update analytics
logGenuineAccess('synagen.net', 'Home');
?>

<div  class="container-fluid text-center bg-grey mx-auto" style="margin:20px;">
	<div class=" text-center mx-auto">
		<h1>Your Global ICT Consultancy Partner</h1>
		<h4>Improving your ICT efficiency and achieving your productivity goals.
		</h4>
	</div>
	<div class="row" style="margin:10px;">
		<div class="col-sm-4" >
	
			<div class="m-3 gradualHover content-center"  onClick="window.location.href='https://mybooking.clinic/patient-booking-system.php'">		
				<h4><i class="fas fa-calendar-alt"></i> MyBooking.Clinic</h4>
				<p style="text-align:left;">
				Streamline Your Practice with Modern Appointment Booking
				<ul>
				<li style="text-align:left;">Medical Centres.</li>
				<li style="text-align:left;">Therapists.</li>
				<li style="text-align:left;">Veternary Surgeons.</li>
				<li style="text-align:left;">Dentists.</li>
				<li style="text-align:left;">Personal Trainers.</li>
				</ul>
				</p>
			</div>
	
			<div class="m-3 gradualHover content-center"  onClick="window.location.href='https://calltaker.co?sp=landing'">		
				<!-- a href='https://calltaker.co?sp=landing' target="_blank">
				<div class="m-3 gradualHover content-center" -->
					<h4><img src="images/CallLog_Logo_150px.png" style=" max-width:60px;"> Calltaker.co CRM</h4>
					<p style="text-align:left;">
					Customer Service Centre - the complete service request and call centre management system for small or large companies. Delivers outstanding functionality for:
					<ul>
					<li style="text-align:left;">call centres,</li>
					<li style="text-align:left;">sales teams,</li>
					<li style="text-align:left;">service management,</li>
					<li style="text-align:left;">service catalogue</li>
					<li style="text-align:left;">public-facing online help and service request.</li>
					</ul>
					</p>
				<!-- /div -->
			</div>
			
			<div class="m-3 gradualHover content-center"  onClick="window.location.href='https://trabalo.net'">			  
				<h4><i class="fa fa-cogs" style="font-size:50px; color:rgb(192, 192, 192);"></i>Trabalo.net Recruitment portal</h4>
				<ul>
				<li style="text-align:left;">Cloud-based job advertising and recruitment management service. Takes the hard work out of placing job adverts and processing candidate responses.</li>
				<li style="text-align:left;">Available in English and Japanese</li>
				<li style="text-align:left;">Trabalo provides all the funcitonality you need to manage recruitment teams,</li>
				<li style="text-align:left;">manage and co-ordinate with job applicants,</li>
				<li style="text-align:left;">post job adverts.</li>
				<li style="text-align:left;">And it's 100% free to try for the first month.</li>
				</ul>
			</div>

		</div>

		<div class="col-sm-4" >
			<div class="m-3 gradualHover content-center" id="consultingDiv"  onClick="window.location.href='consulting.php'">
				<!-- i class="fa fa-handshake-o" style="font-size:50px; color:rgb(192, 192, 192);"></i -->
				<img src="images/business_partner_400x200px.jpg" border=0 style="margin:10px;">
				<h4>ICT Project Management and Consulting</h4>
				<p style="text-align:left;">Having issues increasing efficiency of your development team? Major system implementation coming?
				Need external, independent, ICT consulting and/or project management expertese? Things not going as well as they could with rolling out IT systems?</p>
				<p style="text-align:left;">We have 40 years experience in software development, business analysis, project management, problem solving and team leadership.</p>
				<p style="text-align:left;">Let us get your next, or current, major project on the right path and achieve amazing results.</p>
				<p style="text-align:left;">Operating globally, we will come to your office, anywhere in the world, work remotely, or a combination of both, as suits best.</p>
				
				<button class="btn btn-lg btn-info ">Click for more information</button>
			</div>
		</div>

		<div class="col-sm-4" >
			<div class="m-3 gradualHover content-center" onClick="window.location.href='support.php'">
				<i class="fa fa-shield" style="font-size:50px; color:rgb(192, 192, 192);"></i>
				<h4>Website support and Security Evaluation</h4>
				<p style="text-align:left;">Your websites need regular checkups, maintenance and support. How secure are your systems? We can provide one-off or on-going security support services. 
				We can provide regular support for your website backend, to ensure it is running smoothly, without problems and reduced risk of hacking. 
				<BR>Talk to us today to see just how we can help.</p>
			</div>
			<div class="m-3 gradualHover  content-center" id="cloudDiv"  onClick="window.location.href='cloud.php'">
				 <i class="fa fa-cloud-upload"  style="font-size:50px; color:rgb(192, 192, 192);"></i>
				 <h4>Cloud Migration</h4>
				 <p style="text-align:left;">Thinking of moving your in-house systems to cloud based? 
				 Synagen can design, manage and implement migration of your in-house systems to cloud services (AWS, Azure or Google). Our systems run entirely on Google Cloude Services.
				 Talk to us today to see just how we can help.</p>
			</div>
		</div>

	</div>
	<div class="row " style="margin:10px;">
		<div class="col-sm-4" >
			<div class="m-3 gradualHover content-center" >
				<a href="software.php" class="scroll"> <i class="fa fa-random" style="font-size:50px; color:rgb(192, 192, 192);"></i></a>
				<div onClick="window.location.href='development.php'">
					<h4>Software Development</h4>
					<p style="text-align:left;">Need custom software built or want to make modifications to an existing application? We will help you achieve your goals.</p>
					<p style="text-align:left;">Our services include project management, developer team leadership, risk management, change management, documentation, design and implementation, all at a fixed cost</p>
				</div>
			</div>
		</div>
		<div class="col-sm-4" >
		</div>
		<div class="col-sm-4" >
			<div class="m-3 gradualHover content-center" >
				<a href="software.php" class="scroll"> <i class="fa fa-random" style="font-size:50px; color:rgb(192, 192, 192);"></i></a>
				<div onClick="window.location.href='integration.php'">
					<h4>System Integration</h4>
					<p style="text-align:left;">Synagen can assist you with planning and implementing a major system integrating project. Services include project planning, team management, risk management, security analysis.
					</p>
					<p style="text-align:left;">Talk to us today to see just how we can help.</p>
				</div>
			</div>
		</div>
	</div>
	<br>
	<a href="contact.php"><button class="btn btn-lg btn-success slideanim">Contact us now to discuss your requirements</button></a>
	<br><br>
</div>

<div class="container-fluid text-center">
  <h2>WHY SYNAGEN</h2>
  <br>
  <div class="row star">
    <div class="col-sm-4">
     <i class="fa fa-line-chart" style="font-size:50px; color:rgb(192, 192, 192);"></i>
      <h4>QUALITY-DRIVEN RESULTS</h4>
      <p>Synagen Systems ensures your project flows smoothly and efficiently. 
         We employ stict quality-control throughout the process and will communicate with 
         you every step of the way throught the life of your project.</p>
    </div>
    <div class="col-sm-4">
      <i class="fa fa-diamond" style="font-size:50px; color:rgb(192, 192, 192);"></i>
      <h4>QUALITY PRODUCTS</h4>
      <p>Synagen Systems only create excellent products and our many years of experience with IT technology, business processes and project implementation methodologies puts us ahead of the rest. 
	  Our expert designers and engineers ensure that only quality fit-for-purpose products are implemented for our clients.</p>
    </div>
    <div class="col-sm-4">
     <i class="fa fa-cogs" style="font-size:50px; color:rgb(192, 192, 192);"></i>
      <h4>REAL SOLUTIONS</h4>
      <p>We are not tied down to legacy systems and we don't take commission for selling any brand. 
	  Instead, we use the very latest, but proven, technologies (such as Google Cloud) and provide you with real business solutions to real business problems.</p>
    </div>
  </div>
  <div class="row star">
    <div class="col-sm-4">  </div>
    <div class="col-sm-4">
      <i class="fa fa-globe" style="font-size:50px; color:rgb(192, 192, 192);"></i>
      <h4>GLOBAL and ONLINE</h4>
      <p>Synagen Systems is a global online business which means we save substantial amounts of money on unneccessary overheads and means that
	  we can then pass the savings on to you.</p>
    </div>
    <div class="col-sm-4">  </div>
  </div><br>
  <a href="contact.php"><button class="btn btn-lg btn-success slideanim">Get in touch</button></a>
  <br><br>
</div>
</center>



<?php 
include('footer.php');
?>