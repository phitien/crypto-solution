<?php
class Template {
  public static $smarty;
  protected $_data;
  protected $_config = [];
  public static function init() {
    if (!self::$smarty) self::$smarty = self::factory();
  }
  public static function factory() {
    $smarty = new Smarty;
    $smarty->setConfigDir('smarty/configs/');
    $smarty->setCompileDir('tmp/compile/');
    $smarty->setCacheDir('tmp/cache/smarty');
    $smarty->debugging = DEBUG;
    return $smarty;
  }
  public static function renderUC($tpl, $options = []) {
    $smarty = self::factory();
    $path = APP_DIR."/views/elements/uc";
    if (!is_dir($path) || !file_exists("$path/$tpl.tpl")) $path = CORE_DIR."/views/elements/uc";
    $smarty->setTemplateDir($path);
    foreach($options as $k => $v) $smarty->assign($k,$v);
    return $smarty->fetch("$tpl.tpl");
  }
  public static function renderMenu($data = []) {
    $parent_id = isset($data['parent_id']) ? $data['parent_id'] : '';
    $recursive = isset($data['recursive']) ? $data['recursive'] : false;
    $class = @$data['class'];
    $max = (int) $data['max'];
    $model = new Model_Menu;
    $item = $model->all(['parent_id' => $parent_id]);
    return self::renderUC('menu', [
      'items' => $item,
      'class' => $class,
      'recursive' => $recursive,
      'max' => $max
    ]);
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
    if (!is_dir($dir)) return;
    foreach(scandir($dir, 1) as $f) {
      $p = "$dir/$f";
      $rs = is_file($p);
      if ($rs) {
        $info = pathinfo($p);
        $o = Util::json($p);
        $prop = $info['filename'];
        $klass = "Html_{$prop}";
        if (is_assoc($o)) new $klass($o);
        else if (is_array($o)) foreach($o as $i) new $klass($i);
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
