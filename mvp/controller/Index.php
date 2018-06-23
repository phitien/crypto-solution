<?php
class Controller_Index extends Controller {
  public function __construct(Router $router, View $view) {
    parent::__construct($router, $view);
    $this->_model = new Model_Category;
  }
  public function index() {
    $this->assignToView('title', 'asdadad');
  }
}
