<?php
class Html_Style extends Html_Dom {
  public static $items = [];
  public static function html() {return implode("",Html_Style::$items);}
  public function __construct($d = null) {
    if (!$d) return;
    $html = "";
    if (is_string($d)) $html = sprintf('<style>%s</style>',$d);
    else {
      $props = [];
      $this->prop($props,'rel','stylesheet');
      foreach($d as $k => $v) $this->prop($props,$k,$v);
      $html = sprintf('<link %s/>',implode(" ", $props));
    }
    if (!in_array($html, self::$items)) array_push(self::$items, $html);
  }
}
