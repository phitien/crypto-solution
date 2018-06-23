<?php
class Util {
  public static function url($field, $alias, $os) {
    if ($os == 'ios') return "$field AS $alias";
    return "REPLACE($field, 'https', 'http') AS $alias";
  }
  public static function psize() {
    if (empty($_PARAMS['psize'])) return PSIZE;
    return (int) $_PARAMS['psize'];
  }
  public static function page() {
    if (empty($_PARAMS['page'])) return PAGE;
    return (int) $_PARAMS['page'];
  }
  public static function now($diff = 0) {
    return $diff ? date(DATETIME_FORMAT, $diff) : date(DATETIME_FORMAT);
  }
  public static function getdistance($location, $destination) {
    $theta = $location['longitude'] - $destination['longitude'];
    $dist = sin(deg2rad($location['latitude'])) * sin(deg2rad($destination['latitude'])) +  cos(deg2rad($location['latitude'])) * cos(deg2rad($destination['latitude'])) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return ($miles * 1.609344); //in KM
  }
  public static function secure($sql) {
    return $sql;
  }
  public static function api($url, $method, $data = null, $headers = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    switch ($method) {
      case "POST":
      curl_setopt($ch, CURLOPT_POST, true);
      if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      break;
      case "PUT":
      curl_setopt($ch, CURLOPT_PUT, true);
      break;
      default:
      if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    else curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $res = curl_exec($ch);
    $json = json_decode($res);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_errno = curl_errno($ch);
    $result = $curl_errno ? [
      'status' => $status,
      'error' => curl_error($ch),
      'data' => $json,
      'raw' => $res
    ] : [
      'status' => $status,
      'data' => $json,
      'raw' => $res
    ];
    curl_close($ch);
    return $result;
  }
  public static function send($options) {
    return Mail::send($options);
  }
  public static function onesignal_send($params, $headings = false, $subtitle = false, $contents = false, $extra = false) {
    if (!$headings && !$contents) return false;
		if (!isset($params['devices'])) return false;
		if (!is_array($params['devices'])) $devices = [$params['devices']];
		else $devices = $params['devices'];
		$devices = array_filter($devices);
		if (!count($devices)) return false;
    $data = array(
      'app_id' => Constant::ONESIGNAL_APPID,
      'include_player_ids' => $devices,
      'contents' => $contents,
    );
    if ($headings) $data['headings'] = $headings;
    if ($subtitle) $data['subtitle'] = $subtitle;
    if ($extra) $data['data'] = $extra;
    $headers = [
      "Content-Type: application/json; charset=utf-8",
      "Authorization: Basic ".Constant::ONESIGNAL_APIKEY
    ];
    $res = self::api("POST", "https://onesignal.com/api/v1/notifications", json_encode($data), $headers);
    if (!isset($res['error']) && !isset($res['data']->errors)) return true;
    return false;
  }
  public static function password($str) {return Hash::make($str);}
  public static function encrypt($str) {return Hash::make($str);}
  public static function decrypt($str) {return Hash::make($str);}
  public static function uniqid() {return self::password(uniqid());}
  public static function json($path) {return json_decode(file_get_contents($path));}
}
