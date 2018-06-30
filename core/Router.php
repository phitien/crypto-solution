<?php
class Router {
  protected static $_routes = [];
  protected $_app;
  protected $_route;
  protected $_authentication;
  protected $_authorization;
  protected $_controllerName;
  protected $_controller;
  protected $_view;
  protected $_actionName;
  protected $_handler;
  protected $_public;
  protected $_mime_type = APP_TYPE;
  protected $_template = null;
  protected $_pinfo;
  public function __construct(App $app, $routes = null) {
    $this->_app = $app;
    $this->_set_controller();
    $this->_set_action();
    $this->_set_route();
    $this->_set_params();
    $this->_set_view();
    $this->_init();
  }
  public final function authenticate() {
    $this->_authentication = new Acl_Authentication($this);
    $this->_authentication->execute();
  }
  public final function authorise() {
    $this->_authorization = new Acl_Authorization($this);
    $this->_authorization->execute();
  }
  protected final function _set_controller() {
    if (empty(Request::get('controller'))) Request::set('controller', 'index');
    $this->_controllerName = ucfirst(Request::get('controller'));
  }
  protected final function _set_action() {
    if (empty(Request::get('action'))) Request::set('action', 'index');
    $uid = Request::get('uid');
    $this->_actionName = Request::get('action');
    $this->_actionName = $uid ? "{$this->_actionName}/:uid" : $this->_actionName;
    Log::info("Action: ".$this->action());
  }
  protected final function _set_route() {
    $cname = lcfirst($this->_controllerName);
    $aname = $this->_actionName;
    if (!array_key_exists($cname, self::$_routes)) throw new Exception_NotFound(sprintf("Controller '%s' is not accessible", $this->_controllerName));
    $routes = self::$_routes[$cname]['actions'];
    if (!array_key_exists($aname, $routes)) throw new Exception_NotFound(sprintf("Action '%s' is not accessible in Controller '%s'", $aname, $this->_controllerName));
    $this->_route = $route = $routes[$aname];
    $this->_public = true;
    Log::info("Route: $aname : ", $this->route());
  }
  //'json' => ['mime-type' => 'json'],
  //'json' => ['public' => true, 'mime-type' => 'json'],
  //'json' => ['public' => true, 'method' => 'get', 'mime-type' => 'json'],
  //'json' => ['public' => true, 'method' => ['handler' => 'json', 'mime-type' => 'json']],
  //'json' => ['public' => true, 'method' => ['get' => 'json', 'post' => '*json', 'mime-type' => 'json']],
  //'json' => ['public' => true, 'method' => [
  //  'get' => ['handler' => 'json', 'mime-type' => 'json'],
  //  'post' => '*json',
  //  'mime-type' => 'json'
  //]],
  // PUT * before handler for private access
  protected final function _check_access($route) {
    $public = isset($route['public']) ? ($route['public'] ? true : false) : true;
    if (!isset($route['method'])) return $public;
    $method = $route['method'];
    if (in_array($method, ['*','all','any'])) return $public;
    $rmethod = Request::method();
    if (is_string($route['method'])) return $method == $rmethod;
    if (!isset($method[$rmethod])) return $public;
    return array_key_exists($rmethod, $method);
  }
  protected final function _get_mime_type($route) {
    if (Request::ajax()) return 'json';
    $mime_type = APP_TYPE;
    if (isset($route['mime-type'])) $mime_type = $route['mime-type'];
    if (isset($route['method']) && !is_string($route['method'])) {
      $route = $route['method'];
      if (isset($route['mime-type'])) $mime_type = $route['mime-type'];
      $rmethod = Request::method();
      if (isset($route[$rmethod])) {
        $route = $route[$rmethod];
        if (isset($route['mime-type'])) $mime_type = $route['mime-type'];
      }
    }
    return $mime_type;
  }
  protected final function _get_handler($route) {
    $aname = strpos($this->_actionName, "/:") ? explode("/", $this->_actionName)[0] : $this->_actionName;
    $handler = isset($route['handler']) ? $route['handler'] : $aname;
    if (!isset($route['method'])) return $handler;
    if (is_string($route['method'])) return $handler;
    $rmethod = Request::method();
    $handler = isset($route['method']['handler']) ? $route['method']['handler'] : $handler;
    if (!isset($route['method'][$rmethod])) return $handler;
    if (is_string($route['method'][$rmethod])) return $route['method'][$rmethod];
    $handler = isset($route['method'][$rmethod]['handler']) ? $route['method'][$rmethod]['handler'] : $handler;
    return $handler;
  }
  protected final function _set_params() {
    $route = $this->route();
    $rmethod = Request::method();
    $this->_public = $this->_check_access($route);
    $this->_mime_type = $this->_get_mime_type($route);
    $this->_handler = $this->_get_handler($route);
    $handler = $this->handler();
    if (strpos($handler, "*") !== false) {
      $this->_handler = str_replace("*", "", $handler);
      $this->_public = false;
    }
    Log::info("RequestMethod: ".Request::method());
    Log::info("Public: ".$this->isPublic());
    Log::info("MimeType: ".$this->mime_type());
    Log::info("Handler: ".$this->handler());
    return $this;
  }
  protected final function _set_view() {
    $route = $this->route();
    if ($this->mime_type() != 'json') {
      $this->_template = !empty($route['view']) ? $route['view'] :
        lcfirst($this->_controllerName)."/".lcfirst($this->handler());
    }
    $this->_view = new View($this->template());
    Log::info("Template: ".$this->template());
    return $this;
  }
  protected final function _init() {
    $klass = "Controller_{$this->_controllerName}";
    $this->_controller = new $klass($this, $this->view());
    $this->view()->controller($this->controller());
    $handler = $this->handler();
    if (!method_exists($this->controller(), $handler))
      throw new Exception_NotFound(sprintf("Handler '%s' is missing in Controller '%s'", $handler, $this->_controllerName));
    Log::info("Controller: "."Controller_{$this->_controllerName}");
    return $this;
  }
  public final function app() {return $this->_app;}
  public final function controller() {return $this->_controller;}
  public final function view() {return $this->_view;}
  public final function action() {return $this->_actionName;}
  public final function isPublic() {return $this->_public;}
  public final function handler() {return $this->_handler;}
  public final function routes() {return self::$_routes;}
  public final function route() {return $this->_route;}
  public final function authentication() {return $this->_authentication;}
  public final function user() {return $this->authentication()->user();}
  public final function authorization() {return $this->_authorization;}
  public final function mime_type() {return $this->_mime_type;}
  public final function template() {return $this->_template;}
  public final static function add($n, $v = null) {
    $actions = [
      'index' => ['method' => ['get' => 'index', 'post' => '*create']],
      'all' => [],
      'index/:uid' => ['method' => ['get' => 'detail', 'put' => '*update', 'delete' => '*delete']],
    ];
    $controller = $v && $v['controller'] ? $v['controller'] : ucfirst($n);
    $actions = array_merge($actions, $v && $v['actions'] ? $v['actions'] : []);
    self::$_routes[$n] = [
      'controller' => $controller,
      'actions' => $actions,
    ];
  }
  public function pinfo($pinfo = null) {$this->_pinfo = $pinfo;return $this;}
  public final function response() {
    $results = $this->controller()->{$this->handler()}();
    if ($results instanceof Model) {
      $results = $results->output;
      $this->view()->assign('results', $results);
    }
    if ($this->mime_type() == 'json') {
      $res = new stdClass();
      $res->results = $results;
      if ($this->_pinfo) $res->pinfo = $this->_pinfo;
      if (DEBUG && !empty(Session::logs())) {
        $res->debug = Session::logs();
        Session::clean();
      }
      $this->view()->assign('response', $res);
    }
    $this->controller()->{$this->handler()}();
    return $this->view()->render();
  }
  public final function error() {
    $this->controller()->error();
    $this->controller()->layout('login');
    return $this->view()->render();
  }
}
