<?php
class Url {
  public final static function get($o, $type = null) {
    if ($type == 'category') return "?controller=index&action=search&category={$o['id']}";
    //TODO
    return $o['url'];
  }
}
