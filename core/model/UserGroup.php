<?php
class Model_UserGroup extends Model {
	protected $table = 'user_group';
	protected $pk = 'id';
	protected $_fields = [
		'id','user_id','group_id',
	];
}
