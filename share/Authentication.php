<?php
class Authentication {
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
    if ($this->isPublic()) return true;
    $token = Request::token();
    if (empty($token)) throw new Exception_Forbidden("Unauthenticated");
    if (!$this->validate($token)) throw new Exception_Forbidden("Unauthenticated");
  }
  public function isPublic() {
    if (empty($this->_route['public'])) return false;
    return $this->_route['public'] ? true : false;
  }
  protected function validate($token) {
    $model = new Model_Token();
    return $this->_user = $model->validate($token);
  }
  public function user() {
    if (empty($this->_user)) throw new Exception_Forbidden("Unauthenticated");
    return $this->_user;
  }

}
