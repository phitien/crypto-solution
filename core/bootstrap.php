<?php
require_once CORE_DIR.'/autoload.php';

if (!defined('APP_TYPE')) define('APP_TYPE', 'api');//value is api or web
if (!defined('APP_COLOR')) define('APP_COLOR', '#4285f4');
if (!defined('DEBUG')) define('DEBUG', !empty($_REQUEST['debug']));
if (!defined('NODEBUG')) define('NODEBUG', true);
if (!defined('HASH_ALGO')) define('HASH_ALGO', 'ripemd160');
if (!defined('DATETIME_FORMAT')) define('DATETIME_FORMAT', 'Y-m-d H:i:s');
if (!defined('DB_DATETIME_FORMAT')) define('DB_DATETIME_FORMAT', '%Y-%m-%dT%T');
if (!defined('PSIZE')) define('PSIZE', 20);
if (!defined('PAGE')) define('PAGE', 0);
if (!defined('SIGNIN_ATTEMPT_LIMIT')) define('SIGNIN_ATTEMPT_LIMIT', 10);
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8');
if (!defined('TIMEZONE')) define('TIMEZONE', 'Asia/Bangkok');
if (!defined('MAILER')) define('MAILER', 'zend_mail');
if (!defined('DOMAIN')) define('DOMAIN', $_SERVER['SERVER_NAME']);
if (!defined('PROTOCOL')) define('PROTOCOL', ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443 || $_SERVER['HTTP_X_FORWARDED_PORT'] == 443) ? "https://" : "http://");
if (!defined('EMAIL_VERIFICATION')) define('EMAIL_VERIFICATION', false);
if (!defined('COUNTRY_PATH')) define('COUNTRY_PATH', CORE_DIR.'/../src/static/static/data/countries.json');
date_default_timezone_set(TIMEZONE);

function t() {
  $args = func_get_args();
  return call_user_func_array('sprintf', $args);
}
function is_assoc($o) {
  return is_array($o) && array_diff_key($o,array_keys(array_keys($o)));
}

require_once CORE_DIR.'/routes.php';
