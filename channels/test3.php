<?php
$message = '!units 5 miles in km';

preg_match('/^!units\s*[\'"]?([^\'"]+)[\'"]?\s+[\'"]?([^\'"]*?)[\'"]?\s*$/im', $message, $res);

print_r($res);
