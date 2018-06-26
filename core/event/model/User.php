<?php
class Event_Model_User extends Event {
  public function add($user, $action = "index", $data = null) {
    Log::info('User added');
    $profile = new Model_Profile();
		$profile->set($user->output)->set('user_id', $user->id)->add(false);
    if (EMAIL_VERIFICATION)
    Util::send([
      'subject' => t("%s - Email Verification", APP_NAME),
      'receivers' => [$user->email => $user->fullname],
      'template' => 'mails/email_verification',
      'data' => array_merge($user->output, [
        'application' => APP_NAME,
        'url' => EXTERNAL_EMAIL_VERIFICATION."?code={$user->code}"
      ])
    ]);
  }
  public function edit($o, $action = "index", $data = null) {
    $profile = new Model_Profile();
		$profile->set($o->output)->edit(false);
  }
  public function remove($o, $action = "index", $data = null) {
  }
}
