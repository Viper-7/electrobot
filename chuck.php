<?php
include 'PSO/PSO.php';

$pso = new PSO_HTTPClient();
$pso->userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0';

$pso->addTarget('http://www.icndb.com/the-jokes-2/', function() {
	$dom = $this->getDOM();
	$jokes = $dom->getElementById('chuck-norris-jokes-table');
var_dump($jokes);
	foreach($jokes->childNodes as $child) {
		var_dump($child);
	}
});

PSO::drain($pso);
