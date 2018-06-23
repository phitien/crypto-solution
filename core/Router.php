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
  protected $_template = APP_TYPE;
  public function __construct(App $app, $routes = null) {
    $this->_app = $app;
    $this->_set_mime_type();
    $this->_set_controller();
    $this->_set_action();
    $this->_set_route();
    $this->_set_handler();
    $this->_set_view();
    $this->_check_controller();
    $this->_authentication = new Authentication($this);
    $this->_authentication->execute();
    $this->_authorization = new Authorization($this);
    $this->_authorization->execute();
  }
  protected final function _set_mime_type() {
    if (Request::get('api')) $this->_mime_type = 'json';
    if ($this->_mime_type == 'api') $this->_mime_type = 'json';
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
    Log::info("Action:".$this->action());
  }
  protected final function _set_route() {
    $cname = lcfirst($this->_controllerName);
    $aname = $this->_actionName;
    if (!array_key_exists($cname, self::$_routes)) throw new Exception_NotFound(sprintf("Controller '%s' is not accessible", $this->_controllerName));
    $routes = self::$_routes[$cname]['actions'];
    if (!array_key_exists($aname, $routes)) throw new Exception_NotFound(sprintf("Action '%s' is not accessible in Controller '%s'", $aname, $this->_controllerName));
    $this->_route = $route = $routes[$aname];
    $this->_public = true;
    if (isset($route['public'])) $this->_public = $route['public'] ? true : false;
    Log::info("Route", $this->route());
  }
  protected final function _set_handler() {
    $aname = strpos($this->_actionName, "/:") ? explode("/", $this->_actionName)[0] : $this->_actionName;
    $route = $this->route();
    $method = $route['method'];
    $rmethod = Request::method();
    Log::info("RequestMethod:".Request::method());
    $handler = empty($route['handler']) ? $aname : $route['handler'];
    if (!is_string($method) && array_key_exists($rmethod, $method)) $handler = $method[$rmethod];
    if (
      empty($method)
      || in_array($method, ['*', 'all', 'any'])
      || $method == $rmethod
      || (!is_string($method) && array_key_exists($rmethod, $method))
    ) {}//do nothing
    else throw new Exception_Invalid(sprintf("Method '%s' is not supported", $rmethod));
    if (strpos($handler, "*") !== false) {
      $handler = str_replace("*", "", $handler);
      $this->_public = false;
    }
    $this->_handler = $handler;
    Log::info("Handler:".$this->handler());
    Log::info("Public:".$this->isPublic());
    return $this;
  }
  protected final function _set_view() {
    $route = $this->route();
    if ($this->mime_type() != 'json') {
      $this->_template = !empty($route['view']) ? $route['view'] :
        lcfirst($this->_controllerName)."/".lcfirst($this->handler());
      Log::info("Template:".$this->template());
    }
    $this->_view = new View($this->template());
  }
  protected final function _check_controller() {
    $klass = "Controller_{$this->_controllerName}";
    $this->_controller = new $klass($this, $this->view());
    Log::info("Controller:"."Controller_{$this->_controllerName}");
    if (!method_exists($this->controller(), $this->handler()))
      throw new Exception_NotFound(sprintf("Handler '%s' is missing in Controller '%s'", $handler, $this->_controllerName));
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
  public final function response() {
    if ($this->mime_type() == 'json') return $this->controller()->{$this->handler()}();
    $this->controller()->{$this->handler()}();
    return $this->view()->render();
  }
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
}
