<?php
class Application {
  protected $_router;
  protected $_mime_types = [
    'json' => 'application/json',
    'javascript' => 'application/javascript',
    'ecmascript' => 'application/ecmascript',
    'js' => 'application/javascript',
    'plain' => 'text/plain',
    'html' => 'text/html',
    'css' => 'text/css',
    'csv' => 'text/csv',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'mpeg' => 'audio/mpeg',
    'ogg' => 'audio/ogg',
    'audio' => 'audio/*',
    'mp4' => 'video/mp4',
  ];
  public function __construct($routes = null) {
    set_exception_handler([$this, 'exception_handler']);
    Session::init();
    Request::init();
    Database::init();
    Template::init();
    $this->router()->authenticate();
    $this->router()->authorise();
    $this->response();
  }
  public final function response() {return $this->results($this->router()->response());}
  public final function router() {
    if (!$this->_router) $this->_router = new Router($this);
    return $this->_router;
  }
  public function results($results = null, $status = 200) {
    ob_end_clean();
    ob_start();
    $mime_type = $this->router()->mime_type();
    header("HTTP/1.1 $status ERROR");
    header("Content-Type: $mime_type");
    print_r(is_string($results) ? $results : json_encode($results));
  }
  public function error($error, $status = 500) {$this->results(is_object($error) ? $error : ['error' => $error], $status);}
  public function exception_handler(Exception $e) {
    $mime_type = $this->router()->mime_type();
    if ($mime_type == 'html' && $e instanceof Exception_Forbidden) {//render login page
      $this->results($this->router()->error(), 401);
    }
    else {
      if (!NODEBUG && DEBUG) {
        if (!$e instanceof Exception_Exception) Log::info($e);
      }
      $this->error($e->getMessage(), $e->getCode());
    }
  }
}
