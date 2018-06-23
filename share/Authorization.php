<?php
class Authorization {
  protected $_app;
  protected $_router;
  protected $_route;
  protected $_controller;
  protected $_action;
  protected $_user;

  public function __construct(Router $router) {
    $this->_router = $router;
    $this->_route = $this->_router->route();
    $this->_app = $this->_router->app();
    $this->_controller = $this->_router->controller();
    $this->_action = $this->_router->action();
  }
  public function execute() {
    //TODO
  }
}
