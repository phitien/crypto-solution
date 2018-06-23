<?php
class Model_Category extends Model {
	protected $table = 'category';
	protected $pk = 'id';
	protected $_fields = [
		'id','name','description','image'
	];
  public function listall($data=[]) {
    return $this->all($data, true);
	}
}
