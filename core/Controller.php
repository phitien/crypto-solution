<?php
class Controller {
  protected $_router;
  protected $_model;
  public function __construct(Router $router) {$this->_router = $router;}
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
