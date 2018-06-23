<?php
class Html_Dom {
  public static function data() {return false;}
  public static function html() {return "";}
  public function prop(&$props,$k,$v) {
    array_push($props, sprintf('%s="%s"',$k,$v));
  }
  public static function remove(&$items,$v) {
    $items = array_diff($items, [$v]);
  }
  public static function add(&$items,$v) {
    $klass = get_called_class();
    new $klass($v);
  }
}
