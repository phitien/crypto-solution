<?php
class Template {
  public static $smarty;
  protected $_data;
  protected $_config = [];
  public static function init() {
    self::$smarty = new Smarty;
    self::$smarty->setConfigDir('smarty/configs/');
    self::$smarty->setCompileDir('tmp/compile/');
    self::$smarty->setCacheDir('tmp/cache/smarty');
    self::$smarty->debugging = DEBUG;
  }
  public function __construct($data = []) {
    $this->_data = $data;
    $this
      ->setconfig($this->_config, CORE_DIR)
      ->setconfig($this->_config, APP_DIR);
  }
  public function set($k,$v=null) {
    if (is_string($k)) self::$smarty->assign($k,$v);
    else foreach($k as $key => $val) self::$smarty->assign($key,$val);
  }
  public function config() {return $this->_config;}
  public function setViewParams() {
    $this->set($this->_data);
    foreach($this->_config as $k => $klass) {
      $data = $klass::data();
      if (!empty($data)) $this->set($data);
      $html = $klass::html();
      if ($html) $this->set(strtolower($k), $html);
    }
    return $this;
  }
  public function setconfig(&$config, $dir) {
    $dir = $dir."/config";
    foreach(scandir($dir, 1) as $f) {
      $p = "$dir/$f";
      $rs = is_file($p);
      if ($rs) {
        $info = pathinfo($p);
        $o = Util::json($p);
        $prop = $info['filename'];
        $klass = "Html_{$prop}";
        if (is_array($o)) foreach($o as $i) new $klass($i);
        else new $klass($o);
        $config[$prop] = $klass;
      }
    }
    return $this;
  }
  public function render($tpl) {
    $path = APP_DIR."/views";
    if (is_dir($path) && file_exists("$path/$tpl.tpl")) self::$smarty->setTemplateDir($path);
    else self::$smarty->setTemplateDir(CORE_DIR."/views");
    return self::$smarty->fetch("$tpl.tpl");
  }
}
