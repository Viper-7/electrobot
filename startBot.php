<?php
include 'lib/Bootstrap.php';

echo "Connecting\n";
$defaults = array(
	'BOT_SERVER' => 'irc.libera.chat',
	'BOT_PORT' => '6667',
	'BOT_NICK' => 'electrobot',
	'BOT_CHANNEL' => '##electronics',
	'BOT_DEBUG' => 1,
	'BOT_NICKSERV_USER' => '',
	'BOT_NICKSERV_PASS' => ''
);
$env = array_intersect_key($_ENV, $defaults) + $defaults;

startBot($env['BOT_SERVER'], $env['BOT_PORT'], $env['BOT_NICK'], array($env['BOT_CHANNEL']), $env['BOT_DEBUG'], ltrim($env['BOT_CHANNEL'], '#'));

