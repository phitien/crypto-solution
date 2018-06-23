<?php
class Log {
	public static function out($o) {
		if (!DEBUG) return;
		if ($o instanceof Model) return Session::log($o->output);
		if (!$o instanceof Exception) return Session::log($o);
		Session::log('Error:'.$o->getMessage());
		Session::log('ErrorType:'.get_class($o));
		Session::log('ErrorFile:'.$o->getFile());
		Session::log('ErrorLine:'.$o->getLine());
	}
	public static function info($o) {
		$args = func_get_args();
		foreach($args as $arg) self::out($arg);
	}
	public static function error($o) {
		$args = func_get_args();
		foreach($args as $arg) self::out($arg);
	}
	public static function warn($o) {
		$args = func_get_args();
		foreach($args as $arg) self::out($arg);
	}
}
