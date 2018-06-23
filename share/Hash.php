<?php
class Hash {
  public static function make($str, $algo = HASH_ALGO) {
    return hash($algo, $str);
  }
}
