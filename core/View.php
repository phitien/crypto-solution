<?php
class View {
  protected $_tpl;
  protected $_contentTpl;
  protected $_header;
  protected $_left;
  protected $_content;
  protected $_right;
  protected $_footer;
  public function __construct($tpl) {
    $this->_contentTpl = $tpl;
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
    $res = ['responseText' => ''];
    Event::publish($this, "beforerender", $tpl, $res);
    $content = $this->renderContent();
    $header = $this->renderHeader();
    $left = $this->renderLeft();
    $right = $this->renderRight();
    $footer = $this->renderFooter();
    $debug = $this->renderDebug();
    $tpl->set([
      'content' => $content,
      'header' => $header,
      'left' => $left,
      'right' => $right,
      'footer' => $footer,
      'debug' => $debug,
    ]);
    $res['responseText'] .= $tpl->render("layout");
    Event::publish($this, "afterrender", $tpl, $ajax ? $content : $res);
    return $res['responseText'];
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
