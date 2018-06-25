<?php
class Controller_Index extends Controller {
  public function __construct(Router $router, View $view) {
    parent::__construct($router, $view);
    $this->_model = new Model_Category;
  }
  public function index() {
    // $this->title = '11111';
    // $this->hasLeft(false);
    // $this->hasRight(false);
  }
  public function json() {
    return $this->model()->fetch(Request::data());
  }
}
