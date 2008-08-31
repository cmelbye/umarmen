<?php
include('common.php');
include('config.php');
require('library/MultiBot.php');
require('library/DataStore.php');
require('library/Logger.php');
require('library/Hooks.php');
$multibot = new MultiBot($inital_server, $master_nick, $inital_channel);
$datastore = new DataStore;
$bot_number = 1;
$inital_load = false;
$doing_inital_load = false;
srand((float) microtime() * 10000000);

foreach($extra_connections as $extra_connection) {
	$multibot->new_bot($extra_connection['server'], $extra_connection['nickname'], $extra_connection['channel']);
}

// Main bot loop
while(1) {
	
	$read = array();
	$networks = array();
	
	foreach( $multibot->bots as $key=>$value ) {
		if ( $value['connected'] ) {
			$read[$key] = $value['socket'];
		}
	}

	if ( select( $read, $network_name ) ) {
		$socket = $multibot->bots[$network_name]['socket'];
		$bot_nick = $multibot->bots[$network_name]['nickname'];
		if ( $socket && !feof( $socket ) ) {
			$data = $multibot->read($network_name);
			if ( mb_strlen( $data ) > 0 ) {
				$comchar = '^';
				include('parser.php');
				$modules = array();
				foreach(glob('modules/*.php') as $module_file) {
					$module_name = substr($module_file, 8, -4);
					$modules[] = $module_name;
					if(!$inital_load) {
						$module_text = file_get_contents($module_file);
						$module_text = substr($module_text, 5, strlen($module_text) - 7);
						try {
							eval($module_text);
						} catch(Exception $e) {
							Logger::log(Logger::_WARNING, 'Initial load of the ' . $module_name . ' module failed! Caught Exception: ' . $e->getMessage());
						}
						$doing_inital_load = true;
					}
				}
				if($doing_inital_load) {
					$inital_load = true;
				}
				$module_to_load = Hooks::get($msg_cmd);
				if(!empty($module_to_load) && in_array($module_to_load, $modules) && $command = 'PRIVMSG') {
					echo "Loading the " . $module_to_load . " module\n";
					$module_text = file_get_contents('./modules/' . $module_to_load . '.php');
					$module_text = substr($module_text, 5, strlen($module_text) - 7);
					try {
						eval($module_text);
					} catch(Exception $e) {
						reply('Caught Exception in the ' . $module_to_load . ' module! ' . $e->getMessage());
					}
				}
				if($command == 'PING') {
					$multibot->write($network_name, 'PONG :' . $message);
				}
				if($command == 'ERROR') {
					echo $data;
				}
			}
		} else {
			$multibot->disconnect($network_name);
		}
	}

}

?>
