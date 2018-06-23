<?php
class Model_Menu extends Model {
	protected $table = 'menu';
	protected $pk = 'id';
	protected $_fields = [
		'id','name','url','description','extra',
	];
}
