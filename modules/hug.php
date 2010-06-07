<?php
if(command('hug')) {
	$person = $msg_args[1];
	if($msg_args[2] == "in" && $msg_args[3][0] == "#") {
	  $channel = $msg_args[3];
	} else {
	  $channel = $args[2];
	}
	$multibot->write($network_name, "PRIVMSG " . $channel . " :" . chr(1) . "ACTION bounces into " . $person . "'s lap and huggles and *hugs*" . chr(1));
}

Hooks::add('hug', 'hug');
?>