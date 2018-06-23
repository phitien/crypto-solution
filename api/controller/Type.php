<?php
class Controller_Type extends Controller {
  public function __construct(Router $router) {
    parent::__construct($router);
    $this->_model = new Model_Type;
  }
}
