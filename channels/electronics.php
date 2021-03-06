<?php
function toAmps($val) {
$val = trim($val, 'Aa');
exec(escapeshellcmd('units') . ' -t ' . escapeshellarg($val) . ' A', $resarr);
                                                                $result = trim($resarr[0]);
if($result == 'reciprocal conversion' || $result == 'conformability error') $result = $resarr[1];
return $result;
}
function ago($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
class electronics extends IRCServerChannel
{
	public $lastUsed = array();
	public $recentParts = array();
	public $recentPartTimes = array();

        public function event_joined() {
                $this->server->send_msg('nickserv', 'IDENTIFY ...');
        }

        public function event_msg($who, $message)
        {

		if(preg_match('/(https?:\/\/.*?)(?:\s+|$)/', $message, $match)) {
			$url = $match[1];

$c = curl_init($url);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
curl_setopt($c, CURLOPT_COOKIE, 'PREF=f1=50000000&gl=AU&al=en&f6=400&f5=30000&hl=en');
curl_setopt($c, CURLOPT_HEADER, true);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($c, CURLOPT_TIMEOUT, 2);

$ret = curl_exec($c);
curl_close($c);

$orghost = parse_url($url, PHP_URL_HOST);

if(preg_match('/^Location:\s*(.*)\s*$/im', $ret, $match)) {
        $url = $match[1];
        $host = parse_url($url, PHP_URL_HOST);
} else {
        $url = '';
        $host = '';
}
if(preg_match('/<title[^>]*>(.*?)<\/title>/i', $ret, $match)) {
        $title = $match[1];
} else {
        $title = '';
}

if(substr($orghost, 0, 3) == 'www' && (substr($host, 0, 2) == 'it' || substr($host, 0, 2) == 'fr' || substr($host, 0, 2) == 'br')) {
	$host = '';
	$title = '';
}

if($orghost == str_replace('.au', '', $host)) {
	$host = '';
	$title = '';
}

if($orghost == substr($host, 4) || substr($orghost, 4) == $host || substr($host, 2) == $orghost) {
	$host = '';
	$title = '';
}

if($orghost == 'ali.viper-7.com' || $host == 'sec.aliexpress.com' || substr($host, -3) == '.fr' || substr($host, -3) == '.ca' || substr($host, -3) == '.br') {
	$host = '';
	$title = '';
}

if(substr(trim($host),0,11) == 'ipv4.google' || substr($orghost, 0, 10) == 'www.google') {
	$host = '';
	$title = '';
}

if(substr($orghost,0,3) == 'pdf' && substr($host,0,3) == 'www') {
	$host = ''; $title = '';
}

if($title) {
	$title = html_entity_decode($title, ENT_QUOTES | ENT_HTML401, 'UTF-8');
}

if($orghost != $host && $host != '') {
        $out = $who->nick . ' just linked to ' . $host;
        if($title) {
                $out .= ' (' . $title . ')';
        }
} elseif($title && ($orghost == 'youtube.com' || $orghost == 'www.youtube.com' || $orghost == 'youtu.be')) {
	$out = $who->nick . ' just linked to ' . $title;
}

}

if(substr($message,0,1) == '!' && !empty($this->users)) {
if(!empty($this->lastUsed[$who->nick][3])) {
        if(!empty($this->lastUsed[$who->nick][4])) {
                if($this->lastUsed[$who->nick][4] > time() - 120) {
                        return;
                }
        }
        $this->lastUsed[$who->nick][4] = $this->lastUsed[$who->nick][3];
        if($this->lastUsed[$who->nick][3] > time() - 300) {
                return $this->send_msg($who->nick . ': You have used too many triggers in the past 5 minutes, please PM me in future');
        }
}
if(!empty($this->lastUsed[$who->nick][2])) {
        $this->lastUsed[$who->nick][3] = $this->lastUsed[$who->nick][2];
}
if(!empty($this->lastUsed[$who->nick][1])) {
        $this->lastUsed[$who->nick][2] = $this->lastUsed[$who->nick][1];
}

if(!empty($this->lastUsed[$who->nick][0])) {
        $this->lastUsed[$who->nick][1] = $this->lastUsed[$who->nick][0];
}
$this->lastUsed[$who->nick][0] = time();

}

if(substr($message, 0, 5) == '!part') {
$message = substr($message, 6);

if(empty($out)) {
//if(preg_match_all('/\b([0-9]{0,2}[A-Z]{1,6}[0-9]{1,5}(?:[A-Z]{1,2}[0-9]{2,4})?(?:[A-Z0-9]{2,5})?)(?:\b|\?)/', $message, $match)) {
if(1) {
	$part = $message;
//	$part = $match[1][0];

	$search = $part;

	$banned = array('ESP8266','STM32','STM8','TP4056','SOT23','N64','PS2','PS3','PS4','PS1','ESP1','ESP32','USB3','USB2','USB1','USB','ATMEGA328','ATMEGA168','CC0','C18','CC1','RS232','RS485','RS422');
	if(in_array($part, $banned)) return;
// || count($match[1]) > 1 || preg_match('/\b[0-9]{1,2}[A-Z][0-9]\b/', $part) || preg_match('/\bATTINY|shell|Solution/i', $part) || preg_match('/\bhttps?:\/\//', $message) || preg_match('/\b\w\d\w\d\b/', $part) || preg_match('/^\w{2}\d$|^STM32[FL]\d$/', $part) || preg_match('/HTTP|^DS\d+\w?$|0X[a-f0-9]{1,2}|^\w{2,3}\d$|^DIP\d+$/',$part)) return;

	if(in_array($part, $this->recentParts)) {
		if($this->recentPartTimes[$part] > time() - (6*60*60)) {
			return;
		}
	}

	if(count($this->recentParts) > 10) {
		$this->recentParts = array_slice($this->recentParts, -10, 10);
	}

	if(preg_match('/^[A-Z][0-9]{1,2}$/', $part)) return;

	$this->recentParts[] = $part;
	$this->recentPartTimes[$part] = time();

if(!empty($this->users)) {
if(!empty($this->lastUsed[$who->nick][1])) {
        if(!empty($this->lastUsed[$who->nick][2])) {
                if($this->lastUsed[$who->nick][2] > time() - 12800) {
                        return;
                }
        }
        $this->lastUsed[$who->nick][2] = $this->lastUsed[$who->nick][1];
        if($this->lastUsed[$who->nick][1] > time() - 12800) {
		return;
        }
}
if(!empty($this->lastUsed[$who->nick][0])) {
        $this->lastUsed[$who->nick][1] = $this->lastUsed[$who->nick][0];
}
$this->lastUsed[$who->nick][0] = time();
}


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://octopart.com/api/v3/parts/search?apikey=...&include[]=descriptions&q=' . $part);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$ret = curl_exec($ch);

	$data = json_decode($ret);
	foreach($data->results as $res) {
		$item = $res->item;

		if(strpos($item->mpn, $search) === false) { echo "Part {$search} not found in record {$item->mpn}, aborting."; return; }

		$part = $item->mpn;
		$vendor = $item->brand->name;
		foreach($item->descriptions as $des) {
			if(isset($des->attribution->sources[0]->name) && $des->attribution->sources[0]->name == 'LTL Group')
				continue;
			if(isset($des->value)) {
				$desc = $des->value;
		                if(preg_match('/Demo|Breakout|Board|Dev|Experimenter|Xplained|Evaluation|Solution|Meter|RMS|Resistor/i', $desc)) {
					$desc = '';
					$part = '';
		                        echo "Rejecting part from search {$part} - Evaluation model\n";
		                        continue;
                		}

				break;
			}
		}
		break;
	}

	echo "Found part from search {$part}\n";
	
	$params = array(
		'apikey' => '...',
		'queries' => json_encode(array(array('mpn' => $part))),
		'pretty_print' => true,
		'include[]' => 'datasheets',
	);
	
	curl_setopt($ch, CURLOPT_URL, 'https://octopart.com/api/v3/parts/match?' . http_build_query($params) . '&include[]=short_description');

	$ret = curl_exec($ch);
	$data = json_decode($ret);

	if(isset($data->results[0]->items[0]->datasheets[0]->url)) {
		$item = $data->results[0]->items[0];
		$ds = $item->datasheets[0]->url;
		
		$cost = array();
		foreach($item->offers as $offer) {
			if(isset($offer->prices->USD)) {
				foreach($offer->prices->USD as $price) {
					$cost[$offer->seller->name][$price[0]] = $price[1];
				}
			}
		}
		$lowest = 9990;
		foreach($cost as $seller => $price) {
			if(isset($price[1000])) {
				if($price[1000] < $lowest) {
					$lowest = $price[1000];
				}
			} elseif(isset($price[100])) {
				if($price[100] < $lowest) {
					$lowest = $price[100];
				}
			}
		}
		
		$lowest = "$" . round($lowest, 2);
	}
	if(isset($ds)) {
		$ds = str_replace('country=CA&', '', $ds);
	}

	if(strlen($desc) > 80) $desc = '';

	if(isset($vendor) && isset($part) && isset($desc) && isset($ds)) {
		if(isset($lowest) && $lowest != '$9990')
			$out = "{$vendor} {$part} {$desc} ({$lowest} ea @ 1k) - {$ds}";
		else
			$out = "{$vendor} {$part} {$desc} - {$ds}";
	} elseif(isset($vendor) && isset($part) && isset($ds)) {
		$out = "{$vendor} {$part} - {$ds}";
	}
}
}
}
if(!empty($out))
	return $this->send_msg($out);


                $message_parts = explode(' ', $message);
                switch($message_parts[0])
                {
		case '!seen':
	if(empty($message_parts[1]) || !trim($message_parts[1]) || $this->channel == '##electronics') {
		return $who->send_msg('Usage: !seen <nick> - shows how long ago <nick> was last seen - This trigger only operates in private message');
	} 
	$nick = trim($message_parts[1]);
	$user = null;
	if(!empty(IRCServerUser::$users[$nick])) {
		$user = IRCServerUser::getUser($nick);
		if($user->last_activity) {
			return $who->send_msg($nick . ' was last seen ' . ago('@' . $user->last_activity));
		} else {
			return $who->send_msg('I have not seen ' . $nick . ' active yet');
		}
	} else {
		return $who->send_msg('I have not seen a user with name ' . $nick);
	}
						case '!units':
							if(empty($message_parts[1])) {
								$this->send_msg('Usage: !units <fromunit> <tounit> - eg !units 1km mile');
							} else {
if(preg_match('/^!units\s*[\'"]?([^\'"]+)[\'"]?\s+[\'"]?([^\'"]+?)[\'"]?\s*$/im', $message, $res)) {
	list(,$message_parts[1], $message_parts[2]) = $res;
}
if(in_array(substr($message_parts[1], -1) , array('+', '-', '*', '/'))) {
	$message_parts[1] .= ' ' . $message_parts[2];
	$message_parts[2] = '';
}
if(substr($message_parts[1], -3) == 'AWG') {
	if(strpos($message_parts[1], '/') !== FALSE) {
		list($count, $val) = explode('/', $message_parts[1]);
		$message_parts[1] = ($count-1)*-1;
	}
	$message_parts[1] = 'wiregauge(' . floatval($message_parts[1]) . ')';
}
if(substr($message_parts[1], -3) == 'SWG') {
	if(strpos($message_parts[1], '/') !== FALSE) {
                list($count, $val) = explode('/', $message_parts[1]);
                $message_parts[1] = ($count-1)*-1;
        }
	$message_parts[1] = 'brwiregauge(' . floatval($message_parts[1]) . ')';
}
if($message_parts[2] == 'AWG') $message_parts[2] = 'wiregauge';
if($message_parts[2] == 'SWG') $message_parts[2] = 'brwiregauge';

if(preg_match('/^!units\s*(\d+)([CF])\s+([CF])\s*$/im', $message, $res)) {
	$message_parts[1] = 'temp' . $res[2] . '(' . $res[1] . ')';
	$message_parts[2] = 'temp' . $res[3];
}
								exec(escapeshellcmd('units') . ' -v ' . escapeshellarg($message_parts[1]) . ' ' . escapeshellarg($message_parts[2]), $resarr);
								$result = trim($resarr[0]);
if($result == 'reciprocal conversion' || $result == 'conformability error') $result = $resarr[1];
if(substr($result, 0, 3) == '1 /') $result = substr($result, 4);
								$this->send_msg($who->nick . ': ' . $result);
							}
							break;
						case '!say':
							if($who->nick == 'Viper-7') {
								IRCServerChannel::getChannel('##electronics')->send_msg(implode(' ', array_slice($message_parts, 1)));
							}
							break;
						case '!act':
							if($who->nick == 'Viper-7') {
								IRCServerChannel::getChannel('##electronics')->send_action(implode(' ', array_slice($message_parts, 1)));
							}
							break;
						case '!awg':
							if(empty($message_parts[1])) {
								$this->send_msg('Usage: !awg <mils> - convert mils to awg');
							} else {
								exec(escapeshellcmd('units') . ' "' . $message_parts[1] . ' mils" "wiregauge"', $result);
								$result = trim($result[0], " \t\r\n*/");
								$this->send_msg($who->nick . ': ' . $message_parts[1] . ' mils = ' . $result . ' AWG');
							
							}
							break;
						case '!impwire':
							if(empty($message_parts[1])) {
								$this->send_msg('Usage: !impwire <awg> - gives imperial stats for copper wire of <awg> size');
							} else {
								exec(escapeshellcmd('units') . ' "wiregauge(' . $message_parts[1] . ')" "mil"', $result);
								$gauge = $message_parts[1];
								$mil = trim($result[0], " \t\r\n*/");
								$cmil = $mil**2;
								$this->send_msg($who->nick . ': ' . $gauge . 'AWG (' . round($cmil, 2) . 'cmils) @ TPI: ' . round(1000/$mil, 3) . 'turns, Ohm/Ft: ' . round(10.37*(1/$cmil), 6) . ', Diameter: ' . $mil . 'mils');
							}
							break;
						case '!wire':
							if(empty($message_parts[1])) {
								$this->send_msg('Usage: !wire <awg> - gives stats for copper wire of <awg> size, also supports !wire <swg>SWG, !wire <mm diameter>mm, !wire <square mm>mm^2, and !wire <amps>A - gives recommended wire sizes for <amps> amps');
							} else {
if(substr(trim($message_parts[1]), -3) == 'SWG') {
	$result = array();
if(trim($message_parts[1], ' #SAWG') === '00') $message_parts[1] = '-1';
if(trim($message_parts[1], ' #SAWG') === '000') $message_parts[1] = '-2';
if(trim($message_parts[1], ' #SAWG') === '0000') $message_parts[1] = '-3';

	exec(escapeshellcmd('units') . ' -t "brwiregauge(' . floatval(trim($message_parts[1])) . ')" "wiregauge"', $result);
	$message_parts[1] = trim($result[0], " \t\r\n");
	$result = array();
}
								if(substr(trim($message_parts[1]), -2) == 'mm') {
									$message_parts[0] = '!mwire';
									return $this->event_msg($who, implode(' ', $message_parts));
								} else if(substr(trim($message_parts[1]), -3) == 'mm²' || substr(trim($message_parts[1]), -3) == 'mm2' || substr(trim($message_parts[1]), -4) == 'mm^2') {
									$message_parts[0] = '!mwire';
									$message_parts[1] = 2 * sqrt(floatval($message_parts[1]) / 3.14159);
									return $this->event_msg($who, implode(' ', $message_parts));
								} else if(strpos($message_parts[1], '/') !== FALSE) {
									list($count, $dia) = explode('/', trim($message_parts[1]));
if($dia === '0') {
	$message_parts[1] = str_repeat('0', $count);
	return $this->event_msg($who, implode(' ', $message_parts));
}
									$area = pow((floatval($dia) / 2), 2) * 3.14159;
									$area *= $count;
									$dia = 2 * sqrt($area / 3.14159);
									$message_parts[0] = '!mwire';
									$message_parts[1] = $dia;
									return $this->event_msg($who, implode(' ', $message_parts));
								} else if(strtoupper(substr(trim($message_parts[1]), -1)) == 'A') {
									$message_parts[0] = '!wireamps';
									$message_parts[1] = toAmps($message_parts[1]);
									return $this->event_msg($who, implode(' ', $message_parts));
								}

if(trim($message_parts[1], ' #AWG') === '00') $message_parts[1] = '-1';
if(trim($message_parts[1], ' #AWG') === '000') $message_parts[1] = '-2';
if(trim($message_parts[1], ' #AWG') === '0000') $message_parts[1] = '-3';

								$gauge = round($message_parts[1],2);
								exec(escapeshellcmd('units') . ' "wiregauge(' . floatval(trim($message_parts[1])) . ')" "in"', $result);
								$diameter = trim($result[0], " \t\r\n*/");

								if($diameter < 0.0127) {
									$free = 0.31648 * 2.71828183**($diameter/0.00711)-0.42085;
                					$enclosed = 0.07005 * 2.71828183**($diameter/0.00590)+0.13427;
								} else if($diameter < 0.0404) {
									$free = 1.48 * 2.71828183**($diameter/0.01611)-1.9;
									$enclosed = 0.9 * 2.71828183**($diameter/0.0155)-1.1;
								} else {
									$free = 500.01941 * 2.71828183**($diameter/0.79238)-511.15794;
                					$enclosed = 287.19085 * 2.71828183**($diameter/0.76306)-294.40413;
								}
								if($free < 0) $free = 0;
								if($enclosed > $free) $enclosed = 0;
								
								$cmil = ($diameter*1000)**2;
								$sqmm = round($cmil / 1973.5, 2);
								$diamm = round($diameter * 25.4, 2);

								$result = null;
								exec(escapeshellcmd('units') . ' "wiregauge(' . $gauge . ')" "mil"', $result);
								$mil = trim($result[0], " \t\r\n*/");
								$cmil = ($mil**2);///0.7854;
								$sqmm = round($cmil / 1973.5, 2);

								$ohmm = round(10.37*(1/$cmil)/0.3048, 5);
								$ohmft = round(10.37*(1/$cmil), 5);
								$result = null;
								exec(escapeshellcmd('units') . ' -t "wiregauge(' . $gauge . ')" "brwiregauge"', $result);
								$swg = trim($result[0], " \t\r\n*/");
if($swg < 0) { if($swg > -6) { $swg = str_repeat('0', ($swg*-1)+1); } else { $swg = (($swg*-1)+1) . '/0'; } } else { $swg = round($swg, 1); }

if($gauge > 4) { $showcm = true; } else { $showcm = false; }
if($gauge < 0) { if($gauge > -6) { $gauge = str_repeat('0', ($gauge*-1)+1); } else { $gauge = (($gauge*-1)+1) . '/0'; } } else { $gauge = round($gauge, 1); }
								
								if($ohmm > 0.1) {
									$this->send_msg($who->nick . ': ' . $gauge . 'AWG (' . $swg . 'SWG, ' . $sqmm . 'mm², ' . $diamm . 'mm dia, ' . round($mil,1) . ' mils) Cu Wire Free air: ' . round($free, 2) . 'A, Bundled: ' . round($enclosed, 2) . 'A' . ($showcm ? ', Windings: ' . round($cmil/700, 2) . 'A' : '') . ', Ohm/Ft: ' . $ohmft . ', Ohm/m: ' . $ohmm);
								} else {
									$ohmm *= 1000;
									$ohmft *= 1000;

									$this->send_msg($who->nick . ': ' . $gauge . 'AWG (' . $swg . 'SWG, ' . $sqmm . 'mm², ' . $diamm . 'mm dia, ' . round($mil,1) . ' mils) Cu Wire Free air: ' . round($free, 2) . 'A, Bundled: ' . round($enclosed, 2) . 'A' . ($showcm ? ', Windings: ' . round($cmil/700, 2) . 'A' : '') . ', mOhm/Ft: ' . $ohmft . ', mOhm/m: ' . $ohmm);
								}
							}
							break;
						case '!wireamps':

							if(empty($message_parts[1])) {
								$this->send_msg('Usage: !wireamps <amps> - Gives recommended wire sizes for various conditions to carry <amps>');
							} else {
								$amps = floatval(trim($message_parts[1]));

								if($amps < 1.5) {
									$free = log(($amps + 0.42085) / 0.31648) * 0.00711;
								} else if($amps < 15.9) {
									$free = log(($amps + 1.9) / 1.48) * 0.01611;
								} else {
									$free = log(($amps + 511.15794) / 500.01941) * 0.79238;
								}

								if($amps < 1) {
									$enclosed = log(($amps - 0.13427) / 0.07005) * 0.00590;
								} else if($amps < 10.8) {
									$enclosed = log(($amps + 1.1) / 0.9) * 0.0155;
								} else {
									$enclosed = log(($amps + 294.40413) / 287.19085) * 0.76306;
								}

								$freemm = $free * 25.4;
								$enclosedmm = $enclosed * 25.4;

								$cmil = $amps * 700;
								$mil = sqrt($cmil);// * 0.7854);
								exec(escapeshellcmd('units') . ' "' . $mil . ' mil" "wiregauge"', $result);
								$cm700 = trim($result[0], " \t\r\n*/");

								$result = array();
								exec(escapeshellcmd('units') . ' "wiregauge(' . $cm700 . ')" "in"', $result);
								$cm700mm = trim($result[0], " \t\r\n*/") * 25.4;

								$result = array();
								exec(escapeshellcmd('units') . ' "' . $free . ' in" "wiregauge"', $result);
								$free = trim($result[0], " \t\r\n*/");

								$result = array();
								exec(escapeshellcmd('units') . ' "' . $enclosed . ' in" "wiregauge"', $result);
								$enclosed = trim($result[0], " \t\r\n*/");

								$cm700mm2 = pow($cm700mm / 2, 2) * 3.14159;
								$freemm2 = pow($freemm / 2, 2) * 3.14159;
								$enclosedmm2 = pow($enclosedmm / 2, 2) * 3.14159;
if($cm700 < 32) $showcm = true; else $showcm = false;
if($free < 0) { $free = floor($free); if($free > -6) { $free = str_repeat('0', ($free*-1)+1); } else { $free = (($free*-1)+1) . '/0'; } } else { $free = floor($free); }
if($enclosed < 0) { $enclosed = floor($enclosed); if($enclosed > -6) { $enclosed = str_repeat('0', ($enclosed*-1)+1); } else { $enclosed = (($enclosed*-1)+1) . '/0'; } } else { $enclosed = floor($enclosed); }
if($cm700 < 0) { $cm700 = floor($cm700); if($cm700 > -6) { $cm700 = str_repeat('0', ($cm700*-1)+1); } else { $cm700 = (($cm700*-1)+1) . '/0'; } } else { $cm700 = floor($cm700); }


								$this->send_msg($who->nick . ': Recommended Cu wire for ' . round($amps, 2) . 'A in Free Air: ' . $free . 'AWG (' . round($freemm, 2) . 'mm dia, ' . round($freemm2, 2) . 'mm²), Bundled: ' . $enclosed . 'AWG (' . round($enclosedmm, 2) . 'mm dia, ' . round($enclosedmm2, 2) . 'mm²)' . ($showcm ? ', Windings: ' . $cm700 . 'AWG (' . round($cm700mm, 2) . 'mm dia, ' . round($cm700mm2, 2) . 'mm²)' : ''));

							}
							break;
						case '!mwire':
							if(empty($message_parts[1])) {
								$this->send_msg('Usage: !mwire <mm> - gives stats for copper wire of <mm> diameter');
							} else {
								//$diamm = 2*sqrt((floatval(trim($message_parts[1])))/3.14159265);
								$diamm = floatval(trim($message_parts[1]));
								$diameter = $diamm / 25.4;
								
								exec(escapeshellcmd('units') . ' "' . round($diameter, 4) . ' in" "wiregauge"', $result);
								$gauge = trim($result[0], " \t\r\n*/");

								if($diameter < 0.0127) {
									$free = 0.31648 * 2.71828183**($diameter/0.00711)-0.42085;
                					$enclosed = 0.07005 * 2.71828183**($diameter/0.00590)+0.13427;
                                                                } else if($diameter < 0.0404) {
                                                                        $free = 1.48 * 2.71828183**($diameter/0.01611)-
1.9;
                                                                        $enclosed = 0.9 * 2.71828183**($diameter/0.0155
)-1.1;
								} else {
									$free = 500.01941 * 2.71828183**($diameter/0.79238)-511.15794;
                					$enclosed = 287.19085 * 2.71828183**($diameter/0.76306)-294.40413;
								}
								if($free < 0) $free = 0;
								if($enclosed > $free) $enclosed = 0;
								
//								$cmil = ($diameter*1000)**2;
//								$sqmm = round($cmil / 1973.5, 2);

								$result = null;
								exec(escapeshellcmd('units') . ' "wiregauge(' . $gauge . ')" "mil"', $result);
								$mil = trim($result[0], " \t\r\n*/");
								$cmil = ($mil**2);// / 0.7854;
								$sqmm = round($cmil / 1973.5, 2);
								
								$ohmm = round(10.37*(1/$cmil)/0.3048, 5);
								$ohmft = round(10.37*(1/$cmil), 5);

								$diamm = round($diamm, 2);
if($gauge > 4) $showcm = true; else $showcm = false;

if($gauge < 0) { if($gauge > -6) { $gauge = str_repeat('0', ($gauge*-1)+1); } else { $gauge = (($gauge*-1)+1) . '/0'; } } else { $gauge = round($gauge, 1); }
								
								if($ohmm > 0.1) {
									$this->send_msg($who->nick . ': ' . $sqmm . 'mm² (' . $diamm . 'mm dia, ' . $gauge . 'AWG, ' . round($mil,1) . ' mils) Cu Wire Free air: ' . round($free, 2) . 'A, Bundled: ' . round($enclosed, 2) . 'A' . ($showcm ? ', Windings: ' . round($cmil/700, 2) . 'A' : '') . ', Ohm/Ft: ' . $ohmft . ', Ohm/m: ' . $ohmm);
								} else {
									$ohmm *= 1000;
									$ohmft *= 1000;
									$this->send_msg($who->nick . ': ' . $sqmm . 'mm² (' . $diamm . 'mm dia, ' . $gauge . 'AWG, ' . round($mil,1) . ' mils) Cu Wire Free air: ' . round($free, 2) . 'A, Bundled: ' . round($enclosed, 2) . 'A' . ($showcm ? ', Windings: ' . round($cmil/700, 2) . 'A' : '') . ', mOhm/Ft: ' . $ohmft . ', mOhm/m: ' . $ohmm);
								}
							}
							break;
						case '!ampacity':
							if(empty($message_parts[1])) {
								$this->send_msg('Usage: !ampacity <awg> - gives ampacity for copper wire of <awg> size');
							} else {
								exec(escapeshellcmd('units') . ' "wiregauge(' . (float)$message_parts[1] . ')" "in"', $result);
								$diameter = trim($result[0], " \t\r\n*/");
								
								if($diameter < 0.026) {
									$free = 0.31648 * 2.71828183**($diameter/0.00711)-0.42085;
                					$enclosed = 0.07005 * 2.71828183**($diameter/0.00590)+0.13427;
								} else {
									$free = 500.01941 * 2.71828183**($diameter/0.79238)-511.15794;
                					$enclosed = 287.19085 * 2.71828183**($diameter/0.76306)-294.40413;
								}
								if($free < 0) $free = 0;
								if($enclosed > $free) $enclosed = 0;
								
								$cmil = ($diameter*1000)**2;
								
								$this->send_msg($who->nick . ': ' . floatval($message_parts[1]) . 'AWG Cu Wire Free air: ' . round($free, 3) . 'A, Bundled: ' . round($enclosed, 3) . 'A, Windings: ' . round($cmil/700, 3) . 'A');
							}
							break;
						case '!trace':
							if(empty($message_parts[1])) {
								$this->send_msg('Usage: !trace <mils> <oz> - tells ohms of trace <mils> wide on <oz> weight copper clad');
							} else {
								$mils = $message_parts[1];
								$oz = $message_parts[2];
								$ohms = 10.37*(1/($mils*1.755*$oz))/12;
								


								$this->send_msg($who->nick . ': ' . $mils . ' mil wide trace on ' . $oz . 'oz copper clad: ' . round($ohms, 6) . ' Ohms/Inch');
							}
							break;
						case '!traceamp':
							if(empty($message_parts[1])) {
								$this->send_msg('Usage: !traceamp <amps> <oz> <tempRiseInC> - tells required trace width for copper clad of weight <oz> to carry <amps> with a max temperature rise of <tempRiseInC> over ambient');
							} else {
								$amps = $message_parts[1];
								$oz = $message_parts[2];
								$temprise = $message_parts[3];
								$intarea =($amps/(0.0150*($temprise)**0.5453))**(1/0.7349);
								$intwidth=$intarea/($oz*1.378);
								$surfarea=($amps/(0.0647*($temprise)**0.4281))**(1/0.6732);
								$surfwidth=$surfarea/($oz*1.378);
								$this->send_msg($who->nick . ': Internal: ' . round($intwidth,0) . 'mils, Surface: ' . round($surfwidth, 0) . 'mils');
							}
							break;
						case '!help':
							$this->send_msg('Available Triggers: !units, !awg, !wire, !ampacity, !traceohms, !traceamp');
							break;
                }
        }
}

