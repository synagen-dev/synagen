<?php 
$subtitle='Professional, Quality And Cost Effective Website Development';
include('header.php');

$message='';
$error=false;
$from       ='';
$first_name ='';
$last_name  ='';
$comment    ='';
$Subject    ='';
$x='';
$x1='';
$iv='';
$fout=false;
include("setSpamCheck.php");
if (isset($_POST['action']))  $action =stripslashes(urldecode($_POST['action']));

if(isset($_POST['action']) && $_POST['action']=='Submit'){
	$v1='';
	$v2='';
	$x1='';
	$spamcheck='';
	if (isset($_GET['oid']))              $oid            =stripslashes(urldecode($_GET['oid']));
	if (isset($_POST['v1']))              $v1             =stripslashes(urldecode($_POST['v1']));
	if (isset($_POST['v2']))              $v2             =stripslashes(urldecode($_POST['v2']));
	if (isset($_POST['x1']))              $x1             =stripslashes($_POST['x1']);
	if (isset($_POST['x']))               $x              =stripslashes($_POST['x']);
	if (isset($_POST['spamcheck']))       $spamcheck      =stripslashes(urldecode($_POST['spamcheck']));
    // Anti-spam check. This does the revers of setSpamCheck.php
	$plaintext = openssl_decrypt($x1, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $v2);
	$data2=$code.'|'.$myday.'|'.session_id(); 
	if ($data!=$data2 ) {
		$message="You entered the anti-spam code incorrectly. Please try again.";
		$error=true;
	}
	if ($v1!=$spamcheck) {
		$message="You entered the anti-spam code incorrectly. Please try again.";
		$error=true;
	}

	if ($error===false) {
	    $from       = trim(stripslashes(urldecode($_POST['email']))); // this is the sender's Email address
	    $first_name = trim(stripslashes(urldecode($_POST['first_name'])));
		$name       = addslashes($first_name);
	    $comment    = trim(stripslashes(urldecode($_POST['comment'])));
	
	    /************************************************************************************/
	    /** Send an email to me with all details submitted, including any file attachments **/
	    /************************************************************************************/
	
		if (!isset($from) || (strlen($from)==0 )) {
			$message="Please enter a valid email address";
			$error=true;
		}
		if (!isset($first_name) || (strlen($first_name)==0 )) {
			$message="Please enter your name.";
			$error=true;
		}	
		if (!isset($comment) || (strlen($comment)==0 )) {
			$message="Please enter your question or comment.";
			$error=true;
		}    
	}    
    $subject1 = "Contact Form submission from Synagen.net";
    $subject2 = "Copy of your form submission to SYnagen Systems";

	if ($error===false) {
	
	    /************************************************************************************/
	    /** Send an email to me with all details submitted, including any file attachments **/
	    /************************************************************************************/
	   	$comment=str_replace("\r\n",'<BR>',$comment);
		$comment=str_replace("\n",'<BR>',$comment);
		$comment=str_replace("\r",'<BR>',$comment);

		$fout=fopen("contact.log",'w+');
		if(!$fout){
			$error=true;
		} else fwrite($fout,__FILE__." from=$from, name=$first_name, $last_name,  message=$message <BR>\r\n");
	}
	if (!is_file("/var/www/vendor/autoload.php")){
		$error=true;
		if($fout)fwrite($fout,__FILE__." FATAL ERROR! autoload.php not found! <BR>\r\n");
		$message="Sorry. There was a technical problem sending your message. Error SC049";
	}
	if ($error===false) {
	    if (isset($fout)) fwrite($fout,  __FILE__.", at line" . __LINE__. ", email=$from, \r\n");
		$subject="Contact from Synagen web site";
	
		require "/var/www/vendor/autoload.php"; 

		$projectId = getenv('PROJECTID');
		$secretId = getenv('SENDGRID_SYNAGEN'); 
		$client = new SecretManagerServiceClient();
		$name = $client->secretVersionName($projectId, $secretId, 'latest');
		$response = $client->accessSecretVersion($name);
		$sendGridAPIkey = $response->getPayload()->getData();
		$sendgrid = new \SendGrid($sendGridAPIkey); 

		$EmailMsg= "Contact From: $from via Synagen web site.<BR>Subject: $Subject<BR>$first_name $last_name wrote the following:<BR>$comment";
		fwrite($fout, __FILE__. ", ".__LINE__.",  \$EmailMsg=$EmailMsg,\r\n");
		
		$newemail = new SendGrid\Mail\Mail(); // Create an email object
		$newemail->setFrom('admin@synagen.net');    
		$newemail->setSubject($subject1);
		$newemail->addTo('admin@synagen.net', 'Synagen');
		$newemail->addTo('terrysmith56@hotmail.com', 'Terry Smith' );
		$newemail->addContent("text/plain", str_replace('<BR>',"\r\n",str_replace('<br>',"\r\n",$EmailMsg)) );
		$newemail->addContent("text/html", $EmailMsg);
		// Open the email API connection
		try {
			$response = $sendgrid->send($newemail);
			fwrite($fout, __FILE__. ", line ".__LINE__.", sendEmail(), Email sent ok\r\n");
		} catch (Exception $e) {
			fwrite($fout, __FILE__. ", line ".__LINE__.", Email send FAILED\r\n");
			$error=true;
			$message="Message send FAILED due to technical error";
		}
		if (!$error){
			$message="Message sent ok";
			fwrite($fout,  __FILE__.", at line" . __LINE__. ", After sending email -  message=$message\r\n");		
		}
	
		// Now send confirmation back to user
		$EmailMsg='Hello '.$first_name.'
		<BR><BR>
		This email confirms your contact with the team at synagen.net.
		<BR><BR>You will be contacted as soon as possible.
		<BR><BR>
		Thank you for your enquiry.<BR><BR>
		Terry Smith,
		CEO,<BR>
		Synagen Systems Ltd<BR>
		<a href="https://synagen.net">https://synagen.net</a>
		';

		$email2 = new SendGrid\Mail\Mail(); // Create an email object
		$email2->setFrom('admin@synagen.net');    
		$email2->setSubject($subject2);
		$email2->addTo($from, $first_name);
		$email2->addContent("text/plain", str_replace('<BR>',"\r\n",str_replace('<br>',"\r\n",$EmailMsg)) );
		$email2->addContent("text/html", $EmailMsg);
		$message='';
		try {
			$response = $sendgrid->send($email2);
			fwrite($fout, __FILE__. ", line ".__LINE__.", Email2 sent ok\r\n");
			$message="Message received. You will be contacted shortly. Thank you.";
		} catch (Exception $e) {
			fwrite($fout, __FILE__. ", line ".__LINE__.", Email2 send FAILED\r\n");
			$error=true;
			$message="Message send FAILED due to technical error";
		}
		fwrite($fout,  __FILE__.", at line" . __LINE__. ", After sending email2 -  message=$message\r\n");		
    }
}
?>

<SCRIPT language=JavaScript>
function ValidateItem(item, name, validate_mail, validate_phone)
{
  if (document.form.elements[item]) {
    if (document.form.elements[item].value.length < 1)
    {  alert("You must enter a value for " + name);
      document.form.elements[item].focus();
      return false;
    }
    if (validate_mail)
    {
      var emailFilter=/^.+@.+\..{2,3}$/;
      var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/;
      var strng = document.form.elements[item].value;
      if (!(emailFilter.test(strng)))
      { alert("You have entered an invalid email address.");
        document.form.elements[item].focus();
        return false;
      }
      if (strng.match(illegalChars))
      { alert("Your email address contains invalid characters.");
        document.form.elements[item].focus();
        return false;
      }
    }
  }
  return true;
}
function ValidateForm()
{
    if (!ValidateItem("first_name", "Your First Name",      false )) return false;
    if (!ValidateItem("email",      "Email Address",        true  )) return false;
    if (!ValidateItem("comment",    "Your Comment/Enquiry", false )) return false;
    if (!ValidateItem("spamcheck",  "Anti-Spam Check",      false )) return false;
    return true;
}

</script>
<div id="consulting" class="container-fluid text-center bg-grey" style="margin:20px;" >
	<div class=" text-center">
		
		<p class="star">You know you need a website, but why? who is the target audience? What do you want to convey? What do you want to acheive from it?</p>
		<div >By getting to know your business needs and goals, we can begin with an educated discussion of what you want to acheive with your new website. </div>
		<?php if (strlen($message)>0)echo '<p class="errormessage">'.$message.'</p>'; ?>
	</div>
	<div class="row ">
	   <div class="col-sm-3" ></div>
	   <div class="col-sm-6 text-left" style="color:#353535 !important;">
	   
			<form class="form-horizontal" name=form method="post" action="questionnaire.php">
			<input type="hidden" id="btnAction" name="action" value="Submit">
			<input type='hidden' id='x' name='x' value='<?php  echo $x;  ?>'>
			<input type='hidden' id='x1' name='x1' value='<?php  echo $x1;  ?>'>
			<input type='hidden' id='v2' name='v2' value='<?php  echo addslashes(urlencode($iv));  ?>'>
			<BR>
			<h4>1. Tell us about your company.</h4>
			<div class="form-group">
				<input type="text"  class="form-control" id="first_name" name="phone"      size="50" maxlength="100"  placeholder="Your name">
				<input type="text"  class="form-control" id="phone"      name="first_name" size="50" maxlength="100"  placeholder="Phone number (including country and area code)">
				<input type="email" class="form-control" id="email"      name="email"      size="50" maxlength="100"  placeholder="Your email address">
				<input type="text"  class="form-control" id="org_name"   name="org_name" size="50" maxlength="100"  placeholder="Your organisation/company name">
				<textarea class="form-control" rows="4" cols="100" id="address" name="address" placeholder="Your business address"></textarea>
				<textarea class="form-control" rows="4" cols="100" id="vision" name="vision" placeholder="Your mission statement/vision/goals"></textarea>
			</div>
			
			<h4>2. What specific services does your company provide?</h4>
			The answers to this question can help us understand what design elements and keywords to focus on.		
			<div class="form-group">
				<textarea class="form-control" rows="4" cols="100" id="services" name="services" ></textarea>
			</div>
			<h4>3. What sets your company apart from your competition?</h4>
			This will tell us your unique selling proposition (USP), unique value proposition (UVP), competitive advantage, or strengths so you can show their potential clients why they should choose them.
			<div class="form-group">
				<textarea class="form-control" rows="4" cols="100" id="services" name="services" ></textarea>
			</div>

			<h4>4. Who is your target client?</h4>
			This should include all applicable demographics, such as age, location, gender, education, occupations, etc. Understanding the client’s target audience will give you insight into what design elements — such as colors, images and fonts — to use on their website.
			<div class="form-group">
				<textarea class="form-control" rows="4" cols="100" id="services" name="services" ></textarea>
			</div>

			<h4>5. Do you currently have a website?</h4>
			We will need to assess the site and compare it to the company’s goals to see if this will be a tweak or a complete rebuild.
			<div class="form-group">
				<input type="text"  class="form-control" id="website"      name="website" size="50" maxlength="100"  placeholder="URL of existing website">
			</div>

			<h4>6. What keywords will your audience use to find your website?</h4>
			The answer to this question will show what keywords the client’s site currently ranks for (if any) and what they want to rank for. 
			We will research which keywords are the better choices, but knowing your target keywords will help us understand the audience and genre.
			<div class="form-group">
				<textarea class="form-control" rows="4" cols="100" id="services" name="services" ></textarea>
			</div>

			<h4>7. What do you like about your website?</h4>
			The response to this question will help us understand the website elements that mean the most to you. 
			Defining the new website’s purpose, understanding its current weaknesses, and creating a detailed feature list will help us build a solid foundation for a successful project.
			<div class="form-group">
				<textarea class="form-control" rows="4" cols="100" id="like" name="like" ></textarea>
			</div>

			<h4>8. Why do you want a new website?</h4>
			Like the preceding question on the web design client questionnaire, this question helps us understand the weaknesses of the current site and see what’s not working for you. 
			It will help us understand the new website’s purpose. It could be that the site just needs a new feature added or a different theme, or it might need to be built on a new platform with a different layout and features.
			<div class="form-group">
				<textarea class="form-control" rows="4" cols="100" id="whywant" name="whywant" ></textarea>
			</div>

			<h4>9. What features will your website need?</h4>
			This answer needs to be as detailed as possible. Features include:

			Forms
			Maps
			Social media buttons
			Click-to-call buttons
			Online ordering / eCommerce
			Search
			Portfolio / gallery
			Pricing tables
			Calls-to-action
			Forum
			…etc.
			Again, encourage the client to consider their audience and the goals for the website when coming up with the list of necessary features.
			<div class="form-group">
				<textarea class="form-control" rows="4" cols="100" id="features" name="features" ></textarea>
			</div>

			<h4>10. What similar websites do you like and what is it you like about them?</h4>
			This will show you what styles they like and provide examples of features that might be difficult to describe. It can be especially helpful for the client to point out features they like on competitor sites.
			<BR>

			<h4>11. Who will provide content for the new website?</h4>
			Not all clients understand what you mean by “content,” so start out by explaining what website content includes — website copy, graphics, images, logo, fonts, etc.
			<BR>

			<h4>12. Does your company have established branding?</h4>
			Branding includes materials that the website will need to match, including colors, fonts, business cards, newsletters, flyers, logos, signs, etc. If the client hasn’t established their brand, here’s a fun quiz to get them started.
			<BR>

			<h4>13. Do you need a new URL?</h4>
			Depending on the client’s technical sophistication, you might first need to explain what a URL is and how it’s related to the website (i.e., it’s the website’s address). If the client does need a new URL, who will be responsible for securing the domain? Maybe they will need your advice.
			<BR>

			<h4>14. Do you need hosting?</h4>
			Again, you might need to explain the concept of web hosting and what to look for in a web hosting provider. If the client does need hosting, will you provide this service? If you don’t provide hosting at all or the type of hosting that the client requires, they need your recommendations.
			<BR>

			<h4>15. Will the old site be moved to a new location?</h4>
			Migrations can add a lot of time and cost to the project. Be sure to set the right expectations.
			<BR>

			<h4>16. What is the deadline for the website?</h4>
			You can use this information on the web design client questionnaire to determine if the client’s needs can be met by the deadline. You might need to provide a timeline to show what can be done by the deadline and what can be added later and when.
			<BR>

			<h4>17. What is the budget for the website?</h4>
			Defining a budget will let you know if you can meet the goals of this project.
			<BR>

			Explore opportunities for ongoing work
			The web design project is just the starting point of a lasting relationship. You should consider ongoing opportunities. If you don’t provide these services, maybe you could partner with someone so you can recommend each other.

			Adjust the following questions according to the services you offer. This will also show the client that these services are provided at an extra cost and help them adjust their budget accordingly.
			<BR>

			<h4>18. Do you want us to handle maintenance?</h4>
			This question shows that ongoing website maintenance is not part of the website design project and that it will be an additional cost they will have to budget for. A website maintenance plan can include updates for themes and plugins, changing themes, adding new features through plugins, as well as ongoing changes such as images, prices, backups, etc.

			If you provide this service, you could supply the client with different pricing options based on the services they want.
			<BR>

			<h4>19. Would you like us to handle content marketing?</h4>
			The client needs to understand that creating the site doesn’t guarantee traffic and that traffic is not your obligation unless they want to pay for this as a service. Content marketing and promotion can include SEO, local SEO, social media, newsletters, ad campaigns, articles, etc.
			<BR><BR>
			<div class="form-group text-left">
				<label class="control-label" style="padding:0px;" for="sel1">Spammer Check:</label>
				<input  id="control-label" type="TEXT" name="spamcheck" size=10 maxlen=10>
				<BR>
				To prevent automated spammers using this form, please enter the following string of letters and numbers into the field above:
				<input  type="TEXT" name="v1" id='v1' value='<?php  echo $code;  ?>'readonly=true size="8" maxlen="6" style="font:verdana; font-style:italic;">
			</div>

			<input type="submit" class="btn btn-lg btn-primary" onclick="return ValidateForm();" name="submit" value="Submit">

			</form>

		</div>
		<div class="col-sm-3" ></div>
	</div>
</div>

<?php 
include('footer.php');
?>