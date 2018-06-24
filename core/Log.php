<?php
class Log {
	public static function out($o,$section=null) {
		if (!DEBUG) return;
		if ($o instanceof Model) return Session::log($o->output,$section);
		if (!$o instanceof Exception) return Session::log($o,$section);
		Session::log('Error:'.$o->getMessage(),$section);
		Session::log('ErrorType:'.get_class($o),$section);
		Session::log('ErrorFile:'.$o->getFile(),$section);
		Session::log('ErrorLine:'.$o->getLine(),$section);
		return get_called_class();
	}
	public static function info() {
		$args = func_get_args();
		foreach($args as $arg) self::out($arg);
		return get_called_class();
	}
	public static function error() {
		$args = func_get_args();
		foreach($args as $arg) self::out($arg,'error');
		return get_called_class();
	}
	public static function warn() {
		$args = func_get_args();
		foreach($args as $arg) self::out($arg,'warn');
		return get_called_class();
	}
	public static function sql() {
		$args = func_get_args();
		foreach($args as $arg) self::out($arg,'sql');
		return get_called_class();
	}
}
