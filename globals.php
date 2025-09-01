<?php
/*********************************************************************************************************************
Program: globals.php
Desc:   Define GLOBAL variables used by most programs

*********************************************************************************************************************/
$gvsn="2025-06-06 14:05";
GLOBAL $hostmachine;
$hostmachine="notSET";
GLOBAL $debugMode;

include_once '_DONOT_UPLOAD_ME.php';  // Sets $hostmachine, base_dir, debugMode

$debugMode=false;
// $debugLevel: 0=none, 1=log on/off & program execution, 2=SQL, 3=cookies, session vars & run variables & parameters
GLOBAL $debugLevel;
$debugLevel=0; // Override with value from organisations.orgDebugLevel
GLOBAL $glog;
$glog=FALSE;

if($debugMode) {
	$glog=fopen("$debugMode/debug.log", "a");
	if(!$glog) {echo "FATAL ERROR G101"; exit(0);}
}

if($debugMode && $glog)fwrite($glog, __FILE__." line ".__LINE__." ========================== ". $_SERVER['PHP_SELF'] . " START OF booking/globals.php  ============================ Version=$gvsn.\r\n");

if($glog && $debugLevel>2) {
	fwrite($glog, " \$_SERVER['SCRIPT_FILENAME']=".$_SERVER['SCRIPT_FILENAME'].", \$_SERVER['SCRIPT_NAME']=".$_SERVER['SCRIPT_NAME'].", \r\n");
}

if($debugMode && $glog)
	if(isset($_SESSION)) fwrite($glog, __FILE__." line ".__LINE__." _SESSION[]:". print_r($_SESSION, true)."\r\n");
	else				 fwrite($glog, __FILE__." line ".__LINE__." _SESSION[] is NOT SET, \r\n");
		

// stupid bots
if (isset($_SERVER["REQUEST_URI"]) && strpos($_SERVER["REQUEST_URI"],'.php/index.php')!==false ) {sleep(60); echo '404'; exit(0);}
if (isset($_SERVER["REQUEST_URI"]) && strpos($_SERVER["REQUEST_URI"],'index.php/documents/documents')!==false ) {sleep(60); echo '404'; exit(0);}
// Remove any javascript embedded in parameters
if (isset($_SERVER["REQUEST_URI"]) && strpos(strtolower($_SERVER["REQUEST_URI"]),'javascript' ) !==false ) {sleep(60);  echo '404'; exit(0);}
if (isset($_SERVER["REQUEST_URI"]) && strpos(strtolower($_SERVER["REQUEST_URI"]), '//' ) !==false )        {sleep(60);  echo '404'; exit(0);}
if (isset($_SERVER["REQUEST_URI"]) && strpos(strtolower($_SERVER["REQUEST_URI"]), '/^' ) !==false )        {sleep(60);  echo '404'; exit(0);}

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

GLOBAL $currURL;  // Full domain name including http(s)://
$currURL=curPageURL();
GLOBAL $http;   // Use this if "http://" is specifically needed in a link
GLOBAL $https;  // Use this if "https://" is specifically needed in a link
GLOBAL $base_url;  // server name, without "http://", eg "localhost"
if (isset($_SERVER['HTTP_HOST']) && strlen($_SERVER['HTTP_HOST'])>0) $base_url=$_SERVER['HTTP_HOST'];
else  $base_url=$_SERVER['SERVER_NAME'];
if(@$_SERVER['CONTEXT_PREFIX'])$base_url.=$_SERVER['CONTEXT_PREFIX'];

// strip off "http://"
GLOBAL $domain;
if (strpos($base_url,'//')===false) $domain=$base_url;
else $domain=substr($base_url, strpos($base_url,'//')+1);

GLOBAL $autoload;
if($debugMode && $glog)fwrite($glog, "globals.php line ".__LINE__.", \$domain=$domain, \r\n");

// Function to sanitize input data
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)));
}

function curPageURL() {
 $pageURL = 'http';
 if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if (@$_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
GLOBAL $country;
GLOBAL $city;
$country="Unknown";
$city="Unknown";
// Get visitor's IP address
$ip = $_SERVER['REMOTE_ADDR'];
// If site is behind a proxy or load balancer (common with cloud platforms)
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
$geolocationData = file_get_contents("http://ip-api.com/json/$ip");
$locationInfo = json_decode($geolocationData, true);
if ($locationInfo && $locationInfo['status'] == 'success') {
    $country = $locationInfo['country'];
    $city = $locationInfo['city'];
} 

if ($hostmachine=="localDEV"){
	$autoload='../vendor/autoload.php';
	require($autoload);
	if (strtolower(substr($base_url,0,5))=='https') {
		$http='http://'.substr($base_url,8); 
		$https=$http;  // because we don't have https in personal computer
	} else {
		$http=$base_url;
		$https=$base_url;
	}
	$_SERVER['SERVER_PORT']='80';

} else {
	// Cloud server
	$autoload= "/var/www/vendor/autoload.php"; 
	require($autoload);
	
	if (strtolower(substr($base_url,0,5))=='https') {
		$http='http://'.substr($base_url,8); 
		$https=$base_url; 
	} else {
		$http=$base_url;
		$https='https://'.substr($base_url,7); 
	}
}
$http=str_replace(":443","",$http);

if($debugMode && $glog)fwrite($glog, "globals.php line ".__LINE__.", \$http=$http, \r\n \$https=$https, \r\n");

use Google\Cloud\SecretManager\V1\Client\SecretManagerServiceClient;
use Google\Cloud\SecretManager\V1\AccessSecretVersionRequest;

// Function to safely get a secret value
function getSecretValue($projectId, $secretId) {
    GLOBAL $glog;
	GLOBAL $debugMode;

    if (empty($projectId) || empty($secretId)) {
        if ($glog) fwrite($glog, "ERROR: Missing projectId or secretId\r\n");
        return "";
    }
    
    try {
        // Create client
        $client = new SecretManagerServiceClient();
        
        // Build the resource name
        $name = "projects/$projectId/secrets/$secretId/versions/latest";
        if ($glog) fwrite($glog, "getSecretValue(\$projectId=$projectId, \$secretId=$secretId, Secret name=$name\r\n");
        
        try {
            // Try the newer approach with request object
            $request = new AccessSecretVersionRequest();
            $request->setName($name);
            $response = $client->accessSecretVersion($request);
            return $response->getPayload()->getData();
        } catch (Exception $e1) {
            if ($glog) fwrite($glog, "First approach failed: " . $e1->getMessage() . "\r\n");
            
            try {
                // Try direct approach (older API)
                $response = $client->accessSecretVersion($name);
                return $response->getPayload()->getData();
            } catch (Exception $e2) {
                if ($glog) fwrite($glog, "Second approach failed: " . $e2->getMessage() . "\r\n");
                
                try {
                    // Try getSecretVersion approach
                    $response = $client->getSecretVersion($name);
                    // This might not have getPayload method, try alternative approaches
                    if (method_exists($response, 'getPayload')) {
                        return $response->getPayload()->getData();
                    } else if (method_exists($client, 'accessSecretVersion')) {
                        // Try one more approach
                        $accessResponse = $client->accessSecretVersion($name);
                        return $accessResponse->getPayload()->getData();
                    }
                } catch (Exception $e3) {
                    if ($glog) fwrite($glog, "All approaches failed: " . $e3->getMessage() . "\r\n");
                }
            }
        }
    } catch (Exception $e) {
		error_log("getSecretValue(\$projectId=$projectId, \$secretId=$secretId, Secret name=$name\r\n");
        error_log("Secret Manager error: " . $e->getMessage() . "\r\n");
    }
    
    return ""; // Return empty string if all approaches fail
}

// Database connection details
GLOBAL $db_host;
GLOBAL $db_name;
GLOBAL $db_user;
GLOBAL $db_pwd;
$db_host = getenv('BOOKING_DB_HOST') ?: 'localhost';
$db_name = getenv('BOOKING_DB_NAME') ?: 'booking';
$db_user = "";
$db_pwd = "";

GLOBAL $projectId;
	
$projectId 		= getenv('PROJECTID');
if ($hostmachine=="localDEV" || $hostmachine=='TEST'){
	$db_pwd="";
	$db_user="root";
} else {
	$secretId 		= getenv('BOOKING_DB_USER');
	if ($glog) fwrite($glog, __FILE__.", Line ".__LINE__."\$db_user=getSecretValue($projectId, $secretId)\r\n");
	$db_user 		= getSecretValue($projectId, $secretId);

	$secretId 		= getenv('BOOKING_DB_PWD');
	if ($glog) fwrite($glog, __FILE__.", Line ".__LINE__."\$db_pwd=getSecretValue($projectId, $secretId)\r\n");
	$db_pwd 		= getSecretValue($projectId, $secretId);
}

GLOBAL $con;  // Database connection
$con=false;
GLOBAL $dbname;

function getMySQLiDBConnection(){
	GLOBAL $db_host;
	GLOBAL $db_name;
	GLOBAL $db_user;
	GLOBAL $db_pwd;

	// Connect to database
	try{
		$con = new mysqli($db_host, $db_user, $db_pwd, $db_name) or die("Fatal Error in file ". __FILE__.", at line" . __LINE__. " , Error EQL102. Query failed: " . mysqli_error($conn) );
		return $con;
	} catch(Exception $e){
		  echo "\r\n Failed to connect to database: ".$e->getMessage()."\n";
		  return false;	
	}
}

// Function to get a PDO database connection
function getDBConnection() {
    GLOBAL $db_host;
    GLOBAL $db_name;
    GLOBAL $db_user;
    GLOBAL $db_pwd;
    GLOBAL $glog; 
	GLOBAL $debugMode;
	if ($glog) fwrite($glog, "globals.getDBConnection()\r\n");
	
    // DSN (Data Source Name) specifies the database type, host, and database name
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

    // PDO options for error handling, fetch mode, etc.
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Default fetch mode to associative array
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
    ];

    try {
        // Create a new PDO instance
        $pdo = new PDO($dsn, $db_user, $db_pwd, $options);
        if($debugMode && $glog)  fwrite($glog, "PDO Connection Successful to $db_name on $db_host\r\n");
        return $pdo; // Return the PDO connection object
    } catch (PDOException $e) {
        // Log the error securely
        if ($glog) fwrite($glog, "PDO Connection Error: " . $e->getMessage() . "\r\n");
        // Handle connection error gracefully - don't expose details to the user in production
        // For development, you might echo or die, but debugMode is better for production.
        error_log("PDO Connection Error: " . $e->getMessage());
        // You might choose to die, throw the exception again, or return null/false
        // depending on how you want your application to handle DB connection failures.
        die("Database connection failed. Please check logs or contact support."); // Or return null; / return false;
        // return null; // Or return false;
    }
}

if ($debugLevel>2 && isset($glog)) {
  if (isset($_SESSION)) foreach ($_SESSION as $key => $value) {fwrite($glog, "globals.php \$_SESSION[$key]='$value' \r\n"); }
  if (isset($_COOKIE))  foreach ($_COOKIE as $key => $value)  {fwrite($glog, "globals.php \$_COOKIE[$key]='$value' \r\n"); }
  if (isset($_GET))     foreach ($_GET as $key => $value)     {fwrite($glog, "globals.php \$_GET[$key]='$value' \r\n"); }
  if (isset($_POST))    foreach ($_POST as $key => $value)    {fwrite($glog, "globals.php \$_POST[$key]='$value' \r\n"); }
  fwrite($glog, "========================== ". $_SERVER['PHP_SELF'] . " END OF globals.php  ============================\r\n");
}

if($debugMode && $glog)fwrite($glog, "globals.php line ".__LINE__." - END\r\n");
// cannot close here because the embedded functions in globals.php need it open:  if($debugMode && $glog)fclose($glog);

?>
