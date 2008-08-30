<?php
if(command('learn', true)) {
	$factoid_name = $msg_args[1];
	$factoid_value = $msg_params2;
	if($datastore->put('factoid', $factoid_name, $factoid_value)) {
		reply('Factoid saved');
	} else {
		reply('Unable to save factoid');
	}
}
if(command('forget', true)) {
	$factoid_name = $msg_args[1];
	try {
		$datastore->delete('factoid', $factoid_name, true);
		reply('Done.');
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

// Add the Hooks for this Module
Hooks::add('learn', 'factoids');
Hooks::add('forget', 'factoids');
Hooks::add('recall', 'factoids');
Hooks::add('factoids', 'factoids');
?>