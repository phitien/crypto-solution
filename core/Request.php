<?php
class Request {
  protected static $_data = [];
  public final static function init() {
    $_HEADERS = getallheaders();
    $_BODY = json_decode(file_get_contents('php://input'), true);
    self::$_data = [];//self::$_data = $_COOKIE;
    if (!empty($_REQUEST)) self::$_data = array_merge(self::$_data, $_REQUEST);
    if (!empty($_HEADERS)) self::$_data = array_merge(self::$_data, $_HEADERS);
    if (!empty($_GET)) self::$_data = array_merge(self::$_data, $_GET);
    if (!empty($_POST)) self::$_data = array_merge(self::$_data, $_POST);
    if (!empty($_BODY)) self::$_data = array_merge(self::$_data, $_BODY);
  }
  public final static function token() {return @self::$_data['token'];}
  public final static function get($f) {return @self::$_data[$f];}
  public final static function set($f,$v) {self::$_data[$f] = $v;}
  public final static function data() {
    $fields = func_get_args();
    return empty($fields) ? self::$_data : array_reduce($fields, function($rs, $f) {
      $rs[$f] = @self::$_data[$f];
      return $rs;
    }, []);
  }
  public final static function uid() {
    return self::get('uid');
  }
  public final static function ajax() {
    return self::get('ajax') ? true : false;
  }
  public final static function method() {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }
  public final static function isGet() {
    return self::method() == 'get';
  }
  public final static function isPost() {
    return self::method() == 'post';
  }
  public final static function isPut() {
    return self::method() == 'put';
  }
  public final static function isDelete() {
    return self::method() == 'delete';
  }
}
