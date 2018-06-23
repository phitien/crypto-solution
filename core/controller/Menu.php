<?php
class Controller_Menu extends Controller {
  public function __construct(Router $router) {
    parent::__construct($router);
    $this->_model = new Model_Group;
  }
}
