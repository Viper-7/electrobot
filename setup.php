<?php
$db = new PDO('sqlite:redneck.db');
$db->query('CREATE TABLE IF NOT EXISTS chuck (id INTEGER PRIMARY KEY AUTOINCREMENT, line VARCHAR NOT NULL, said INTEGER)');
$fp = fopen('chuck.txt','r');
$stmt = $db->prepare('INSERT INTO chuck (line, said) VALUES (?, 0)');
$data = stream_get_contents($fp);
$data = json_decode($data);
foreach($data as $joke) {
	$line = trim($joke->joke);
	if($line)
		$stmt->execute(array($line));
	echo "$line\n";
}
	
