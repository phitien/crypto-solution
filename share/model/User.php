<?php
class Model_User extends Model {
	protected $table = 'user';
	protected $pk = 'id';
	protected $_fields = [
		'id','email','facebook','twitter','linkedin',
		'facebook_token','twitter_token','linkedin_token',
		'created', 'updated',
		'code','status',
		'password',
	];
	protected $_joins = [
		'user_profile' => "`user_profile`.`id`=`user_profile`.`user_id`",
	];
	public function joinfields() {
		return array_merge(['user.id AS user_id', 'user_profile.id AS user_profile_id'], $this->fields([
			'fullname','fname','lname','mname','avatar',
			'birthday','gender','nationality',
			'country','state','city','postcode',
			'address1','address2','address3',
			'document','document_type','document_proof',
			'phone1','phone2','phone3',
		], 'user_profile'));
	}
	public function datefield($f) {return in_array($f, ['created', 'updated', 'expired', 'birthday']);}
	public function pwdfield($f) {return in_array($f, ['password']);}
	public function normalise($adding = true) {
		if (!empty($this->facebook) && empty($this->facebook_token)) throw new Exception_Invalid('Invalid facebook token');
		if (!empty($this->twitter) && empty($this->twitter_token)) throw new Exception_Invalid('Invalid twitter token');
		if (!empty($this->linkedin) && empty($this->linkedin_token)) throw new Exception_Invalid('Invalid linkedin token');
		if (!empty($this->password)) {
			if (strlen($this->password) < 6) throw new Exception_Invalid("Password is invalid, must have at least 6 characters");
			if (empty($this->confirm)) throw new Exception_Invalid('Confirm password is missing');
			if ($this->password != $this->confirm) throw new Exception_Invalid('Password and Confirm password is not matched');
		}
		$this->code = Util::uniqid();
		if (!EMAIL_VERIFICATION && $adding) $this->status = 'active';
		if ($adding) $this->created = Util::now();
		else $this->updated = Util::now();
		return parent::normalise($adding);
	}
	public function after($new = false) {
		$profile = new Model_UserProfile();
		$profile->setData($this->output)->upsert('user_id');
		return parent::after($new);
	}
}
