<?php
class Html_Page extends Html_Dom {
  public static $data = [];
  public static function data() {return Html_Page::$data;}
  public function __construct($d = null) {
    if (empty($d)) return;
    self::$data = array_merge((array) self::$data, (array) $d);
  }
}
