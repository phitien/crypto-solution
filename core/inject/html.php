<?php
trait Inject_Html {
  public final function script() {return Html_Script::data();}
  public final function scriptadd($v) {return new Html_Script($v);}
  public final function scriptremove($v) {return (new Html_Script($v))->delete();}

  public final function style() {return Html_Style::data();}
  public final function styleadd($v) {return new Html_Style($v);}
  public final function styleremove($v) {return (new Html_Style($v))->delete();}

  public final function meta() {return Html_Meta::data();}
  public final function metaadd($v) {return new Html_Meta($v);}
  public final function metaremove($v) {return (new Html_Meta($v))->delete();}

  public final function favicon() {return Html_Favicon::data();}
  public final function faviconadd($v) {return new Html_Favicon($v);}
  public final function faviconremove($v) {return (new Html_Favicon($v))->delete();}
}
