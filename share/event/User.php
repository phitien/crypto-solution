<?php
class Event_User extends Event {
  public function add($o, $data) {
    Log::info('User added');
    $body = [];
		$o->activation_url = $activation_url = EXTERNAL_EMAIL_VERIFICATION."?code{$o->code}";
		array_push($body, sprintf("Hi %s", $o->fullname));
		array_push($body, sprintf("In order to continue with %s, please activate your account by clicking on the <a href='%s'>here</a>", APP_NAME, $activation_url));
		array_push($body, sprintf("Thank you."));
    if (EMAIL_VERIFICATION)
    try {
       Util::send([
        'subject' => sprintf("%s - Email Verification", APP_NAME),
        'body' => implode("<br/>", $body),
        'receivers' => [$o->email => $o->fullname]
      ]);
    }
    catch(Exception $e) {
      Log::error($e);
    }
  }
  public function edit($o, $data) {
    Log::info('User edited');
  }
  public function remove($o, $data) {
    Log::info('User removed');
  }
}
