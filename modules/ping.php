<?php

if (command( 'ping' )) {
  reply('Pong!');
}

Hooks::add('ping', 'ping');
