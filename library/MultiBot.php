<?php
class MultiBot_Exception extends Exception {}

class MultiBot {
	public $bots = array();
	
	public function __construct($firstbot_server, $firstbot_nick, $firstbot_channel) {
		$this->new_bot($firstbot_server, $firstbot_nick, $firstbot_channel);
		Logger::log(Logger::_STATUS, "First bot has been created");
	}
	
	public function new_bot($server, $nick, $channel) {
		$uuid = md5($server . $nick);
		if(isset($this->bots[$uuid])) {
			print_r($this->bots);
			var_dump($nick);
			throw new MultiBot_Exception('Can not create a bot that already exists');
		} else {
			$this->bots[$uuid] = array();
			$this->bots[$uuid]['nickname'] = $nick;
			Logger::log(Logger::_STATUS, "New bot has been created: " . $nick);
			$this->bots[$uuid]['socket'] = fsockopen($server, 6667);
			Logger::log(Logger::_STATUS, "Socket opened for bot '" . $nick . "'");
			$this->bots[$uuid]['channel'] = $channel;
			$this->bots[$uuid]['server'] = $server;
			$this->bots[$uuid]['connected'] = false;
			$this->connect($uuid);
			Logger::log(Logger::_STATUS, "A bot has registered with IRC: '" . $nick . "'");
			$this->join($uuid, $channel);
			Logger::log(Logger::_STATUS, "Told bot to join " . $channel . ": '" . $nick . "'");
		}
	}
	
	public function connect($uuid) {
		if(!isset($this->bots[$uuid])) {
			throw new MultiBot_Exception('That bot does not exist');
		} else if($this->bots[$uuid]['connected']) {
			throw new MultiBot_Exception('That bot is already connected!');
		} else {
			$bot_nick = $this->bots[$uuid]['nickname'];
			$this->write($uuid, "USER " . $bot_nick . " 0 0 :MultiBot");
			$this->write($uuid, "NICK " . $bot_nick);
			$temp_connect = false;
			$connect_start = time();
			$timeout_time = $connect_start + 20;
			while($temp_connect < 1) {
				echo $data;
				$data = $this->read($uuid);
				$dataA = explode(' ', $data);
				if($dataA[1] == '001') {
					$temp_connect = 1;
				}
				if($dataA[0] == 'PING') {
					$this->write($uuid, 'PONG ' . $dataA[1]);
				}
				if($timeout_time == time()) {
					$temp_connect = 2;
				}
			}
			if($temp_connect == 2) {
				throw new MultiBot_Exception("Couldn't connect the new bot, timed out.");
			} else {
				$this->bots[$uuid]['connected'] = true;
			}
		}
	}
	
	public function disconnect($uuid) {
		$this->write($uuid, "QUIT :Killed");
		fclose($this->bots[$uuid]['socket']);
		unset($this->bots[$uuid]);
	}
	
	public function join($uuid, $channel, $message = null) {
		$bot_nick = $this->bots[$uuid]['nickname'];
		if($this->bots[$uuid]['connected']) {
			$this->write($uuid, "JOIN " . $channel);
			if($message) {
				$this->msg($uuid, $channel, $message);
			}
			Logger::log(Logger::_INFO, "A bot has joined a channel: " . $bot_nick);
		} else {
			throw new MultiBot_Exception('That bot isn\'t connected!');
		}
	}
	
	public function part($uuid, $channel, $message = null) {
		$bot_nick = $this->bots[$uuid]['nickname'];
		if($this->bots[$uuid]['connected']) {
			$extra = '';
			if($message) {
				$extra = " :" . $message;
			}
			$this->write($uuid, "PART " . $channel . $extra);
			Logger::log(Logger::_INFO, "A bot has parted a channel: " . $bot_nick);
		} else {
			throw new MultiBot_Exception('That bot isn\'t connected!');
		}
	}

	public function msg($uuid, $channel, $message) {
		if($this->bots[$uuid]['connected']) {
			$message = str_replace("\r", '', $message);
			$message_array = explode('\n', $message);
			foreach($message_array as $msg) {
				if(!empty($msg)) {
					$this->write($uuid, "PRIVMSG " . $channel . " :" . $msg);
				}
			}
		} else {
			throw new MultiBot_Exception('That bot isn\'t connected');
		}
	}
	
	public function write($uuid, $data) {
		if(isset($this->bots[$uuid])) {
			$data = str_replace(array("\n", "\r"), '', $data);
			Logger::log(Logger::_MESSAGE, $data);
			fwrite($this->bots[$uuid]['socket'], $data . "\n");
		} else {
			throw new Exception('That bot doesn\'t exist');
		}
	}
	
	public function read($uuid) {
		if(isset($this->bots[$uuid]['connected'])) {
			return fgets($this->bots[$uuid]['socket']);
		} else {
			throw new Exception('That bot doesn\'t exist');
		}
	}
}
?>
