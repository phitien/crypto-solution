<?php
class Model_Category extends Model {
	protected $table = 'category';
	protected $pk = 'id';
	protected $_fields = [
		'id','type_id','name','description','image','status'
	];
  public function listall($data=[]) {
    return $this->all($data, true);
	}
}
