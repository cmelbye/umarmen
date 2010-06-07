<?php
if(command('join', true)) {
	$channel = $msg_args[1];
	$multibot->join($network_name, $channel, "Hey everyone! :D");
}
if(command('cycle', true)) {
	$channel = $args[2];
	if($channel[0] == "#") {
		$multibot->part($network_name, $channel, 'Cycling');
		$multibot->join($network_name, $channel);
	} else {
		reply('That\'s not a channel name!');
	}
}
if(command('reload', true)) {
	// This fixes a bug with reloading
	$msg_cmd = null;
	
	$reloadArray = glob('modules/*.php');
	foreach($reloadArray as $reload_file) {
		$reload_name = substr($reload_file, 8, -4);
		$reload_text = file_get_contents($reload_file);
		$reload_text = substr($reload_text, 5, strlen($reload_text) - 7);
		try {
			eval($reload_text);
		} catch(Exception $e) {
			reply('Caught exception while reloading the ' . $reload_name . ' module: ' . $e->getMessage());
		}
	}
	reply('Reloaded all modules');
}
if(command('eval', true)) {
	$eval_text = $msg_params;
	$response = '';
	try {
		$response = eval($eval_text);
	} catch(Exception $e) {
		$response = 'Caught Exception: ' . $e->getMessage();
	}
	if(!empty($response)) {
		reply($response);
	} else {
		reply('No output returned');
	}
}
if(command('adminadd', true)) {
	$nick = $msg_args[1];
	$host = $msg_args[2];
	$admin_nicks[] = $nick;
	$admin_hosts[] = $host;
	reply($nick . ' has been added to the admin list');
}
if(command('admindel', true)) {
	$nick = $msg_args[1];
	$host = $msg_args[2];
	unset($admin_nicks[$nick]);
	unset($admin_hosts[$host]);
	reply($nick . ' has been removed from the admin list');
}
if(command('memory')) {
	reply('Currently using ' . memUsed() . ' of memory');
}
if(command('uptime')) {
	$uptime = `uptime`;
	$hostname = `hostname`;
	reply('My host, ' . $hostname . ', currently has this uptime: ' . $uptime);
}
if(command('hooks')) {
  $hooks = Hooks::listHooks();
  $resp = "";
  foreach($hooks as $cmd => $mod) {
    $resp .= "$cmd: $mod, ";
  }
  $resp = trim($resp, ', ');
  reply("Registered hooks: $resp");
}

// Add the Hooks for this Module
Hooks::add('join', 'admin');
Hooks::add('cycle', 'admin');
Hooks::add('reload', 'admin');
Hooks::add('eval', 'admin');
Hooks::add('memory', 'admin');
Hooks::add('uptime', 'admin');
Hooks::add('adminadd', 'admin');
Hooks::add('admindel', 'admin');
Hooks::add('hooks', 'admin');
?>