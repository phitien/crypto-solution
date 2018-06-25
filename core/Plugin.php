<?php
class Plugin {
  public function runbefore() {return false;}
  public function enhance($res) {
    return $res;
  }
}
