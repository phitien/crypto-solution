<?php
class Html_BottomScript extends Html_Dom {
  public static $items = [];
  public static function html() {return implode("",Html_BottomScript::$items);}
  public function __construct($d = null) {
    if (!$d) return;
    $html = "";
    if (is_string($d)) $html = sprintf("<script>%s</script>", $d);
    else {
      $props = [];$text = "";
      $this->prop($props, 'defer','true');
      foreach($d as $k => $v) {
        if ($k == 'content') $text = $v;
        else $this->prop($props,$k,$v);
      }
      $html = sprintf('<script %s>%s</script>',implode(" ", $props),$text);
    }
    if (!in_array($html, self::$items)) array_push(self::$items, $html);
  }
}
