<?php
class Html_Favicon extends Html_Dom {
  public static $data = [];
  public function __toString() {
    if (is_string($this->_d)) $html = $this->_d;
    else {
      $props = [];
      foreach($this->_d as $k => $v) $this->prop($props,$k,$v);
      $html = sprintf('<link %s/>',implode(" ", $props));
    }
    return $html;
  }
}
