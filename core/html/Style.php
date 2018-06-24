<?php
class Html_Style extends Html_Dom {
  public static $data = [];
  public function __toString() {
    if (is_string($this->_d)) $html = sprintf('<style>%s</style>',$this->_d);
    else {
      $props = [];
      $this->prop($props,'rel','stylesheet');
      foreach($this->_d as $k => $v) $this->prop($props,$k,$v);
      $html = sprintf('<link %s/>',implode(" ", $props));
    }
    return $html;
  }
}
