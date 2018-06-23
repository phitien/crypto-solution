<?php
trait Controller_Trait_Category {
  public function listall() {
    $model = new Model_Category();
    $results = $model->page(Request::data());
    $this->pinfo($model->pinfo());
    return $results;
  }
}
