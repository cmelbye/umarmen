<?php
/**
 * umarmen Special Module: Bad Word Moderation
 * 
 * This module will keep track of 
 */

try {
	$bad_users = $datastore->get('badwords', 'badusers', true);
	if(!isset($bad_users[$source_nick])) {
		$current_level = 0;
	} else {
		$current_level = $bad_users[$source_nick];
	}
} catch(DataStore_DoesNotExist_Exception $e) {
	$current_level = 0;
}

if($multibot->bots[$network_name]['server'] == $bad_words_server && $args[2] == $bad_words_channel) {
	foreach($bad_words as $word) {
		if(stristr($message, $word)) {
			if($current_level == 0) {
				reply('Please watch your language!');
				$multibot->msg($network_name, $source_nick, 'Your level is now 1 for using improper language in the channel. You have one more warning before you will be kicked and banned.');
				$bad_users = $datastore->get('badwords', 'badusers');
				$bad_users[$source_nick] = 1;
				$datastore->put('badwords', 'badusers', $bad_users);
			} else if($current_level == 1) {
				reply('Please watch your language!');
				$multibot->msg($network_name, $source_nick, 'This was your 2nd and final warning. Using improper language again will result in a kickban.');
				$bad_users = $datastore->get('badwords', 'badusers');
				$bad_users[$source_nick] = 2;
				$datastore->put('badwords', 'badusers', $bad_users);
			} else if($current_level == 2) {
				reply('Please watch your language!');
				$multibot->write($network_name, 'KICK ' . $args[2] . ' ' . $source_nick . ' :Please clean up the language');
				$multibot->write($network_name, 'MODE ' . $args[2] . ' +b *!*@' . $source_host);
				$multibot->msg($network_name, $source_nick, 'You have been kicked and banned from ' . $args[2] . ' for improper language. Please contact an op if you wish to be unbanned in the future.');
				$bad_users = $datastore->get('badwords', 'badusers');
				$bad_users[$source_nick] = 3;
				$datastore->put('badwords', 'badusers', $bad_users);
			}
		}
	}
}

if(command('pardon', true)) {
	$bad_users = $datastore->get('badwords', 'badusers');
	unset($bad_users[$source_nick]);
	$datastore->put('badwords', 'badusers', $bad_users);
	reply('Done.');
}
?>