<?php
class Event {
  public final static function subscribe() {
    $args = func_get_args();
  }
  public final static function publish() {
    $args = func_get_args();
    $type = array_shift($args);
    $action = array_shift($args);
    $klass = "Event_$type";
    $instance = new $klass;
    call_user_func_array([$instance, $action], $args);
  }
}
