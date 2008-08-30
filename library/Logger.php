<?php
class Logger {
	const
		_STATUS = 1,
		_WARNING = 2,
		_INFO = 3,
		_MESSAGE = 4;
	
	public function log($type, $what) {
		$what = str_replace(array("\n", "\r"), '', $what);
		$line = '';
		switch ($type) {
			case self::_STATUS:
				$line .= ">@< ";
				break;
			case self::_WARNING:
				$line .= ">!< ";
				break;
			case self::_INFO:
				$line .= ">%< ";
				break;
			case self::_MESSAGE:
				$line .= ">M< ";
				break;
			default:
				$line .= ">%< ";
				break;
		}
		$line .= $what;
		echo $line . "\n";
	}
	
	public static function debug($text) {
		$fp = fopen('debug.txt', 'a');
		fwrite($fp, 'In ' . __FILE__ . ': ' . trim($text) . "\n");
		fclose($fp);
		print_r(debug_backtrace());
		return true;
	}
}
?>