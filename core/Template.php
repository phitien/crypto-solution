<?php
class Template {
  public static $smarty;
  public $data;
  public static function init() {
    self::$smarty = new Smarty;
    self::$smarty->setTemplateDir('views');
    self::$smarty->setConfigDir('smarty/configs/');
    self::$smarty->setCompileDir('tmp/compile/');
    self::$smarty->setCacheDir('tmp/cache/smarty');
    self::$smarty->debugging = DEBUG;
  }
  public function __construct($data = []) {
    $this->data = $data;
    $this->set($this->data);
  }
  public function set($k,$v=null) {
    if (is_string($k)) self::$smarty->assign($k,$v);
    else foreach($k as $key => $val) self::$smarty->assign($key,$val);
  }
  public function render($tpl) {
    return self::$smarty->fetch("$tpl.tpl");
  }
}
