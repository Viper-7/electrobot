<?php
$db = new PDO('sqlite:battery.db');
$sql = file_get_contents('battery.sql');
foreach(explode("\n", $sql) as $line) {
	$line = trim($line);
	if($line) {
		$db->query($line);
	}
}
/*
$db->query('CREATE TABLE IF NOT EXISTS mama (id INTEGER PRIMARY KEY AUTOINCREMENT, line VARCHAR NOT NULL, said INTEGER)');
//$fp = fopen('redneck.txt', 'r');
$fp = fopen('mama.txt','r');
$stmt = $db->prepare('INSERT INTO mama (line, said) VALUES (?, 0)');
while(!feof($fp)) {
	$line = stream_get_line($fp, 4096, "\n");
	$line = trim($line);
	if($line)
		$stmt->execute(array($line));
	echo "$line\n";
}
	
