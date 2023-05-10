<?php
$db = new PDO('sqlite:redneck.db');
$db->query('DELETE FROM chuck WHERE id >426 and id < 538');

$stmt = $db->prepare('SELECT * FROM chuck WHERE id > 426 and id < 538');
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
	echo $row['id'] . ': ' . $row['line'] . "\n";
}
