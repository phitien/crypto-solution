<?php
class Html_Dom {
  public static function data() {
    $klass = get_called_class();
    return $klass::$data;
  }
  public static function html() {
    $klass = get_called_class();
    return implode("", $klass::$data);
  }
  public function prop(&$props,$k,$v) {
    array_push($props, sprintf('%s="%s"',$k,$v));
  }
  public static function remove($v) {
    $klass = get_called_class();
    $klass::$data = array_diff($klass::$data, [$v]);
  }
  public static function add($v) {
    $klass = get_called_class();
    new $klass($v);
  }
  protected $_d = [];
  public function __construct($d = null) {
    if (!$d) return;
    $this->_d = $d;
    $html = "$this";
    $klass = get_class($this);
    if (!in_array($html, $klass::$data)) array_push($klass::$data, $html);
  }
  public function d() {
    return $this->_d;
  }
  public function delete() {
    $klass = get_class($this);
    $klass::remove("$this");
    return $this;
  }
}
