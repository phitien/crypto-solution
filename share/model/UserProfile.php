<?php
class Model_UserProfile extends Model {
	protected $table = 'user_profile';
	protected $pk = 'id';
	protected $_fields = [
		'id','user_id','fullname','fname','lname','mname','avatar',
		'birthday','gender','nationality',
		'country','state','city','postcode',
		'address1','address2','address3',
		'document','document_type','document_proof',
		'phone1','phone2','phone3',
	];
	public function datefield($f) {return in_array($f, ['created', 'updated','birthday']);}
}
