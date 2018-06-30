<?php
class Controller_View extends Controller {
  public function __construct(Router $router, View $view) {
    parent::__construct($router, $view);
  }
  public function load_countries() {
    $this->countries = Cache::load('countries', array_map(function($o) {
      return ['text' => $o['name'], 'value' => $o['alpha3Code']];
    }, Util::json(COUNTRY_PATH)));
  }
  public function load_topmenu() {
    $this->topmenu = Cache::load('topmenu', Template::renderMenu([
      'parent_id' => Request::get('parent_id', 1),
      'recursive' => true,
      'class'=> 'topmenu',
      'max' => 4
    ]));
  }
}
