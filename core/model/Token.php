<?php
class Model_Token extends Model {
	protected $table = 'token';
	protected $pk = 'id';
	protected $_user;
	protected $_fields = [
		'id','user_id','token','device','created','expired','status'
	];
	protected $_joins = [
		'user' => "`token`.`user_id`=`user`.`id`",
		'profile' => "`profile`.`user_id`=`user`.`id`",
	];
	protected $_where = ["token.status='valid'", "token.expired >= NOW()"];
	public function joinfields() {
		return array_merge(['profile.id AS profile_id', 'token.id AS token_id'],
		$this->fields([
			'email','facebook','twitter','linkedin',
			'facebook_token','twitter_token','linkedin_token',
			'created','updated','attempt',
		], 'user'),
		$this->fields([
			'fullname','fname','lname','mname','avatar',
			'birthday','gender','nationality',
			'country','state','city','postcode',
			'address1','address2','address3',
			'document','document_type','document_proof',
			'phone1','phone2','phone3',
		], 'profile'));
	}
	public function datefield($f) {return in_array($f, ['created', 'updated', 'expired', 'birthday']);}
	public function pwdfield($f) {return in_array($f, ['password']);}
	/**
	 * @param Object data
	 */
	public function validate($token) {return $this->find(['token' => $token]);}
	/**
	 * @param Object data
	 */
	public function profile($data) {return $this->validate($data['token']);}
	public function user() {
		if (!$this->_user) {
			$this->_user = new Model_User;
			$this->_user->load($this->user_id);
		}
		return $this->_user;
	}
	/**
	 * @param Object user
	 */
	public function issue($force = false) {
		$user = $this->user();
		$user_id = $user->id;
		$this->find(['user_id' => $user_id]);
		if (!$force && $this->output) return $this;
		return $this->set([
			'user_id' => $user_id,
			'token' => Util::uniqid(),
			'expired' => Util::now(strtotime('+12 months')),
			'status' => 'valid'
		])->add(false);
	}
	/**
	 * @param Object data
	 */
	public function activate($data) {
		$code = @$data['code'];
		$user = $this->user()->find(['code' => $code]);
		if (!$user->output) throw new Exception_NotFound(t("Invalid token"));
		if ($user->output && $user->status == 'deactive')
			$user->set(['status' => 'active', 'code' => Util::uniqid()])->edit(false);
		return $this->find(['user_id' => $user->id]);
	}
	/**
	 * @param Object data
	 */
	public function signout($data) {
		if ($this->output) $this->set('status', 'expired')->edit(false);
		$this->reset(['id' => null, 'token' => null, 'facebook' => null, 'linkedin' => null, 'twitter' => null]);
		return $this;
	}
	/**
	 * @param Object data
	 */
	public function signup($data) {
		if (empty($data['email'])) throw new Exception_Invalid(t("Email is empty"));
		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
			throw new Exception_Invalid(t("Email is invalid"));
		if (empty($data['password']))
			throw new Exception_Invalid(t("Password is empty"));
		if (strlen($data['password']) < 6)
			throw new Exception_Invalid(t("Password is invalid, must have at least 6 characters"));
		if (empty($data['confirm']))
			throw new Exception_Invalid(t('Confirm password is missing'));
		if ($data['password'] != $data['confirm'])
			throw new Exception_Invalid(t('Password and Confirm password is not matched'));
		$this->user()->find(['email' => $data['email']]);
		if ($this->user()->output) throw new Exception_Invalid(t("Email is already used"));
		return $this->user()->set($data)->add(true);
	}
	/**
	 * @param Object data
	 */
	public function signin($data, $type = null) {
		if ($type) return $this->{"singin_$type"}($data);
		if (empty($data['email']))
			throw new Exception_Invalid(t("Email or password is invalid"));
		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
			throw new Exception_Invalid(t("Email or password is invalid"));
		if (empty($data['password']))
			throw new Exception_Invalid(t("Email or password is invalid"));
		if (strlen($data['password']) < 6)
			throw new Exception_Invalid(t("Email or password is invalid"));
		$user = $this->user();
		$user->find(['email' => $data['email'], 'password' => $data['password']]);
		if (!$user->output) return Event::publish($this, "signin_failed", $data);
		Event::publish($this, "signin_successed", $data);
		return $this->issue();
	}
	protected function signin_facebook($data) {
		if (!empty($data['facebook_token'])) return false;
		if (!Facebook::validate($data)) return false;
		$this->user()->set($data)->upsert('facebook', true);
		return $this->issue();
	}
	protected function signin_twitter($data) {
		if (!empty($data['twitter_token'])) return false;
		if (!Twitter::validate($data)) return false;
		$this->user()->set($data)->upsert('twitter', true);
		return $this->issue();
	}
	protected function signin_linkedin($data) {
		if (!empty($data['linkedin_token'])) return false;
		if (!LinkedIn::validate($data)) return false;
		$this->user()->set($data)->upsert('linkedin', true);
		return $this->issue();
	}
	/**
	 * @param Object data
	 */
	public function passwordchange($data) {
		if (empty($data['opassword']))
			throw new Exception_Invalid(t("Old Password is missing"));
		if ($data['opassword'] == $data['password'])
			throw new Exception_Invalid(t("New Password must be different from Old Password"));
		if (empty($data['password']))
			throw new Exception_Invalid(t("Password is invalid"));
		if (strlen($data['password']) < 6)
			throw new Exception_Invalid(t("Password is invalid, must have at least 6 characters"));
		if (empty($data['confirm']))
			throw new Exception_Invalid(t("Confirm Password is missing"));
		if ($data['password'] != $data['confirm'])
			throw new Exception_Invalid(t("Confirm Password does not match"));
		$this->signin(['email' => $this->email, 'password' => $data['opassword']]);
		$this->user()->set('password', $data['password'])->edit(false);
		return $this;
	}
	/**
	 * @param Object data
	 */
	public function passwordforget($data) {
		if (empty($data['email']))
			throw new Exception_Invalid("Email is missing");
		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
			throw new Exception_Invalid("Email is invalid");
		Event::publish($this, "passwordforget", $data);
		return $this;
	}
	/**
	 * @param Object data
	 */
	public function passwordreset($data) {
		if (empty($data['code']))
			throw new Exception_Invalid(t("Invalid token"));
		if (empty($data['password']))
			throw new Exception_Invalid(t("Password is missing"));
		if (empty($data['confirm']))
			throw new Exception_Invalid(t("Confirm Password is missing"));
		if ($data['password'] != $data['confirm'])
			throw new Exception_Invalid(t("Confirm Password does not match"));
		Event::publish($this, "passwordreset", $data);
		return $this;
	}
}
