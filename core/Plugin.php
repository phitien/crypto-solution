<?php
class Plugin {
  public function type() {return 'html';}//html or sql
  public function runbefore() {return false;}
  public function enhance($res) {
    return $res;
  }
}
