<?php
require_once SHARE_DIR.'/autoload.php';

if (!defined('APP_TYPE')) define('APP_TYPE', 'api');//value is api or web
if (!defined('DEBUG')) define('DEBUG', !empty($_REQUEST['debug']));
if (!defined('NODEBUG')) define('NODEBUG', true);
if (!defined('HASH_ALGO')) define('HASH_ALGO', 'ripemd160');
if (!defined('DATETIME_FORMAT')) define('DATETIME_FORMAT', 'Y-m-d H:i:s');
if (!defined('DB_DATETIME_FORMAT')) define('DB_DATETIME_FORMAT', '%Y-%m-%dT%T');
if (!defined('PSIZE')) define('PSIZE', 20);
if (!defined('PAGE')) define('PAGE', 0);
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8');
if (!defined('TIMEZONE')) define('TIMEZONE', 'Asia/Bangkok');
if (!defined('MAILER')) define('MAILER', 'zend_mail');
if (!defined('DOMAIN')) define('DOMAIN', $_SERVER['SERVER_NAME']);
if (!defined('PROTOCOL')) define('PROTOCOL', ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443 || $_SERVER['HTTP_X_FORWARDED_PORT'] == 443) ? "https://" : "http://");
if (!defined('EMAIL_VERIFICATION')) define('EMAIL_VERIFICATION', false);

function t() {
  $args = func_get_args();
  return call_user_func_array('sprintf', $args);
}
date_default_timezone_set(TIMEZONE);
