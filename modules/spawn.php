<?php
if(command('spawn', true, null, true)) {
	if(!isset($msg_args[1]) || $msg_args[1] == 'self') {
		$server = $multibot->bots[$network_name]['server'];
	} else {
		$server = $msg_args[1];
	}
	if(!isset($msg_args[2]) || $msg_args[2] == 'next') {
		$nick = $bot_nick . '-' . $bot_number;
		$bot_number++;
	} else {
		$nick = $msg_args[2];
	}
	if(!isset($msg_args[3]) || $msg_args[3] == 'self') {
		$channel = $args[2];
	} else {
		$channel = $msg_args[3];
	}
	try {
		$multibot->new_bot($server, $nick, $channel);
		reply('The bot has been created and is connecting.');
	} catch(MultiBot_Exception $e) {
		reply('Caught Exception: ' . $e->getMessage());
	}
}

if(command('kill', true)) {
	if(count($msg_args) < 2) {
		reply('Usage: kill <servername> <botnick>');
	} else {
		if(count($msg_args) == 2) {
		  if($msg_args[1] == "yourself") {
		    $uuid = $network_name;
		  } else {
			  $nick = $msg_args[1];
			  $server = $multibot->bots[$network_name]['server'];
			  $uuid = md5($server . $nick);
		  }
		} else {
			$server = $msg_args[1];
			$nick = $msg_args[2];
			$uuid = md5($server . $nick);
		}
		try {
			$multibot->disconnect($uuid);
			reply('Killed ' . $nick . ' on ' . $server);
		} catch(MultiBot_Exception $e) {
			reply('Caught Exception: ' . $e->getMessage());
		}
	}
}

// Add the Hooks for this Module
Hooks::add('spawn', 'spawn');
Hooks::add('kill', 'spawn');
Hooks::add('connections', 'spawn');
?>