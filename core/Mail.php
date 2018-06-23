<?php
use Zend\Mail\Message;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

class Mail {
  public static function send($options) {
    try {
      return self::{MAILER}($options);
    }
    catch(Exception $e) {
      Log::error($e);
    }
  }
  public static function receivers($options) {
    return array_filter(array_merge([], $options['receivers']));
  }
  public static function subject($options) {
    return @$options['subject'];
  }
  public static function body($options) {
    $body = @$options['body'];
    if (empty($body) && $tpl = self::template($options)) {
      $tpl = new Template(self::data($options));
      $body = $tpl->render('email_verification');
    }
    return $body;
  }
  public static function template($options) {
    return @$options['template'];
  }
  public static function data($options) {
    return @$options['data'];
  }
  public static function swiftmailer($options) {
    $receivers = self::receivers($options);
    if (empty($receivers)) return;
    $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
      ->setUsername(EMAIL_ACCOUNT)
      ->setPassword(EMAIL_PASSWORD);
    $mailer = Swift_Mailer::newInstance($transporter);
    $message = (new Swift_Message(self::subject($options)))
      ->setFrom([EMAIL_SENDER_EMAIL => EMAIL_SENDER_NAME])
      ->setTo($receivers)
      ->setBody(self::body($options))
      ;
    $result = $mailer->send($message);
  }
  public static function zend_mail($options) {
    $receivers = self::receivers($options);
    if (empty($receivers)) return;
    $message = new Message();
    $message->setSubject(self::subject($options))->setFrom(EMAIL_SENDER_EMAIL, EMAIL_SENDER_NAME);
    $body = self::body($options);
    $type = empty($options['type']) ? 'text/html' : $options['type'];
    if ($type == 'text/html') {
      $html = new MimePart($body);
      $html->type = $type;
      $body = new MimeMessage();
      $body->addPart($html);
      $message->setBody($body);
    }
    else $message->setBodyText($body);
    foreach($receivers as $email => $o) $message->addTo($email, is_string($o) ? $o : '');
    $transport = new Smtp(new SmtpOptions([
      'host' => 'smtp.gmail.com',
      'port' => 465,
      'ssl' => 'ssl',
      'auth' => 'login',
      'username' => EMAIL_ACCOUNT,
      'password' => EMAIL_PASSWORD
    ]));
    $transport->send($message);
  }
}
