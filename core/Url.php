<?php
class Url {
  public final static function get($o, $type = null) {
    if ($type == 'category') return "?controller=category&uid={$o['id']}";
    //TODO
    return $o['url'];
  }
}
