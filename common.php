<?php
function select($array, &$vkey, $timeout = 1 ){
	$select = array();
	$write = null;
	$except = null;
	foreach ( $array as $key=>$sock ) {
		$x = count( $select );
		$select[$x] = $sock;
		$keys[$x] = $key;
	}
	if ( stream_select( $select, $write, $except, $timeout ) ) {
		foreach ( $keys as $key) {
			if ( $array[$key] == $select[0] ) {
				$vkey = $key;
				return( $select[0] );
			}
		}
	}
}
function reply($with_what) {
	global $network_name, $args, $multibot;
	$multibot->msg($network_name, $args[2], $with_what);
}
function is_admin() {
	global $source_nick, $source_host, $admin_nicks, $admin_hosts;
	
	if(in_array($source_host, $admin_hosts) && in_array($source_nick, $admin_nicks)) {
		return true;
	} else {
		return false;
	}
}
function command($name, $admin = false, $master_only = false, $nick_address_only = false) {
	global $msg_cmd, $network_name, $master_nick, $inital_server, $is_comchar;
	
	$master_uuid = md5($inital_server . $master_nick);
	if($master_only && $network_name != $master_uuid) {
		return false;
	} else {
		if($nick_address_only && $is_comchar) {
			return false;
		} else {
			if($msg_cmd == $name) {
				if($admin) {
					if(is_admin()) {
						return true;
					} else {
						return false;
					}
				}
				return true;
			} else {
				return false;
			}
		}
	}
}
function mem_get_usage(){
    //If its Windows
    //Tested on Win XP Pro SP2. Should work on Win 2003 Server too
    //Doesn't work for 2000
    //If you need it to work for 2000 look at http://us2.php.net/manual/en/function.memory-get-usage.php#54642
    if (substr(PHP_OS, 0, 3) == 'WIN') {
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $output = array();
            exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);

            return preg_replace('/[\D]/', '', $output[5]) * 1000;
        }
    } else {
        //We now assume the OS is UNIX
        //Tested on Mac OS X 10.4.6 and Linux Red Hat Enterprise 4
        //This should work on most UNIX systems
        $pid = getmypid();
        exec("ps -eo%mem,rss,pid | grep $pid", $output);
        $output = explode("  ", $output[0]);
        //rss is given in 1000 byte units
        return $output[1] * 1000;
    }
}

// returns the size of a certain amount of bytes
function ByteSize($bytes){
    $size = $bytes / 1000;
    if ($size < 1000) {
        $size = number_format($size, 2);
        $size .= 'KB';
    } else {
        if ($size / 1000 < 1000) {
            $size = number_format($size / 1000, 2);
            $size .= 'MB';
        } elseif ($size / 1000 / 1000 < 1000) {
            $size = number_format($size / 1000 / 1000, 2);
            $size .= 'GB';
        }
    }
    return $size;
}
function RByteSize($bytes){
    //$size = $bytes / 1000;
    $size=$bytes;
    if ($size < 1000) {
        $size = number_format($size, 2);
        $size .= 'KB';
    } else {
        if ($size / 1000 < 1000) {
            $size = number_format($size / 1000, 2);
            $size .= 'MB';
        } else if ($size / 1000 / 1000 < 1000) {
            $size = number_format($size / 1000 / 1000, 2);
            $size .= 'GB';
        } else if ($size / 1000 / 1000 / 1000 < 1000) {
            $size = number_format($size / 1000 / 1000 / 1000, 2);
            $size .= 'TB';
        } else if ($size / 1000 / 1000 / 1000 / 1000 < 1000) {
            $size = number_format($size / 1000 / 1000 / 1000 / 1000, 2);
            $size .= 'PB';
        } else if ($size / 1000 / 1000 / 1000 / 1000 / 1000 < 1000) {
            $size = number_format($size / 1000 / 1000 / 1000 / 1000 / 1000, 2);
            $size .= 'EB';
        } else if ($size / 1000 / 1000 / 1000 / 1000 / 1000 / 1000 < 1000) {
            $size = number_format($size / 1000 / 1000 / 1000 / 1000 / 1000 / 1000, 2);
            $size .= 'ZB';
        } else {//if ($size / 1000 / 1000 / 1000 / 1000 / 1000 / 1000 / 1000 < 1000) {
            $size = number_format($size / 1000 / 1000 / 1000 / 1000 / 1000 / 1000 / 1000, 2);
            $size .= 'YB';
        }

    }
    return $size;
}
function memUsed(){
    $memory=mem_get_usage();
    return ByteSize($memory);
}
?>