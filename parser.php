<?php

/**
 * IRC Parser by Danopia (http://danopia.net/)
 */
$args = explode(' ', $data);

$has_message = (strpos($data, ' :') !== null);
if ($has_message) {
	$message = trim(substr($data, strpos($data, ' :') + 2));
	$msg_args = explode(' ', $message);
 	$msg_params = substr($message, strpos($message, ' ') + 1);
	$is_ctcp = ((strlen($msg_args[0]) >= 2) && (strpos($msg_args[0], chr(1)) === 0));
	if ($is_ctcp) {
		$ctcp_msg = trim(substr($message, 1, strrpos($message, chr(1)) - 1));
		$ctcp_args = explode(' ', $ctcp_msg);
		$ctcp_command = strtoupper($ctcp_args[0]);
	} else {
		$ctcp_command = '';
		$ctcp_args = array();
	}
} else {
 	$message = '';
 	$msg_params = '';
	$msg_args = array();
	$is_ctcp = false;
	$ctcp_msg = '';
	$ctcp_args = array();
	$ctcp_command = '';
}

$has_source = (strpos($args[0], ':') === 0);
if ($has_source) {
	$source = substr($args[0], 1);
	$args[0] = $source;
	$temp = explode('!', $args[0]);
	$has_source_path = count($temp) > 1;
	if ($has_source_path) {
		$source_nick = $temp[0];
		$temp = explode('@', $temp[1]);
		$source_ident = $temp[0];
		$source_host = $temp[1];
	} else {
		$source_nick = $source;
		$source_ident = '';
		$source_host = '';
	}
	$command = strtoupper($args[1]);
} else {
	$command = strtoupper($args[0]);
	$source_nick = '';
	$source_ident = '';
	$source_host = '';
	$has_source_path = false;
}

$is_cmd = false;
$is_comchar = false;
$msg_cmd = '';
if ($has_message && ($command == 'PRIVMSG'))
{
	if ($args[2] == $bot_nick)
	{
		$is_cmd = true;
		$msg_cmd = strtolower($msg_args[0]);
		$args[2] = $source_nick;
	}
	else if((strlen($comchar) > 0) && (strpos($msg_args[0], $comchar) === 0))
	{
		$msg_cmd = strtolower(substr($msg_args[0], strlen($comchar)));
		$is_cmd = true;
		$is_comchar = true;
	}
	else if ((stripos($msg_args[0], $bot_nick) === 0) && (((strlen($bot_nick)+1) == (strlen($msg_args[0]))) || ($msg_args[0] == $bot_nick)))
	{
		array_shift($msg_args);
		$msg_cmd = strtolower($msg_args[0]);
		$message = $msg_params;
		$msg_params = substr($message, strpos($message, ' ') + 1);
		$is_cmd = true;
		$is_comchar = false;
	}
}

if ($has_message)
{
	$msg_params2 = substr($msg_params, strpos($msg_params, ' ') + 1);
}

?>