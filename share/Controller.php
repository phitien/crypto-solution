<?php
class Controller {
  public function __construct(Router $router) {$this->_router = $router;}
  public final function router() {return $this->_router;}
  public final function route() {return $this->router()->route();}
  public final function app() {return $this->router()->app();}
  public final function action() {return $this->router()->action();}
  public final function handler() {return $this->router()->handler();}
  public final function user() {return $this->router()->user();}
  public final function pinfo($pinfo = null) {$this->app()->pinfo($pinfo);}
  public final function setMimeType($v) {$this->app()->setMimeType($v);return $this;}
}
