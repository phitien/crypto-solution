<?php
class Controller_Country extends Controller {
  public function __construct(Router $router, View $view) {
    parent::__construct($router, $view);
    $this->_model = new Model_Country;
  }
  public function all() {return Util::json(COUNTRY_PATH);}
}
