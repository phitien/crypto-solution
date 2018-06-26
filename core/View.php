<?php
class View {
  protected $_controller;
  protected $_tpl;
  protected $_res;
  protected $_contentTpl;
  protected $_header;
  protected $_left;
  protected $_content;
  protected $_right;
  protected $_footer;
  public function __construct($tpl) {
    $this->_contentTpl = $tpl;
    $this->_tpl = new Template;
    $this->_res = ['html' => ''];
    $this->_res = Event::publish($this, "beforerender", $this->_tpl, $this->_res);
  }
  public final function assign($k, $v = null) {
    $this->template()->set($k,$v);
  }
  public final function template() {
    if (!$this->_tpl) $this->_tpl = new Template;
    return $this->_tpl;
  }
  public final function controller($v = null) {
    if (!$v) return $this->_controller;
    $this->_controller = $v;
    return $this;
  }
  public function render() {
    if ($this->controller()->router()->mime_type() == 'json') return $this->_tpl->render("json");
    $tpl = new Template;
    $this->renderCustom();
    $content = $this->template()->render($this->_contentTpl);
    $left = !$this->controller()->hasLeft() ? "" : $this->template()->render("elements/left");
    $right = !$this->controller()->hasRight() ? "" : $this->template()->render("elements/right");
    $header = !$this->controller()->hasHeader() ? "" : $this->template()->render("elements/header");
    $footer = !$this->controller()->hasFooter() ? "" : $this->template()->render("elements/footer");
    $debug = $ajax ? "" : $this->renderDebug();
    $this->_tpl->set([
      'content' => $content,
      'header' => $header,
      'left' => $left,
      'right' => $right,
      'footer' => $footer,
      'debug' => $debug,
    ]);
    $this->_res['html'] .= $ajax ? $content : $this->_tpl->render("layout");
    $this->_res = Event::publish($this, "afterrender", $this->_tpl, $this->_res);
    return $this->_res['html'];
  }
  public function renderDebug() {
    if (DEBUG && !empty(Session::logs())) {
      $this->template()->set('logs', Session::logs());
      $rs = $this->template()->render("elements/debug");
      Session::clean();
      return $rs;
    }
    return "";
  }
  public function renderCustom() {
    $dir = APP_DIR."/views/elements/custom";
    if (!is_dir($dir)) return;
    $options = [];
    foreach(scandir($dir, 1) as $f) {
      $p = "$dir/$f";
      $rs = is_file($p);
      if ($rs) {
        $info = pathinfo($p);
        $prop = $info['filename'];
        $this->_tpl->set($prop, $this->template()->render("elements/custom/$prop"));
      }
    }
  }
}
