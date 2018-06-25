<?php
class Authorization {
  protected $_router;

  public function __construct(Router $router) {
    $this->_router = $router;
  }
  public function execute() {
    //TODO
  }
  public function router() {
    return $this->_router;
  }
}
