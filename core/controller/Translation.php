<?php
class Controller_Translation extends Controller {
  public function __construct(Router $router, View $view) {
    parent::__construct($router, $view);
    $this->_model = new Model_Translation;
  }
}
