<?php
class Event_View {
  public function beforerender($view, $action = "index", $tpl, $res) {
    //TODO integrate plugins by modify $html
    $tpl->setViewParams();
  }
  public function afterrender($view, $action = "index", $tpl, $res) {
    //TODO integrate plugins by modify $html
  }
}
