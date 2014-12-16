<?php

function isJson($string) {
	return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
}

function createUniqueCode($namespace = '') {    
	static $guid = '';
	$uid = uniqid("", true);
	$data = $namespace;
	$data .= $_SERVER['REQUEST_TIME'];
	$data .= $_SERVER['HTTP_USER_AGENT'];
	//$data .= $_SERVER['LOCAL_ADDR'];
	//$data .= $_SERVER['LOCAL_PORT'];
	$data .= $_SERVER['REMOTE_ADDR'];
	$data .= $_SERVER['REMOTE_PORT'];
	$hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
	$guid = '{' .  
			substr($hash,  0,  8) .
			'-' .
			substr($hash,  8,  4) .
			'-' .
			substr($hash, 12,  4) .
			'-' .
			substr($hash, 16,  4) .
			'-' .
			substr($hash, 20, 12) .
			'}';
	return $guid;
}
	
function anti_injection_login_senha($sql, $formUse = true) {
	$sql = preg_replace("/(from|select|insert|delete|where|drop table|show tables|,|'|#|\*|--|\\\\)/i","",$sql);
	$sql = trim($sql);
	$sql = strip_tags($sql);
	if(!$formUse || !get_magic_quotes_gpc())
	  $sql = addslashes($sql);
	  $sql = md5(trim($sql));
	return $sql;
}

function anti_injection_login($sql, $formUse = true) {
	$sql = preg_replace("/(from|select|insert|delete|where|drop table|show tables|,|'|#|\*|--|\\\\)/i","",$sql);
	$sql = trim($sql);
	$sql = strip_tags($sql);
	if(!$formUse || !get_magic_quotes_gpc())
	  $sql = addslashes($sql);
	return $sql;
}

function toJSONEncoded($data) {
	$json_encoded = json_encode($data);
	if (isJson($json_encoded) == true){
		 $return = strrev(encrypt3DES($json_encoded));
	} else{
		 $return = false;
	}
	return $return;
}