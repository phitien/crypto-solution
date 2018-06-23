<?php
function secure($str) {
	return $str;
}
class Database {
	public static $conn;
	public static $sql;
	public static $where;
	public static function init() {
		if(self::$conn == null) {
			$dsn = "mysql:host=".DB_HOST."; dbname=".DB_NAME."; charset=".DB_CHARSET;
			self::$conn = new PDO($dsn, DB_USER, DB_PWD);
		}
	}
	public static function execute($sql) {
		self::$sql = $sql;
		$options = Session::get('database.options');
		if (is_array($options)) {
			$where = @$options['where'];
			$page = (int) @$options['page'];
			$psize = (int) @$options['psize'];
			if ($psize) $where = "$where LIMIT $page,$psize";
			self::$sql = self::$sql." ".$where;
		}
		Log::info(self::$sql);
		$conn = self::$conn;
		$stmt = $conn->prepare(self::$sql);
		$stmt->execute();
		return $stmt;
	}
	public static function pinfo() {
		$options = Session::get('database.options');
		if (!is_array($options)) throw new Exception_Database("No page found");
		$table = @$options['table'];
		if (!$table) throw new Exception_Database("No page found");
		$psize = (int) @$options['psize'];
		if (!$psize) throw new Exception_Database("No page found");
		$where = @$options['where'];
		$page = (int) @$options['page'];
		$sql = "SELECT $page AS page, $psize AS psize, COUNT(*) AS total, CEIL(COUNT(*)/$psize) AS totalpages FROM $table $where";
		Session::set('database.options', null);
    return self::one($sql);
  }
	public static function commit($sql, array $values) {
		Log::info($sql);
		Log::info($values);
		self::$sql = $sql;
		$conn = self::$conn;
		$stmt = $conn->prepare(self::$sql);
		try {
			$conn->beginTransaction();
			$rs = $stmt->execute($values);
			$id = strpos($sql, 'INSERT') !== false ? $conn->lastInsertId() : $rs;
			$conn->commit();
			return $id;
    }
		catch(PDOExecption $e) {
			$conn->rollback();
			throw new Exception_Database($e->getMessage());
    }
	}
	public static function fetch($sql, $klass = null, $options = null) {
		Session::set('database.options', $options);
		$stmt = self::execute($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public static function one($sql, $klass = null) {
		$stmt = self::execute($sql);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	public static function value($sql, $klass = null) {
		$stmt = self::execute($sql);
		return $stmt->fetchColumn();
	}
}
