<?php
class Controller_Category extends Controller {
  public function __construct(Router $router, View $view) {
    parent::__construct($router, $view);
    $this->_model = new Model_Category;
  }
}
