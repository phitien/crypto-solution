<?php
class Application {
  protected $_router;
  protected $_pinfo;
  protected $_mime_types = [
    'json' => 'application/json',
    'api' => 'application/json',
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
  protected $_mime_type = APP_TYPE;
  public function __construct($routes = null) {
    set_exception_handler([$this, 'exception_handler']);
    Session::init();
    Request::init();
    Database::init();
    $this->_router = new Router($this);
    $this->response();
  }
  public final function response() {return $this->results($this->_router->response());}
  public final function router() {return $this->_router;}
  public final function setMimeType($v) {$this->_mime_type = $v;return $this;}
  public function api_results($results = null) {
    $data = $results instanceof Model ? $results->output : $results;
    $res = new stdClass();
    $res->results = $data;
    if ($this->_pinfo) $res->pinfo = $this->_pinfo;
    if (DEBUG && !empty(Session::logs())) {
      $res->debug = Session::logs();
      Session::clean();
    }
    print_r(json_encode($res));
  }
  public function results($results = null, $status = 200) {
    ob_end_clean();
    ob_start();
    $mime_type = $this->_mime_types[$this->_mime_type];
    if (!$mime_type) $mime_type = $this->_mime_types['html'];
    header("HTTP/1.1 $status ERROR");
    header("Content-Type: $mime_type");
    if (in_array($this->_mime_type, ['api', 'json'])) return $this->api_results($results);
    print_r($results);
  }
  public function error($error, $status = 500) {$this->results(is_object($error) ? $error : ['error' => $error], $status);}
  public function pinfo($pinfo = null) {$this->_pinfo = $pinfo;return $this;}
  public function exception_handler(Exception $e) {
    if (!NODEBUG && DEBUG) {
      if (!$e instanceof Exception_Exception) Log::info($e);
    }
    $this->error($e->getMessage(), $e->getCode());
  }
}
