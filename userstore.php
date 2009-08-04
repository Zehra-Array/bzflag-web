<?php

// userstore.php
//
// Copyright (c) 1993 - 2004 Tim Riker
//
// This package is free software;  you can redistribute it and/or
// modify it under the terms of the license found in the file
// named COPYING that should have accompanied this file.
//
// THIS PACKAGE IS PROVIDED ``AS IS'' AND WITHOUT ANY EXPRESS OR
// IMPLIED WARRANTIES, INCLUDING, WITHOUT LIMITATION, THE IMPLIED
// WARRANTIES OF MERCHANTIBILITY AND FITNESS FOR A PARTICULAR PURPOSE.

/* expected globals:
  $ldap_host
  $ldap_rootdn
  $ldap_rootpass
  $ldap_suffix
  $daemon_http_ports
*/

define("REG_SUCCESS", 0);
define("REG_INVALID_MESSAGE", 1);
define("REG_USER_EXISTS", 2);
define("REG_MAIL_EXISTS", 3);
define("REG_FAIL_GENERIC", 4);
define("REG_USER_INVALID", 5);
define("REG_PASS_INVALID", 6);
define("REG_MAIL_INVALID", 7);

class UserStore {
	private $rootld;
	private $nextuid;
	
	private function bind($dn, $password) {
		global $ldap_host;
		$ld = ldap_connect($ldap_host);
		ldap_set_option($ld, LDAP_OPT_PROTOCOL_VERSION, 3)
			or die("Failed to set ldap protocol version to 3");
		return ldap_bind($ld, $dn, $password) ? $ld : false;
	}
	
	private function getuserdn($callsign) {
		global $ldap_suffix;
		return 'cn=' . $callsign . ',' . $ldap_suffix;
	}
	
	public function auth($callsign, $password) {
		global $ldap_suffix;
		return $this->bind($this->getuserdn($callsign), $password);
	}
	
	public function getroot($die = true) {
		global $ldap_rootdn, $ldap_rootpass;
		if(!$this->rootld) {
			$this->rootld = $this->bind($ldap_rootdn, $ldap_rootpass);
			if(!$this->rootld && $die)
				die('failed to bind to rootdn');
		}
		return $this->rootld;
	}
	
	private function getIDfromDN($dn) {
		$conn = $this->getroot();
		
		$attrs = array("uid");
		$result = ldap_search($conn, $dn, "(userPassword=*)", $attrs);

		if (!$result || !ldap_count_entries($conn, $result))
			return false;

		$info = ldap_get_entries($conn, $result);
		return $info[0]["uid"][0];
	}
	
	public function getID($callsign) {
		return $this->getIDfromDN($this->getuserdn($callsign));
	}
	
	public function intersectGroupsNoExplode($callsign, $garray, $all, $ids) {
		if (!count($garray) && !$all)
			return array();
		// NOTE: if callsign = "" this returns all existing groups from the array
		//              if all = true then the groups of $callsign are intersected with all groups i.e returns all groups the user is in
		//             returns the values in the format ":group_name_1:group_name_1..."
		//             or if the ids = true then ":group_id_1:group_id_2.."

		return $this->sendRequest(array_merge(array("intersectGroups", ($all ? "1" : "0") . ($ids ? "1" : "0"), $callsign), $garray));
	}
	
	private function sendRequest($reqs) {
		global $daemon_http_ports;
		$port = $daemon_http_ports[array_rand($daemon_http_ports)];
		$url = "localhost:$port?";
		$first = true;
		foreach($reqs as $req) {
			if(!$first) $url = $url . "&";
			$first = false;
			$url = $url . urlencode($req);
		}
		$ch = curl_init(); if(!$ch) { debug("curl_init failed"); return ""; }
        if(!curl_setopt($ch, CURLOPT_URL, $url)) { debug("opt url failed"); return ""; }
        if(!curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1)) { debug("opt return failed"); return ""; }
		if(!curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2)) { debug("opt timeout failed"); return ""; }
        $output = curl_exec($ch);
        curl_close($ch);
		if(!$output || strlen($output) < 8) { debug("no output" . ($output ? strlen($output) : "")); return ""; }
		return substr($output, 8);
	}
	
	public function registerUser($callsign, $password, $email, &$randtext) {
		$rand_text_length = 8;
		$output = trim($this->sendRequest(array("register", $callsign, $password, $email)));
		$arr = explode(":", $output);
		if(count($arr) != 2) { debug("arr count wrong: " . count($arr)); return REG_FAIL_GENERIC; }
		if($arr[0] == "" || !ctype_digit($arr[0])) { debug("ret code wrong: " . $arr[0]); return REG_FAIL_GENERIC; }
		$ret = (int)$output;
		if($ret == REG_SUCCESS && strlen($arr[1]) < $rand_text_length) { debug("randtext wrong: " . $arr[1]); return REG_FAIL_GENERIC; }
		$randtext = $arr[1];
		return $ret;
	}
	
	public function getToken($callsign, $password, $ip, &$token) {
		$token = trim($this->sendRequest(array("gettoken", $callsign, $password, $ip)));
		return $token != "";
	}
	
	public function checkToken($callsign, $ip, $token) {
		$ret = trim($this->sendRequest(array("checktoken", $callsign, $ip, $token)));
		if($ret != "1" && $ret != "2" && $ret != "3") return false;
		return $ret;
	}
	
	public function updateName($old_name, $new_name) {
		return trim($this->sendRequest(array("updatename", $old_name, $new_name)));
	}
};

?>