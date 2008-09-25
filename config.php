<?php

/**
 * These connection settings are for the master bot
 * Read the documentation for more information
 */
$inital_server = 'chat.freenode.net';
$master_nick = 'mynewbot';
$inital_channel = '##charlie';
$extra_channels = array('#botters');

/**
 * These settings are for the Administrator users of the bot
 */
$admin_nicks = array('jdoe');
$admin_hosts = array('jdoe.unaffiliated');

/**
 * Special Modules
 * These are modules that, for whatever reason, need to be loaded on every message we receive
 * If you don't need these modules, then don't set this variable
 */
//$special_modules = array('badwords');

/**
 * These are all your secondary connections which will be created on startup
 */
$extra_connections = array();
//$extra_connections[] = array(
//	'server' => 'irc.freenode.net',
//	'nickname' => 'umarmen',
//	'channel' => '#OctarineParrot');

/**
 * Should the bot join channels if people invite it?
 */
$join_on_invite = true;

/**
 * "Bad Words" module
 */

// To avoid offending anyone, we leave the job of choosing bad words to filter up to you
$bad_words = array('insert', 'bad', 'words', 'here');

// I know that this is fairly ugly, but it's the best way I found of doing it
$bad_words_channel = '#badwords';
$bad_words_server = 'some.irc.channel.net';

?>
