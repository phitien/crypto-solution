<?php
class Session {
	public static function init() {
		session_start();
		$_SESSION['logs'] = [];
	}
	public static function log($o) {
		array_push($_SESSION['logs'], $o);
	}
	public static function logs() {
		return $_SESSION['logs'];
	}
	public static function clean() {
		return $_SESSION['logs'] = [];
	}
	public static function get($f) {
		return $_SESSION[$f];
	}
	public static function set($f, $v) {
		$_SESSION[$f] = $v;
	}
}
