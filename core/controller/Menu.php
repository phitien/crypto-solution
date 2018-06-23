<?php
class Controller_Menu extends Controller {
  public function __construct(Router $router, View $view) {
    parent::__construct($router, $view);
    $this->_model = new Model_Group;
  }
}
