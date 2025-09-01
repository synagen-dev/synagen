<?php
// Flag that specifies whether this machine is a production or test host, or a local development machine
// Each site will have its own version of this file, and it must never be overwritten

GLOBAL $hostmachine;

//$hostmachine="localDEV";
//$hostmachine="DEV";
//$hostmachine="TEST";
$hostmachine="PROD";

define('DS', DIRECTORY_SEPARATOR); // either "/" or "\"

GLOBAL $base_dir;
$base_dir="/var/www/synagen";

GLOBAL $logdir;
$logdir="/etc/apache2/logs/synagen";
ini_set('error_log', "$logdir/error.log");

?>