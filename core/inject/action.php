<?php
trait Inject_Action {
  public function index() {
    $results = $this->model()->page(Request::data());
    $this->pinfo($this->model()->pinfo());
    return $results;
  }
  public function all() {return $this->model()->fetch(Request::data());}
  public function detail() {return $this->model()->load(Request::uid());}
  public function create() {return $this->model()->load(Request::uid())->set(Request::data())->add();}
  public function update() {return $this->model()->load(Request::uid())->set(Request::data())->edit();}
  public function delete() {return $this->model()->load(Request::uid())->remove();}
}
