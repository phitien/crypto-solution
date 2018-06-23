<?php
class Router {
  protected $_app;
  protected $_routes;
  protected $_route;
  protected $_authentication;
  protected $_authorization;
  protected $_controllerName;
  protected $_controller;
  protected $_actionName;
  protected $_handler;
  public function __construct(App $app, $routes = null) {
    $this->_app = $app;
    $this->_routes = Request::get('routes');
    $this->_set_controller();
    $this->_authentication = new Authentication($this);
    $this->_authentication->execute();
    $this->_authorization = new Authorization($this);
    $this->_authorization->execute();
  }
  protected final function _set_controller() {
    if (empty(Request::get('controller'))) Request::set('controller', 'index');
    $this->_controllerName = ucfirst(Request::get('controller'));
    $klass = "Controller_{$this->_controllerName}";
    $this->_controller = new $klass($this);
    $this->_set_action();
  }
  protected final function _set_action() {
    if (empty(Request::get('action'))) Request::set('action', 'index');
    $this->_actionName = Request::get('action');
    $this->_set_route();
  }
  protected final function _set_route() {
    $cname = lcfirst($this->_controllerName);
    $aname = $this->_actionName;
    if (empty($this->_routes[$cname])) throw new Exception_NotFound(sprintf("Controller '%s' is not accessible", $this->_controllerName));
    $routes = $this->_routes[$cname]['actions'];
    if (empty($routes[$aname])) throw new Exception_NotFound(sprintf("Action '%s' is not accessible in Controller '%s'", $aname, $this->_controllerName));
    $this->_route = $route = $routes[$aname];
    $this->_set_handler();
  }
  protected final function _set_handler() {
    $aname = $this->_actionName;
    $route = $this->_route;
    $method = $route['method'];
    $rmethod = Request::method();
    $handler = empty($route['handler']) ? $aname : $route['handler'];
    if (!is_string($method) && array_key_exists($rmethod, $method)) $handler = $method[$rmethod];
    if (
      empty($method)
      || in_array($method, ['*', 'all', 'any'])
      || $method == $rmethod
      || (!is_string($method) && array_key_exists($rmethod, $method))
    ) {}//do nothing
    else throw new Exception_Invalid(sprintf("Method '%s' is not supported", $rmethod));
    if (!method_exists($this->_controller, $handler)) throw new Exception_NotFound(sprintf("Handler '%s' is missing in Controller '%s'", $handler, $this->_controllerName));
    $reflection = new ReflectionMethod($this->_controller, $handler);
    if (!$reflection->isPublic()) throw new Exception_NotFound(sprintf("Hanlder '%s' is not accessible in Controller '%s'", $handler, $this->_controllerName));
    $this->_handler = $handler;
    Log::info("Controller:"."Controller_{$this->_controllerName}");
    Log::info("Action:".$this->_actionName);
    Log::info("Handler:".$this->_handler);
    Log::info("Route", $this->_route);
    Log::info("RequestMethod", $rmethod);
    // Log::info('Request', Request::data());
    return $this;
  }
  public final function app() {return $this->_app;}
  public final function controller() {return $this->_controller;}
  public final function action() {return $this->_actionName;}
  public final function handler() {return $this->_handler;}
  public final function routes() {return $this->_routes;}
  public final function route() {return $this->_route;}
  public final function authentication() {return $this->_authentication;}
  public final function user() {return $this->authentication()->user();}
  public final function authorization() {return $this->_authorization;}
  public final function response() {return $this->controller()->{$this->handler()}();}

}
