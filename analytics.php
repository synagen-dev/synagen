<?php
/**
 * Log genuine user access to analytics database
 * 
 * @param string $website The website identifier
 * @param string $function The function being accessed
 * @return boolean True if access was logged (genuine user), false if bot detected
 */
GLOBAL $logdir;
GLOBAL $debugMode;
GLOBAL $debugLevel;
GLOBAL $glog;

$website="";
$origin="";
if (isset($_SERVER['HTTP_HOST']) && strlen($_SERVER['HTTP_HOST'])>0) $origin=$_SERVER['HTTP_HOST'];
else  $origin=$_SERVER['SERVER_NAME'];
$website=$origin;
if (strtolower(substr($website,0,8))==="https://")$website=substr($website,8);
if (strtolower(substr($website,0,4))==="www.")$website=substr($website,4);

//header('Access-Control-Allow-Origin: *');
// if (in_array($website, $allowed_origins)) {
   // header('Access-Control-Allow-Origin: HTTPS://' . $website);
// }
// header('Access-Control-Allow-Methods: GET');


if ($debugMode && $glog) fwrite($glog, __FILE__." origin=$origin, website=$website <BR>\r\n");

function logGenuineAccess($website, $function) {
	GLOBAL $glog;
	GLOBAL $debugMode;

    // Skip debugMode if it's likely a bot
    if (isBot()) {
        return false;
    }

	include "/var/www/advertserver/allowed_websites.php";  
	// Get minimal domain name and check against list of allowed websites
	if (strtolower(substr($website,0,8))==="https://")$website=substr($website,8);
	if (strtolower(substr($website,0,4))==="www.")$website=substr($website,4);

	$db_host = getenv('ANALYTICS_DB_HOST') ?: 'localhost';
	$db_name = getenv('ANALYTICS_DB_NAME') ?: 'analytics';
	
	$country="";
	$city="";
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
	$db_host = getenv('ANALYTICS_DB_HOST') ?: 'localhost';
	$db_name = getenv('ANALYTICS_DB_NAME') ?: 'analytics';
	$db_user = "";
	$db_pwd = "";

	if ($hostmachine=="localDEV" || $hostmachine=='TEST'){
		$db_pwd="";
		$db_user="root";
	} else {	
		$projectId = getenv('PROJECTID');
		// Get the database user
		$secretId = getenv('SECRET_NAME_DBU');
		$db_user = getSecretValue($projectId, $secretId);

		// Get the database password
		$secretId = getenv('SECRET_NAME_DBP');
		$db_pwd = getSecretValue($projectId, $secretId);
	}
	
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pwd, $db_name);
	// Determine country and city of user, for location-specific adverts
	$ip = $_SERVER['REMOTE_ADDR'];

	// If your site is behind a proxy or load balancer (common with cloud platforms)
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}

	// Use a free geolocation API
	$geolocationData = file_get_contents("http://ip-api.com/json/$ip");
	$locationInfo = json_decode($geolocationData, true);

	if ($locationInfo && $locationInfo['status'] == 'success') {
		$country = $locationInfo['country'];
		$city = $locationInfo['city'];
	}    else{
		$country="Unknown";
		$city="Unknown";
	}
    // Check connection
    if ($conn->connect_error) {
        error_log(__FILE__."Analytics connection failed: " . $conn->connect_error);
        return false;
    }
     
    try {
        // Call the stored procedure
        $stmt = $conn->prepare("CALL log_access(?, ?, ?, ?)");
        $stmt->bind_param("ssss", $website, $function,$country,$city);
        $stmt->execute();
        $stmt->close();
        $conn->close();
		// if ($debugMode && $glog) fwrite($glog,  "Line ".__LINE__."\n<BR>");
        return true;
    } catch (Exception $e) {
		if ($debugMode && $glog) fwrite($glog,  "Line ".__LINE__." ". $e->getMessage().", \n<BR>");
        error_log("Error debugMode access: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if current visitor is likely a bot
 * 
 * @return boolean True if visitor is likely a bot
 */
function isBot() {
	GLOBAL $glog;
	GLOBAL $debugMode;
	
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    // if ($debugMode && $glog) fwrite($glog,  "Line ".__LINE__."\n<BR>");
    
    // No user agent is suspicious
    if (empty($userAgent)) {
        return true;
    }
    
    // Common bot user agent patterns
    $botPatterns = [
        'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 
        'baidu', 'yandex', 'wget', 'curl', 'apache-httpclient',
        'python-requests', 'ahrefs', 'semrush', 'screaming frog',
        'googlebot', 'bingbot', 'facebookexternalhit', 'headless',
        'lighthouse', 'pagespeed', 'pingdom', 'pingbot', 'GTmetrix',
        'snapchat', 'whatsapp', 'flipboard', 'tumblr', 'wp-admin'
    ];
    
    // Check if user agent contains known bot patterns
    foreach ($botPatterns as $pattern) {
        if (stripos($userAgent, $pattern) !== false) {
            return true;
        }
    }
    
    // Check for common HTTP headers that browsers typically send
    $commonHeaders = [
        'Accept', 'Accept-Language', 'Accept-Encoding',
        'Connection', 'Referer'
    ];
    
    $missingHeaderCount = 0;
    foreach ($commonHeaders as $header) {
        $headerKey = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (!isset($_SERVER[$headerKey])) {
            $missingHeaderCount++;
        }
    }
    
    // If too many common headers are missing, it might be a bot
    if ($missingHeaderCount >= 3) {
        return true;
    }
    
    // Check for suspicious behavior
//    if (empty($_SERVER['HTTP_REFERER']) && isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache') {
//        return true;
//    }
    
    return false;
}

// Log the access if it's a genuine user - Example usage
//$function = "Load"; // The function being accessed
//logGenuineAccess($website, $function);
// if ($debugMode && $glog) fwrite($glog,  "Completed OK \n");
?>