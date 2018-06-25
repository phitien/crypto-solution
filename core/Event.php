<?php
class Event {
  public final static function subscribe() {
    $args = func_get_args();
  }
  public final static function publish() {
    $args = func_get_args();
    $callee = $args[0];
    $action = $args[1];
    $klass = "Event_".get_class($callee);
    if (!class_exists($klass)) return;
    $instance = new $klass;
    Log::info("Hanlder:$klass::$action");
    Log::info("Callee", $callee);
    if (method_exists($instance, $action)) return call_user_func_array([$instance, $action], $args);
  }
}
