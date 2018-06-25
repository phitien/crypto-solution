<?php
class Event_View {
  protected $_plugins = [];
  public function __construct() {
    $this->load_plugins(CORE_DIR);
    $this->load_plugins(APP_DIR);
  }
  public function load_plugins($dir) {
    $dir = $dir."/plugin";
    if (!is_dir($dir)) return;
    foreach(scandir($dir, 1) as $f) {
      $p = "$dir/$f";
      $rs = is_file($p);
      if ($rs) {
        $info = pathinfo($p);
        $prop = $info['filename'];
        $klass = "Plugin_$prop";
        $this->_plugins[$prop] = new $klass;
      }
    }
  }
  public function beforeplugins() {
    return array_filter(array_values($this->_plugins), function($p) {
      return $p->type() == 'html' && $p->runbefore();
    });
  }
  public function afterplugins() {
    return array_filter(array_values($this->_plugins), function($p) {
      return $p->type() == 'html' && !$p->runbefore();
    });
  }
  public function beforerender($view, $action = "index", $tpl, $res) {
    $tpl->setViewParams();
    $plugins = $this->beforeplugins();
    foreach($plugins as $plugin) $res = $plugin->enhance($res);
    return $res;
  }
  public function afterrender($view, $action = "index", $tpl, $res) {
    $plugins = $this->afterplugins();
    foreach($plugins as $plugin) $res = $plugin->enhance($res);
    return $res;
  }
}
