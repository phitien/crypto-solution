<?php
class Controller {
  protected $_router;
  protected $_model;
  protected $_view;
  public function __construct(Router $router, View $view) {
    $this->_router = $router;
    $this->_view = $view;
  }
  public final function __set($n, $v) {
		if (method_exists($this, $n)) return;
		if (property_exists($this,$n)) return $this[$n] = $v;
		$this->assignToView($n, $v);
  }

  public final function assignToView($k, $v = null) {$this->_view->assign($k,$v);return $this;}
  public final function router() {return $this->_router;}
  public final function route() {return $this->router()->route();}
  public final function app() {return $this->router()->app();}
  public final function action() {return $this->router()->action();}
  public final function handler() {return $this->router()->handler();}
  public final function user() {return $this->router()->user();}
  public final function pinfo($pinfo = null) {$this->app()->pinfo($pinfo);}
  public final function setMimeType($v) {$this->app()->setMimeType($v);return $this;}
  public final function model() {return $this->_model;}
  public function index() {
    $results = $this->model()->page(Request::data());
    $this->pinfo($this->model()->pinfo());
    return $results;
  }
  public function all() {return $this->model()->fetch(Request::data());}
  public function detail() {return $this->model()->load(Request::uid());}
  public function create() {return $this->model()->load(Request::uid())->set(Request::data())->add();}
  public function update() {return $this->model()->load(Request::uid())->set(Request::data())->edit();}
  public function delete() {return $this->model()->load(Request::uid())->remove();}
}
