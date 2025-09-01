<?php 

// Creates a random number along with the number plus todays date and current session ID, encrypted, 
//include_once("globals.php");

// date_default_timezone_set('Australia/Sydney');
// $script_tz = date_default_timezone_get();
$myday=date("Y-m-d");
$letters=array("2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","j","k","m","p","r","t","u","w","x","y");
$l=array_rand($letters,4);
$code = $letters[$l[0]].$letters[$l[1]].$letters[$l[2]].$letters[$l[3]];
$key='xgMerdfy#1x67%';
$k2 = substr(sha1($key, true), 0, 16);
$iv = openssl_random_pseudo_bytes(16);
//if ($debugLevel>2 && isset($bugf)) fwrite($bugf,  __FILE__. ", line ".__LINE__.", iv=$iv,\r\n");
$data=$code.'|'.$myday.'|'.session_id(); 
//if ($debugLevel>2 && isset($bugf)) fwrite($bugf,  __FILE__. ", line ".__LINE__.", code, today, session_id=$data,\r\n");
$options=0;
$encrypt = openssl_encrypt($data, 'AES-128-CBC', $k2, OPENSSL_RAW_DATA, $iv);
//if ($debugLevel>2 && isset($bugf)) fwrite($bugf,  __FILE__. ", line ".__LINE__.", encrypt=$encrypt,\r\n");

$x1=addslashes(urlencode($encrypt));
	
//if ($debugLevel>2 && isset($bugf)) fwrite($bugf,  __FILE__. ", line ".__LINE__.", x1=$x1,\r\n");

 ?>