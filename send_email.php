<?php
require "/var/www/vendor/autoload.php"; // If you're using Composer (recommended)
use Google\Cloud\SecretManager\V1\Secret;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;
use Google\Cloud\SecretManager\V1\SecretPayload;

$client = new SecretManagerServiceClient();
$name = $client->secretVersionName(getenv('PROJECTID'), getenv('SENDGRID_SYNAGEN'), 'latest');
$response = $client->accessSecretVersion($name);
GLOBAL $sendGridAPIkey;
$sendGridAPIkey = $response->getPayload()->getData();

function send_mail($to, $body, $subject, $fromaddress, $fromname, $attachments=false)
{
	GLOBAL $sendGridAPIkey;
	$debugMode=false;
	$bugf=false;
	$debugLevel=0;
	
	$emailType="sendGrid";
	if ($debugMode===true && $bugf) fwrite($bugf, __FILE__. ", line ".__LINE__.", Vsn 2020-08-22 11:30  hostmachine=$hostmachine, \$to=$to, \$fromaddress=$fromaddress, fromname=$fromname, \$subject=$subject,  \r\n");

	if ($emailType=="sendGrid"){
		// Ths version for Google Cloud only
		require "/var/www/vendor/autoload.php"; // Sendgrid email library
		//if ($hostmachine=="PROD")require "/var/www/vendor/autoload.php"; // Sendgrid email library
		//else if ($hostmachine=="localhost"){ require 'sendgrid-php/lib/loader.php';}
		
		$errors=false;
		$message='';
				
		$newEmail = new \SendGrid\Mail\Mail(); 
		$newEmail->setFrom($fromaddress,'Calltaker');
		$newEmail->setSubject($subject);
		$newEmail->addTo($to,$to );
		// $newEmail->addTo('terrysmith56@hotmail.com', 'Terry Smith' );
		$newEmail->addContent("text/plain", str_replace('<BR>',"\r\n",str_replace('<br>',"\r\n",$body)) );
		$str = str_replace("\r\n", "\n", $body);
		$str = str_replace("\r", "\n", $str);
		$str = str_replace("\n", '<br>', $str);
		$newEmail->addContent("text/html",  $str);
		if ($debugMode===true && $bugf) fwrite($bugf, __FILE__. ", line ".__LINE__.", str=$str,\r\n");
		
		$sendgrid = new \SendGrid($sendGridAPIkey); // this code is our unique customer+domain ID
		try {
			$response = $sendgrid->send($newEmail);
			if ($debugMode===true && $bugf) fwrite($bugf, __FILE__. ", line ".__LINE__.", sendEmail(), Email sent \r\n");
			if ($debugMode===true && $bugf) fwrite($bugf, __FILE__. ", line ".__LINE__.", statusCode=". $response->statusCode() . "\r\n");
			if ($debugMode===true && $bugf) fwrite($bugf, __FILE__. ", line ".__LINE__.", headers=");
			foreach($response->headers() as $key=>$value)  if ($debugMode===true && $bugf)fwrite($bugf,"$key=$value, \r\n");
			if ($debugMode===true && $bugf) fwrite($bugf, __FILE__. ", line ".__LINE__.", body=". $response->body() . "\r\n");
		} catch (Exception $e) {
			if ($debugMode===true && $bugf) fwrite($bugf, __FILE__. ", line ".__LINE__.", Email send FAILED\r\n");
			$errors=true;
			$message=" Email send FAILED";
		}
		if ($errors)return(false);
		else return(true);
	} else {
		$eol="\r\n";
		$mime_boundary=md5(time());
		if ($attachments !== false)
		{
			if ($debugLevel>1 && $bugf) fwrite($bugf, __FILE__.", at line" . __LINE__. " , \r\n");
		
			foreach ($attachments as $myAtt)
			{
				if ($debugLevel>1 && $bugf) fwrite($bugf, __FILE__.", at line" . __LINE__. " , \$myAtt['tmp_name']=".$myAtt["tmp_name"]."\r\n");
				if (is_file($myAtt["tmp_name"]))
				{   
					// File for Attachment
					if ($debugLevel>1 && $bugf) fwrite($bugf, __FILE__.", at line" . __LINE__. " , \r\n");
			
					if (strrpos($myAtt["name"], "/") === false) {
						$file_name = $myAtt["name"];
					} else {
						$file_name = substr($myAtt["name"], (strrpos($myAtt["name"], "/")+1));
					}
					
					$file_name = $myAtt["name"];
					if ($debugLevel>1 && $bugf) fwrite($bugf, __FILE__.", at line" . __LINE__. " , file_name=$file_name\r\n");
					
					$handle=fopen($myAtt["tmp_name"], 'rb');
					$f_contents=fread($handle, filesize($myAtt["tmp_name"]));
					$f_contents=chunk_explode(base64_encode($f_contents));    //Encode The Data For Transition using base64_encode();
					$f_type=filetype($myAtt["tmp_name"]);
					fclose($handle);
					
					# Attachment
					// For mime types see: http://www.iana.org/assignments/media-types/application/
					// or : http://www.utoronto.ca/web/HTMLdocs/Book/Book-3ed/appb/mimetype.html
					
					// Try to work out an attachment content-type, based on file extension, eg: "application/msword"
					$extn='';
					$pos=strrpos($file_name, ".");
					$extn=strtolower(trim(substr($file_name,$pos)) );
					$content=$myAtt["content_type"];
					if ($extn=='doc') $content='application/msword';
					elseif ($extn=='pdf') $content='application/pdf';
					elseif ($extn=='rtf') $content='application/rtf';
					elseif ($extn=='jpg') $content='image/jpeg';
					elseif ($extn=='jpeg') $content='image/jpeg';
					elseif ($extn=='png') $content='image/png';
					elseif ($extn=='gif') $content='image/gif';
					elseif ($extn=='tiff') $content='image/tiff';
					
					$msg .= "--".$mime_boundary.$eol;
					$msg .= "Content-Type: ".$content."; name=\"".$file_name."\"".$eol;  // sometimes i have to send MS Word, use 'msword' instead of 'pdf'
					$msg .= "Content-Transfer-Encoding: base64".$eol;
					$msg .= "Content-Description: ".$file_name.$eol;
					$msg .= "Content-Disposition: attachment; filename=\"".$file_name."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
					$msg .= $f_contents.$eol.$eol;
					if ($debugLevel>1 && $bugf) fwrite($bugf, __FILE__.", at line" . __LINE__. " , msg=$msg\r\n");

				} else {
					if ($debugLevel>1 && $bugf) fwrite($bugf, __FILE__.", at line" . __LINE__. " , \$myAtt['tmp_name']=".$myAtt["tmp_name"]." IS NOT A FILE!! \r\n");
					
				}
			}
		}

		// Finished
		$msg .= "--".$mime_boundary."--".$eol.$eol;  // finish with two eol's for better security. see Injection.
		
		// SEND THE EMAIL
		ini_set('sendmail_from',$fromaddress);  // the INI lines are to force the From Address to be used !
		$mail_sent = @mail($to, $subject, $msg, $myHeaders);
		ini_restore('sendmail_from');
		
		return $mail_sent;
	}
}
?>