<?php
class View {
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
    $this->_res = ['responseText' => ''];
    Event::publish($this, "beforerender", $this->_tpl, $this->_res);
  }
  public final function assign($k, $v = null) {
    $this->template()->set($k,$v);
  }
  public final function template() {
    if (!$this->_tpl) $this->_tpl = new Template;
    return $this->_tpl;
  }
  public function render() {
    $ajax = Request::ajax();
    $tpl = new Template;
    $content = $this->renderContent();
    $header = $ajax ? "" : $this->renderHeader();
    $left = $ajax ? "" : $this->renderLeft();
    $right = $ajax ? "" : $this->renderRight();
    $footer = $ajax ? "" : $this->renderFooter();
    $debug = $ajax ? "" : $this->renderDebug();
    $this->_tpl->set([
      'content' => $content,
      'header' => $header,
      'left' => $left,
      'right' => $right,
      'footer' => $footer,
      'debug' => $debug,
    ]);
    $this->_res['responseText'] .= $ajax ? $content : $this->_tpl->render("layout");
    Event::publish($this, "afterrender", $this->_tpl, $this->_res);
    return $this->_res['responseText'];
  }
  public function renderHeader() {
    return $this->template()->render("elements/header");
  }
  public function renderLeft() {
    return $this->template()->render("elements/left");
  }
  public function renderContent() {
    return $this->template()->render($this->_contentTpl);
  }
  public function renderRight() {
    return $this->template()->render("elements/right");
  }
  public function renderFooter() {
    return $this->template()->render("elements/footer");
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
}
