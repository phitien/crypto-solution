<?php
class Model {
	const fixed_fields = ['table', 'pk', '_fields', '_joins'];
	protected $_fields = [];
	protected $_joins = [];
	protected $_joins_extra = [];
	protected $_data = [];
	protected $_where = [];
	protected $_where_extra = [];
	public final function printme() {
		Log::info($this->klass, $this);
		return $this;
	}
  public final function __set($n, $v) {
		if ($n == 'output') return;
		if ($n == 'klass') return;
		if (in_array($n, self::fixed_fields)) return;
		if (method_exists($this, $n)) return;
		if (property_exists($this,$n)) return $this[$n] = $v;
		$this->_data[$n] = $v;
  }
  public final function __get($n) {
		if (method_exists($this, $n)) return null;
		if (property_exists($this,$n)) return $this[$n];
		if ($n == 'output') return $this->get();
		if ($n == 'klass') return get_class($this);
		return @$this->_data[$n];
  }
	public final function reset($data = []) {
		$this->_data = $data;
		return $this;
	}
	public final function getValidFields($exclude = []) {
		return array_filter(array_keys($this->_data), function($f) {
			return $f != $this->pk && in_array($f, $this->_fields);
		});
	}
	public final function getDataParams($adding = true) {
		$data = array_values(array_map(function($f) {
			return $this->fieldval($f,$this->_data[$f]);
		}, $this->getValidFields()));
		return $adding ? $data : array_merge($data, [$this->{$this->pk}]);
	}
	public final function set($f, $v = null) {
		if (!$f) return $this;
		if (is_string($f)) $this->{$f} = $v;
		else $this->_data = array_merge($this->_data, $f);
		return $this;
	}
	public final function get($f = null) {
		return $f ? $this->{$f} : empty($this->_data) ? null : $this->_data;
	}
	public final function fieldval($k,$v) {
		return $this->pwdfield($k) ? Util::password($v) : secure($v);
	}
	public final function where($data) {
		$where = array_merge($this->_where, $this->_where_extra);
		foreach($data as $k => $v) {
			if (is_object($v) || is_array($v)) continue;
			if (in_array($k, ['page','psize'])) continue;
			if (!in_array($k, $this->_fields)) continue;
			$k = secure($k);
			$f = strpos($k,'.') === false ? "`{$this->table}`.`$k`" : "`$k`";
			$v = $this->fieldval($k,$v);
			if (empty($v)) array_push($where, "$f IS NULL");
			else array_push($where, "$f='$v'");
		}
		return empty($where) ? "" : "WHERE ".implode(' AND ', $where);
	}
	public final function whereadd($cond) {
		array_push($this->_where_extra, $cond);
		return $this;
	}
	public final function wherereset() {
		$this->_where_extra = [];
		return $this;
	}
	public final function all($data = []) {
		return Database::fetch($this->bfullquery($data), $this->klass);
	}
	public final function page($data = []) {
		return Database::fetch($this->bquery(), $this->klass, array_merge($data, [
			'table' => $this->table,
			'join' => $this->joins(),
			'where' => $this->where($data),
			'page' => empty($data['page']) ? 0 : (int) $data['page'],
			'psize' => empty($data['psize']) ? PSIZE : (int) $data['psize'],
		]));
	}
	public final function pinfo() {
		return Database::pinfo();
	}
	public function find($data) {
		$where = $this->where($data);
		$rs = Database::one("{$this->bfullquery($data)} LIMIT 0,1", $this->klass);
		$rs = $rs ? $this->reset($rs) : $this->reset();
		return $rs;
	}
	public final function load($id) {
		return $this->find(['id' => $id]);
	}
	public final function bquery() {
		$fields = implode(',', array_merge($this->fields(), $this->joinfields()));
		return "SELECT {$fields} FROM `{$this->table}`";
	}
	public final function bfullquery($data = []) {
		$fields = implode(',', array_merge($this->fields(), $this->joinfields()));
		return "SELECT {$fields} FROM `{$this->table}` {$this->joins()} {$this->where($data)}";
	}
	public function datefield($f) {return false;}
	public function pwdfield($f) {return false;}
	public final function fields($fields = [], $table = null) {
		if (!$table) $table = $this->table;
		$transformer = function($f) use ($table) {
			if ($this->datefield($f)) {
				$df = $table ? "`$table`.`$f`" : "`$f`";
				return "DATE_FORMAT({$df}, '".DB_DATETIME_FORMAT."') AS $f";
			}
			return $table ? "`$table`.`$f`" : "`$f`";
		};
		if (!empty($fields)) return array_map($transformer, array_filter($fields));
		return array_map($transformer, array_filter($this->_fields));
	}
	public final function joins() {
		$joins = [];
		foreach($this->_joins as $table => $on) array_push($joins, $this->joinbuild($table, $on));
		foreach($this->_joins_extra as $table => $on) array_push($joins, $this->joinbuild($table, $on));
		return implode(' ', $joins);
	}
	public final function joinadd($table, $on) {
		$this->_joins_extra[$table] = $on;
		return $this;
	}
	public final function joinreset() {
		$this->_joins_extra = [];
		return $this;
	}
	public final function joinbuild($table, $on) {
		return "JOIN `$table` ON $on";
	}
	public function joinfields() {
		return [];
	}
	public final function insert_sql() {
		$fields = $this->getValidFields();
		$values = implode(',', array_map(function($f) {return '?';}, $fields));
		$fields = implode(',', array_map(function($f) {return "`".secure($f)."`";}, $fields));
		return "INSERT INTO `{$this->table}` ($fields) VALUES ($values);";
	}
	public final function update_sql() {
		$fields = $this->getValidFields();
		$set = implode(',', array_map(function($f) {return "`".secure($f)."`=?";}, $fields));
		return "UPDATE `{$this->table}` SET $set WHERE `{$this->pk}`=?";
	}
	public final function delete_sql() {
		return "DELETE FROM `{$this->table}` WHERE `{$this->pk}`=?";
	}
	public function after($new = false, $propagate = true, $data = []) {
		if ($propagate) Event::publish($this, $new ? "add" : "edit", $data);
		return $this;
	}
	public function normalise($adding = true) {
		$this->_data = array_filter($this->output, function($v) {
			return is_numeric($v) || is_bool($v) || (!empty($v) && !is_object($v) && !is_array($v));
		});
		return $this;
	}
	public function upsert($f, $propagate = true) {
		$data = $this->output;
		$found = $this->find([$f => $this->_data[$f]]);
		if (!$found->output) $this->add($propagate, $data);
		else $this->edit($propagate, $data);
		return $this;
	}
	public final function add($propagate = true, $data = []) {
		$this->set($data);
		$params = $this->normalise(true)->getDataParams(true);
		$id = Database::commit($this->insert_sql(), $params);
		if (!$id) throw new Exception_Database('No row added');
		return $this->set($this->pk, $id)
								->after($id, $propagate, $data)
								->load($id);
	}
	public final function edit($propagate = true, $data = []) {
		$this->set($data);
		$params = $this->normalise(false)->getDataParams(false);
		if (!Database::commit($this->update_sql(), $params))
			throw new Exception_Database(t("Could not update %s", $this->table));
		return $this->after(false, $propagate, $data)
								->load($this->{$this->pk});
	}
	public final function remove($propagate = true, $data = []) {
		$this->set($data);
		if (!$this->{$this->pk}) throw new Exception_NotFound(t("Record not found"));
		if (Database::commit($this->delete_sql(), [$this->{$this->pk}])) $this->reset();
		if ($propagate) Event::publish($this, "remove", $data);
		return $this;
	}

}
