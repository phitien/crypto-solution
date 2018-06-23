<?php
class Model_Type extends Model {
	protected $table = 'type';
	protected $pk = 'id';
	protected $_fields = [
		'id','name','description','image','status'
	];
  public function listall($data=[]) {
    return $this->all($data, true);
	}
}
