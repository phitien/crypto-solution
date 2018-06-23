<?php
class Model_Token extends Model {
	protected $table = 'token';
	protected $pk = 'id';
	protected $_fields = [
		'id','user_id','token','device','created','expired'
	];
	protected $_joins = [
		'user' => "`token`.`user_id`=`user`.`id`",
		'user_profile' => "`user_profile`.`user_id`=`user`.`id`",
	];
	public function joinfields() {
		return array_merge(['user_profile.id AS user_profile_id', 'token.id AS token_id'],
		$this->fields([
			'email','facebook','twitter','linkedin',
			'facebook_token','twitter_token','linkedin_token',
			'created','updated',
			'code','status',
		], 'user'),
		$this->fields([
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
	/**
	 * @param Object data
	 */
	public function validate($token) {return $this->find(['token' => $token]);}
	/**
	 * @param Object data
	 */
	public function profile($data) {return $this->validate($data['token']);}
	/**
	 * @param Object user
	 */
	public function issue($user) {
		$token = Util::uniqid();
		return $this->setData([
			'user_id' => $user->user_id,
			'token' => $token,
			'created' => Util::now(),
			'expired' => Util::now(strtotime('+12 months')),
		])->upsert('user_id');
	}
	/**
	 * @param Object data
	 */
	public function activate($data) {
		if (empty($data['code'])) throw new Exception_NotFound(t("Invalid token"));
		$code = $data['code'];
		$model = new Model_User;
		$found = $model->find(['code' => $code]);
		if (!$found->output) throw new Exception_NotFound(t("Invalid token"));
		if ($found->output && $found->status == 'deactive')
			$found->edit(['status' => 'active', 'code' => Util::uniqid()]);
		return ['status' => 'active', 'code' => Util::uniqid()];
	}
	/**
	 * @param Object data
	 */
	public function signout($data) {
		if (empty($data['token'])) throw new Exception_NotFound(t("Invalid token"));
		$found = $this->find(['token' => $data['token']]);
		if ($found->output) $found->remove([]);
		return ['token' => null, 'id' => null, 'facebook' => null, 'linkedin' => null, 'twitter' => null];
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
		$model = new Model_User();
		$found = $model->find(['email' => $data['email']]);
		if ($found->output) throw new Exception_Invalid(t("Email is already used"));
		return $model->add($data);
	}
	/**
	 * @param Object data
	 */
	public function signin($data) {
		if (empty($data['email']))
			throw new Exception_Invalid(t("Email or password is invalid"));
		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
			throw new Exception_Invalid(t("Email or password is invalid"));
		if (empty($data['password']))
			throw new Exception_Invalid(t("Email or password is invalid"));
		if (strlen($data['password']) < 6)
			throw new Exception_Invalid(t("Email or password is invalid"));
		$model = new Model_User();
		$found = $model->find(['email' => $data['email'], 'password' => $data['password']]);
		if (!$found->output) throw new Exception_Invalid(t("Email or password is invalid"));
		return $this->issue($found);
	}
	/**
	 * @param Object data
	 */
	public function login_facebook($data) {
		if (!empty($data['facebook_token'])) return false;
		if (!Facebook::validate($data)) return false;
		$model = new Model_User();
		return $this->issue($model->setData($data)->upsert('facebook'));
	}
	/**
	 * @param Object data
	 */
	public function login_twitter($data) {
		if (!empty($data['twitter_token'])) return false;
		if (!Twitter::validate($data)) return false;
		$model = new Model_User();
		return $this->issue($model->setData($data)->upsert('twitter'));
	}
	/**
	 * @param Object data
	 */
	public function login_linkedin($data) {
		if (!empty($data['linkedin_token'])) return false;
		if (!LinkedIn::validate($data)) return false;
		$model = new Model_User();
		return $this->issue($model->setData($data)->upsert('linkedin'));
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
		// $found = $this->sign(['email' => $this->email, 'password' => $data['opassword']]);
		Log::info('xxx', $this->token, $this);
		// Request::set('token', $this->token);
		$model = new Model_User();
		$model->id = $this->user_id;
		$model->password = $data['password'];
		return $this->issue($model->edit());
	}
	/**
	 * @param Object data
	 */
	public function passwordforget($data) {
		if (empty($data['email']))
			throw new Exception_Invalid("Email is missing");
		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
			throw new Exception_Invalid("Email is invalid");
		return $this->issue($found);
	}
	/**
	 * @param Object data
	 */
	public function passwordreset($data) {
		if (empty($data['password']))
			throw new Exception_Invalid(t("Password is missing"));
		if (empty($data['confirm']))
			throw new Exception_Invalid(t("Confirm Password is missing"));
		if ($data['password'] != $data['confirm'])
			throw new Exception_Invalid(t("Confirm Password does not match"));
		return $this->issue($found);
	}
	public function after($new = false) {
		$model = new Model_User();
		$model->setData($this->output);
		$model->id = $this->user_id;
		$model->edit();
		return parent::after($new);
	}
}
