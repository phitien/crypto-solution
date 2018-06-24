<?php
class Session {
	public static function init() {
		session_start();
		$_SESSION['logs'] = [];
		return get_called_class();
	}
	public static function log($o,$section=null) {
		if (!$section) array_push($_SESSION['logs'], $o);
		else {
			if (!isset($_SESSION['logs'][$section])) $_SESSION['logs'][$section] = [];
			array_push($_SESSION['logs'][$section], $o);
		}
		return get_called_class();
	}
	public static function logs($section=null) {
		if (!$section) return $_SESSION['logs'];
		else return !isset($_SESSION['logs'][$section]) ? [] :$_SESSION['logs'][$section];
	}
	public static function clean($section=null) {
		if (!$section) $_SESSION['logs'] = [];
		else $_SESSION['logs'][$section] = [];
		return get_called_class();
	}
	public static function get($f,$section=null) {
		if (!$section) return isset($_SESSION[$f]) ? $_SESSION[$f] : null;
		else return isset($_SESSION[$section]) && isset($_SESSION[$section][$f]) ? $_SESSION[$section][$f] : null;
	}
	public static function set($f,$v,$section=null) {
		if (!$section) $_SESSION[$f] = $v;
		else {
			if (!isset($_SESSION[$section])) $_SESSION[$section] = [];
			$_SESSION[$f][$section] = $v;
		}
		return get_called_class();
	}
}
