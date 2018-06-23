<?php
class Model_Group extends Model {
	protected $table = 'group';
	protected $pk = 'id';
	protected $_fields = [
		'id','name','description','status',
	];
}
