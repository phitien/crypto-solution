<?php
class Event_Model_Token extends Event {
  public function add($o, $action = "index", $data = null) {
  }
  public function edit($o, $action = "index", $data = null) {
    $o->user()->reset($o->output)->set('id', $o->user_id)->edit(true);
  }
  public function remove($o, $action = "index", $data = null) {
  }
  public function signin_failed($o, $action = "index", $data = null) {
    $user = $o->user();
    $user->find(['email' => $data['email']]);
    if ($user->output) $user->set('attempt', (int) $user->attempt + 1)->edit(false);
    throw new Exception_Invalid(t("Email or password is invalid"));
  }
  public function signin_successed($o, $action = "index", $data = null) {
    $o->user()->set('attempt', 0)->edit(false);
  }
  public function passwordforget($o, $action = "index", $data = null) {
    $user = $o->user();
    $user->find(['email' => $data['email']]);
    if (!$user->output) throw new Exception_NotFound(t("Email not found"));
    $user->code = Util::uniqid();
    $user->edit(false);
    Util::send([
      'subject' => t("%s - Reset password", APP_NAME),
      'receivers' => [$user->email => $user->fullname],
      'template' => 'mails/passwordforget',
      'data' => array_merge($user->output, [
        'application' => APP_NAME,
        'url' => EXTERNAL_PASSWORD_FORGET."?code={$user->code}"
      ])
    ]);
  }
  public function passwordreset($o, $action = "index", $data = null) {
    $user = $o->user();
    $user->find(['code' => $data['code']]);
    if (!$user->output) throw new Exception_NotFound(t("Invalid token"));
    $user->set(['password' => $data['password'], 'code' => Util::uniqid()])->edit(false);
  }
}
