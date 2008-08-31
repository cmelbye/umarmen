<?php
if(command('hug')) {
	$person = $msg_args[1];
	$multibot->write($network_name, "PRIVMSG " . $args[2] . " :" . chr(1) . "ACTION bounces into " . $person . "'s lap and huggles and *hugs*" . chr(1));
}

Hooks::add('hug', 'hug');
?>