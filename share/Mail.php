<?php
use Zend\Mail\Message;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

class Mail {
  public static function send($options) {
    return self::{MAILER}($options);
  }
  public static function receivers($options) {
    return array_filter(array_merge([], $options['receivers']));
  }
  public static function subject($options) {
    return $options['subject'];
  }
  public static function body($options) {
    return $options['body'];
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
