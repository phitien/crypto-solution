<?php
define('APP', 'sozoapi');
define('APP_TYPE', 'json');
define('APP_NAME', 'SOZO');

define('HASH_KEY', APP.'@123456');

define('APP_DIR', $_SERVER['DOCUMENT_ROOT']);
define('SHARE_DIR', APP_DIR.'/../share');
define('VENDOR_DIR', APP_DIR.'/../vendor');

// define('NODEBUG', false);

//DB CONFIG
define('DB_HOST','localhost');
define('DB_USER','sozo_usr');
define('DB_PWD','BrpmqLTncuhJzKZ2');
define('DB_NAME', 'sozo_db');
define('DB_CHARSET', 'utf8');

define('EMAIL_SENDER_NAME','SOZO');
define('EMAIL_SENDER_EMAIL','szo.purecode@gmail.com');
define('EMAIL_ACCOUNT','szo.purecode@gmail.com');
define('EMAIL_PASSWORD','szo.purecode@123456');

define('EXTERNAL_URL','http://mvp.szo.io');
define('EXTERNAL_EMAIL_VERIFICATION',EXTERNAL_URL.'/email/verification');
define('EXTERNAL_FORGET_PASSWORD',EXTERNAL_URL.'/password/forget');
define('EXTERNAL_RESET_PASSWORD',EXTERNAL_URL.'/password/reset');

define('ONESIGNAL_APPID','ec589436-0771-461a-a7d9-4a9be77399d1');
define('ONESIGNAL_APIKEY','MTYyNWZhNzAtODczNC00ZjQzLWE0Y2EtZWQ3YWYxNjQzNjAz');

require_once SHARE_DIR.'/bootstrap.php';
require_once APP_DIR.'/routes.php';

ini_set('display_errors', DEBUG);
ini_set('display_startup_errors', DEBUG);
error_reporting(DEBUG ? E_ALL : 0);

$app = new App();
