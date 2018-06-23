<?php
class Model_Item extends Model {
	protected $table = 'item';
	protected $pk = 'id';
	protected $_fields = [
		'id','category_id','name','description','image','status'
	];
  public function listall($data=[]) {
    return $this->all($data, true);
	}
}
