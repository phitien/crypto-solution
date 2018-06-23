<?php
class Model {
	const fixed_fields = ['table', 'pk', '_fields', '_joins'];
	protected $_fields = [];
	protected $_joins = [];
	protected $_data = [];
	public final function printme($n, $v) {Log::info($this->klass, $this);return $this;}
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
		if ($n == 'output') return $this->getData();
		if ($n == 'klass') return get_class($this);
		return @$this->_data[$n];
  }
	public final function reset($data = []) {
		$this->_data = $data;
		return $this;
	}
	public final function setData($data = []) {
		if ($data) $this->_data = array_merge($this->_data, $data);
		return $this;
	}
	public final function getData() {
		return empty($this->_data) ? null : $this->_data;
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
	public final function setField($f, $v) {
		$this->{$f} = $v;
		return $this;
	}
	public final function getField($f) {return $this->{$f};}
	public final function fieldval($k,$v) {return $this->pwdfield($k) ? Util::password($v) : secure($v);}
	public final function where($data) {
		$where = [];
		foreach($data as $k => $v) {
			if (is_object($v) || is_array($v)) continue;
			if (in_array($k, ['page','psize'])) continue;
			if (!in_array($k, $this->_fields)) continue;
			$k = secure($k);
			$f = strpos($k,'.') === false ? "`{$this->table}`.`$k`" : "`$k`";
			array_push($where, "$f='{$this->fieldval($k,$v)}'");
		}
		return empty($where) ? "" : "WHERE ".implode(' AND ', $where);
	}
	public final function fetch($data) {
		return Database::fetch($this->bquery($this->where($data)), $this->klass);
	}
	public final function page($data) {
		return Database::fetch($this->bquery(), $this->klass, array_merge($data, [
			'table' => $this->table,
			'where' => $this->where($data),
			'page' => empty($data['page']) ? 0 : (int) $data['page'],
			'psize' => empty($data['psize']) ? PSIZE : (int) $data['psize'],
		]));
	}
	public final function pinfo() {
		return Database::pinfo();
	}
	public final function find($data) {
		$where = $this->where($data);
		$rs = Database::one($this->bquery("$where LIMIT 0,1"), $this->klass);
		$rs = $rs ? $this->reset($rs) : $this->reset();
		return $rs;
	}
	public final function load($id) {
		return $this->find(['id' => $id]);
	}
	public final function bquery($extra = "") {
		$fields = implode(',', array_merge($this->fields(), $this->joinfields()));
		return "SELECT {$fields} FROM `{$this->table}` {$this->joins()} $extra";
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
		foreach($this->_joins as $table => $on) array_push($joins, $this->join($table, $on));
		return implode(' ', $joins);
	}
	public final function join($table, $on) {
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

	public function after($new = false) {return $this;}
	public function normalise($adding = true) {return $this;}
	public function upsert($f) {
		$data = $this->output;
		$found = $this->find([$f => $this->_data[$f]]);
		if (!$found->output) $this->add($data);
		else $this->edit($data);
		return $this;
	}
	public final function add($data = []) {
		$this->setData($data);
		$params = $this->normalise(true)->getDataParams(true);
		$id = Database::commit($this->insert_sql(), $params);
		if (!$id) throw new Exception_Database('No row added');
		Event::publish(substr($this->klass, strlen('Model_')), "add", $this, $data);
		return $this->setField($this->pk, $id)
								->after($id)
								->load($id);
	}
	public final function edit($data = []) {
		$this->setData($data);
		$params = $this->normalise(false)->getDataParams(false);
		if (!Database::commit($this->update_sql(), $params))
			throw new Exception_Database('Could not update');
		Event::publish(substr($this->klass, strlen('Model_')), "edit", $this, $data);
		return $this->after(false)
								->load($this->{$this->pk});
	}
	public final function remove($data = []) {
		$this->setData($data);
		if (!$this->{$this->pk}) throw new Exception_NotFound("No where clause specified");
		if (Database::commit($this->delete_sql(), [$this->{$this->pk}])) $this->reset();
		Event::publish(substr($this->klass, strlen('Model_')), "remove", $this, $data);
		return $this;
	}

}
