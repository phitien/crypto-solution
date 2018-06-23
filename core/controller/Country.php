<?php
class Controller_Country extends Controller {
  public function __construct(Router $router) {
    parent::__construct($router);
    $this->_model = new Model_Country;
  }
  public function all() {return Util::json(COUNTRY_PATH);}
}
