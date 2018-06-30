<?php
class Controller_Index extends Controller_Abstract {
  public function index() {
    $this->hasLeft(false);
    $this->hasRight(false);
    $this->load_marketprices();
    $this->load_sortby_options();
    $this->load_countries();
    $this->load_categories();
    $this->load_categories_items();
  }
  public function search() {
    $this->template('index/index');
    $this->hasLeft(false);
    $this->hasRight(false);
    $this->load_marketprices();
    $this->load_sortby_options();
    $this->load_countries();
    $this->load_categories();
    $this->load_categories_items();
  }
}
