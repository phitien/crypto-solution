<?php
class Html_Page extends Html_Dom {
  public static $data = [];
  public function __construct($d = null) {
    if (empty($d)) return;
    self::$data = array_merge((array) self::$data, (array) $d);
  }
}
