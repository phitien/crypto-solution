<?php
set_include_path(get_include_path() . PATH_SEPARATOR . CORE_DIR . PATH_SEPARATOR . VENDOR_DIR);
spl_autoload_register(function($className) {
	$pieces = array_filter(explode('_', $className));
	$last = ucfirst(array_pop($pieces)).'.php';
	$path = APP_DIR.'/'.strtolower(implode('/', $pieces)).'/'.$last;
	if (file_exists($path)) return include_once($path);
	$path = CORE_DIR.'/'.strtolower(implode('/', $pieces)).'/'.$last;
	if (file_exists($path)) return include_once($path);
});
require_once VENDOR_DIR.'/autoload.php';
use Zend\Loader\StandardAutoloader;
$loader = new StandardAutoloader(array('autoregister_zf' => true));
// Register with spl_autoload:
$loader->register();
