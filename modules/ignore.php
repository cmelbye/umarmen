<?php
if(command('ignore', true)) {
	$person_to_ignore = $msg_args[1];
	$datastore->put('ignore', $person_to_ignore, '');
	reply($person_to_ignore . ' is now ignored.');
}

if(command('unignore', true)) {
	$person_to_ignore = $msg_args[1];
	$datastore->delete('ignore', $person_to_ignore);
	reply('I can now hear ' . $person_to_ignore . ' again.');
}

if(command('ignored', true)) {
	$ignored = $datastore->getAll('ignore');
	$response = 'Ignored People: ';
	foreach($ignored as $ignored_person => $value) {
		$response .= $ignored_person . ', ';
	}
	$response = trim($response, ', ');
	reply($response);
}

Hooks::add('ignore', 'ignore');
Hooks::add('unignore', 'ignore');
Hooks::add('ignored', 'ignore');

?>