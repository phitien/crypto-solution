<?php
class Controller_Index extends Controller {
  public function signin() {
    if (!empty(Request::get('facebook'))) $this->response($this->user()->signin(Request::data(), 'facebook'));
		if (!empty(Request::get('twitter'))) $this->response($this->user()->signin(Request::data(), 'twitter'));
		if (!empty(Request::get('linkedin'))) $this->response($this->user()->signin(Request::data(), 'linkedin'));
    return $this->user()->signin(Request::data('email', 'password'));
  }
  public function signup() {
    if (!empty(Request::get('facebook'))) $this->response($this->user()->signin(Request::data(), 'facebook'));
		if (!empty(Request::get('twitter'))) $this->response($this->user()->signin(Request::data(), 'twitter'));
		if (!empty(Request::get('linkedin'))) $this->response($this->user()->signin(Request::data(), 'linkedin'));
		return $this->user()->signup(Request::data());
  }
  public function signout() {return $this->user()->signout();}
  public function activate() {return $this->user()->activate(Request::data('code'));}
  public function profile() {return $this->user();}
  public function profileupdate() {return $this->user()->set(Request::data())->edit();}
  public function password() {return $this->user()->passwordchange(Request::data('opassword', 'password', 'confirm'));}
  public function forget() {return $this->user()->passwordforget(Request::data('email'));}
  public function reset() {return $this->user()->passwordreset(Request::data('code', 'password', 'confirm'));}
}
