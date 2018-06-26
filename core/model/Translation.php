<?php
class Model_Translation extends Model {
	protected $table = 'translation';
	protected $pk = 'id';
	protected $_fields = [
		'id','lang','ref_id','ref_table','origin','copy','tag',
	];
}
