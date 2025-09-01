<?php 
$subtitle="Website support enquiry";
include('header.php');

$message='';
$error=false;
$email       ='';
$first_name ='';
$last_name  ='';
$comment    ='';
$Subject    ='';
$x='';
$x1='';
$iv='';
$fout=false;
$level='';
$action='';
include("setSpamCheck.php");
if (isset($_POST['action']))  $action =stripslashes(urldecode($_POST['action']));
if (isset($_GET['level']))  $level =stripslashes(urldecode($_GET['level']));
if ($level !="gold" && $level!="silver" && $level !="bronze")$level='';
if($action !='Submit')$action='';

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
	    $email      = trim(stripslashes(urldecode($_POST['email']))); // this is the sender's Email address
	    $first_name = trim(stripslashes(urldecode($_POST['first_name'])));
		$name       = addslashes($first_name);
	    $comment    = trim(stripslashes(urldecode($_POST['comment'])));
	    $level      = trim(stripslashes(urldecode($_POST['level'])));
	
	    /************************************************************************************/
	    /** Send an email to me with all details submitted, including any file attachments **/
	    /************************************************************************************/
	
		if (!isset($email) || (strlen($email)==0 )) {
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
    $subject2 = "Copy of your form submission to Synagen Systems";

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
		} else fwrite($fout,__FILE__." from=$email, name=$first_name,  message=$message <BR>\r\n");
	}
	if (!is_file("/var/www/vendor/autoload.php")){
		$error=true;
		if($fout)fwrite($fout,__FILE__." FATAL ERROR! autoload.php not found! <BR>\r\n");
		$message="Sorry. There was a technical problem sending your message. Error SC049";
	}
	if ($error===false) {
	    if (isset($fout)) fwrite($fout,  __FILE__.", at line" . __LINE__. ", email=$email, \r\n");
		$subject="Contact through Synagen web site regarding customer website support services";
	
		require "/var/www/vendor/autoload.php"; // If you're using Composer (recommended)

		$projectId = getenv('PROJECTID');
		$secretId = getenv('SENDGRID_SYNAGEN_KEY'); 
		$client = new SecretManagerServiceClient();
		$name = $client->secretVersionName($projectId, $secretId, 'latest');
		$response = $client->accessSecretVersion($name);
		$sendGridAPIkey = $response->getPayload()->getData();
		$sendgrid = new \SendGrid($sendGridAPIkey); 

		$EmailMsg= " $subject<BR>
		Email:$email<BR>
		Support Level: $level <BR>
		$first_name wrote the following:<BR>$comment";
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
		This email confirms your contact with the team at synagen.net regarding support for your website.
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
		$email2->setSubject($subject);
		$email2->addTo($email, $first_name);
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
  
<div class="container-fluid bg-grey text-center" style="width:600px;">


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

	<div id="top" class="text-center star">
		<?php if (strlen($message)>0)echo '<p class="errormessage">'.$message.'</p>'; ?>
	</div>

	<form class="form-horizontal" name=form method="post" action="getsupport.php">
	  <input type="hidden" id="btnAction" name="action" value="Submit">
	  <input type='hidden' id='x' name='x' value='<?php  echo $x;  ?>'>
	  <input type='hidden' id='x1' name='x1' value='<?php  echo $x1;  ?>'>
	  <input type='hidden' id='v2' name='v2' value='<?php  echo addslashes(urlencode($iv));  ?>'>
	  <input type='hidden' id='level' name='level' value='<?php  echo $level;  ?>'>
	  
	  <p>
	  <?php if (strlen($level)>0) echo "<BR>You are enquiring about $level level website support.";  ?>
	  </p>
	  
	  <div class="form-group">
	  <input type="text"  class="form-control" id="first_name" name="first_name" size="50" maxlength="100"  placeholder="Your name">
	  </div>
	  
	  <div class="form-group">
	  <input type="email" class="form-control" id="email"      name="email"      size="50" maxlength="100"   placeholder="Your email address">
	  </div>

	  
	  <div class="form-group">
	  <input type="text"  class="form-control" id="company" name="company" size="50" maxlength="100"  placeholder="Your company name">
	  </div>
	  
	  <div class="form-group">
	  <input type="text"  class="form-control" id="website" name="website" size="50" maxlength="100"  placeholder="Your website URL that you need supported">
	  </div>
	 
	  <div class="form-group">
	  <textarea class="form-control" rows="10" cols="100" id="comment" name="comment" placeholder="Comments. Please detail any special needs."></textarea>
	  </div>
	  
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
	
<script  language=JavaScript> 
document.getElementById("contactdets").innerHTML="Email:admin"+"@synagen.net &nbsp; Ph:+61-"+"406296373<BR>"; 
</script>
	
</div>
  <BR><BR>

<?php 
include('footer.php');
?>