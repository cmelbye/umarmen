<?php

$just_saved_factoid = false;
if($command == 'PRIVMSG' && stristr($message, ' is ')) {
	$factoid_array = explode(' is ', $message, 2);
	$factoid_name = $factoid_array[0];
	$factoid_value = str_replace(array("\n", "\r"), '', $factoid_array[1]);
	try {
		$datastore->put('factoid', $factoid_name, $factoid_value, null, true);
		reply('OK, ' . $source_nick . '.');
	} catch(DataStore_AlreadyExists_Exception $e) {
		$existing_factoid = $datastore->get('factoid', $factoid_name);
		reply('... but ' . $factoid_name . ' is ' . $existing_factoid . ' ...');
	}
	$just_saved_factoid = true;
}

$factoids = $datastore->getAll('factoid');
if(array_key_exists($msg_args[0], $factoids) && !$just_saved_factoid) {
	$factoid_value = $datastore->get('factoid', $msg_args[0]);
	if(stristr($factoid_value, '<reply>')) {
		$factoid_value = substr($factoid_value, 7);
		reply($factoid_value);
	} else if(stristr($factoid_value, '<action>')) {
		$factoid_value = substr($factoid_value, 8);
		reply(chr(1) . 'ACTION ' . $factoid_value . chr(1));
	} else {
		reply($msg_args[0] . ' is ' . $factoid_value);
	}
}

if(command('forget', true)) {
	$factoid_name = $msg_args[1];
	try {
		$datastore->delete('factoid', $factoid_name, true);
		reply('*poof*');
	} catch(DataStore_DoesNotExist_Exception $e) {
		reply('That factoid does not exist!');
	}
}
if(command('recall')) {
	$factoid_name = $msg_args[1];
	try {
		$factoid = $datastore->get('factoid', $factoid_name, true);
		reply($factoid);
	} catch(DataStore_DoesNotExist_Exception $e) {
		reply('That factoid does not exist!');
	}
}
if(command('factoids')) {
	$factoids = $datastore->getAll('factoid');
	$factoidsResponse = '';
	foreach($factoids as $key => $value) {
		$factoidsResponse .= chr(2) . $key . chr(2) . ': ' . $value . ', ';
	}
	$factoidsResponse = trim($factoidsResponse, ', ');
	echo $factoidsResponse . "\n";
	reply($factoidsResponse);
}

// This is a "special" module, so there are no hooks.
?>