<?php
trait Controller_Trait_Auth {
  public function signin() {
    $model = new Model_Token();
    if (!empty(Request::get('facebook'))) $this->response($model->login_facebook(Request::data()));
		if (!empty(Request::get('twitter'))) $this->response($model->login_twitter(Request::data()));
		if (!empty(Request::get('linkedin'))) $this->response($model->login_linkedin(Request::data()));
    return $model->signin(Request::data('email', 'password'));
  }
  public function signup() {
    $model = new Model_Token();
    if (!empty(Request::get('facebook'))) $this->response($model->login_facebook(Request::data()));
		if (!empty(Request::get('twitter'))) $this->response($model->login_twitter(Request::data()));
		if (!empty(Request::get('linkedin'))) $this->response($model->login_linkedin(Request::data()));
		return $model->signup(Request::data());
  }
  public function signout() {
    $model = new Model_Token();
    return $model->signout(Request::data('token'));
  }
  public function activate() {
    $model = new Model_Token();
    return $model->activate(Request::data('code'));
  }
  public function profile() {return $this->user();}
  public function profileupdate() {return $this->user()->setData(Request::data())->edit();}
  public function password() {
    return $this->user()->passwordchange(Request::data('opassword', 'password', 'confirm'));
  }
  public function forget() {
    $model = new Model_Token();
    return $model->passwordforget(Request::data('email'));
  }
  public function reset() {
    $model = new Model_Token();
    return $model->passwordreset(Request::data('code'));
  }
}
