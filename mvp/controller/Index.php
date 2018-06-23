<?php
class Controller_Index extends Controller {
  public function __construct(Router $router) {
    parent::__construct($router);
    $this->_model = new Model_Category;
  }
}
