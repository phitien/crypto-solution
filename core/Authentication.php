<?php
class Authentication {
  protected $_router;
  protected $_user;

  public function __construct(Router $router) {
    $this->_router = $router;
  }
  public function execute() {
    $token = Request::token();
    $model = new Model_Token();
    $this->_user = $model->validate($token);
    Log::info("User", $this->user());
    if (!$this->router()->isPublic() && !$this->user()->output)
      throw new Exception_Forbidden("Unauthenticated");
  }
  public function router() {
    return $this->_router;
  }
  public function user() {return $this->_user;}
}
