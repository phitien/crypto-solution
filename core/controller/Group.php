<?php
class Controller_Group extends Controller {
  public function __construct(Router $router) {
    parent::__construct($router);
    $this->_model = new Model_Group;
  }
  public function users() {
    $modelUser = new Model_User;
    $results = $modelUser
    ->joinadd('user_group', 'user_group.user_id=user.id')
    ->whereadd("user_group.group_id=".Request::uid())
    ->page(Request::data());
    $this->pinfo($modelUser->pinfo());
    return $results;
  }
  public function assign() {
    $model = new Model_UserGroup;
    return $model->set([
      'user_id' => Request::get('id'),
      'group_id' => Request::uid()
    ])->add();
  }
  public function remove() {
    $model = new Model_UserGroup;
    return $model->find(false, [
      'user_id' => Request::get('id'),
      'group_id' => Request::uid()
    ])->remove(false);
  }
}
