<?php
if(command('ping')) {
	reply('Pong!! :D');
}

// Add the Hooks for this Module
Hooks::add('ping', 'ping');
?>