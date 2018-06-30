<?php
class Controller {
  //*Note: the controller should be working even without those traits
  use Inject_Html;
  use Inject_Action;

  protected $_router;
  protected $_model;
  protected $_view;
  protected $_hasLeft;
  protected $_hasRight;
  protected $_hasFooter;
  protected $_hasHeader;
  public function __construct(Router $router, View $view) {
    $this->_router = $router;
    $this->_view = $view;
    $this->pageClassName = str_replace('controller_', '', strtolower(get_class($this)));
    $this->title = APP_NAME;
    $this->hasLeft(true);
    $this->hasRight(true);
    $this->hasHeader(true);
    $this->hasFooter(true);
  }
  public final function __set($n, $v) {
		if (method_exists($this, $n)) return;
		if (property_exists($this,$n)) return $this[$n] = $v;
		$this->assignToView($n, $v);
  }

  public final function router() {return $this->_router;}
  public final function route() {return $this->router()->route();}
  public final function app() {return $this->router()->app();}
  public final function action() {return $this->router()->action();}
  public final function handler() {return $this->router()->handler();}
  public final function user() {return $this->router()->user();}
  public final function pinfo($pinfo = null) {$this->router()->pinfo($pinfo);}
  public final function model() {return $this->_model;}

  public final function template($tpl) {
    $this->_view->contentTemplate($tpl);return $this;
  }
  public final function layout($tpl) {
    $this->_view->layoutTemplate($tpl);return $this;
  }
  public final function assignToView($k, $v = null) {$this->_view->assign($k,$v);return $this;}
  public final function hasLeft($v=null) {
    if ($v === null) return $this->_hasLeft;
    $this->assignToView('hasLeft', $this->_hasLeft = $v);
    return $this;
  }
  public final function hasRight($v=null) {
    if ($v === null) return $this->_hasRight;
    $this->assignToView('hasRight', $this->_hasRight = $v);
    return $this;
  }
  public final function hasHeader($v=null) {
    if ($v === null) return $this->_hasHeader;
    $this->assignToView('hasHeader', $this->_hasHeader = $v);
    return $this;
  }
  public final function hasFooter($v=null) {
    if ($v === null) return $this->_hasFooter;
    $this->assignToView('hasFooter', $this->_hasFooter = $v);
    return $this;
  }

  public function error() {
    //do nothing
  }
}
