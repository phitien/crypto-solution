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
    $token = Request::token();
    $model = new Model_Token();
    $this->_user = $model->validate($token);
    Log::info("User", $this->user());
    if (!$this->isPublic() && !$this->user()->output)
      throw new Exception_Forbidden("Unauthenticated");
  }
  public function isPublic() {
    return $this->_router->isPublic();
  }
  public function user() {return $this->_user;}

}
