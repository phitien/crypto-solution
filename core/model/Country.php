<?php
class Model_Country extends Model {
	protected $table = 'country';
	protected $pk = 'id';
	protected $_fields = [
		'id','name','description','status',
	];
}
