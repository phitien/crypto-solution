<?php
class Html_Meta extends Html_Dom {
  public static $items = [];
  public static function html() {return implode("",Html_Meta::$items);}
  public function __construct($d = null) {
    if (!$d) return;
    $html = "";
    if (is_string($d)) $html = $d;
    else {
      $props = [];
      foreach($d as $k => $v) $this->prop($props,$k,$v);
      $html = sprintf('<meta %s/>',implode(" ", $props));
    }
    if (!in_array($html, self::$items)) array_push(self::$items, $html);
  }
}
