<?php
class Controller_Abstract extends Controller_View {
  public function __construct(Router $router, View $view) {
    parent::__construct($router, $view);
  }
}
