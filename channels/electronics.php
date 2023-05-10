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

function tinyurl($url) {
return $url;
//	return file_get_contents('http://tinyurl.com/api-create.php?url=' . urlencode($url));
}

function partByCode($code) {
	$suffix = '';
	$unit = '';
	
	if(preg_match('/^(\d?)([rRkKMG])(\d+)\s*(\w*?)$/', trim($code), $match)) {
		if(strtoupper($match[2]) == 'R')
			$mul = 0.1;
		elseif(strtolower($match[2]) == 'k')
			$mul = 1000;
		elseif($match[2] == 'M')
			$mul = 1000000;
		elseif($match[2] == 'G')
			$mul = 1000000000;
		
		$code = $match[1] . $match[3];
		$unit = $match[4];
		if(strlen($match[3]) > 1) {
			$mul /= pow(10, strlen($match[3])-1);
		}
	} elseif(preg_match('/^(\d+)\s*([a-zA-Z]*?)$/', trim($code), $match)) {
		list($_, $code, $unit) = $match + array('','');
		$mul = substr($code, -1);
		$code = substr($code, 0, -1);
		$mul = pow(10, $mul);
	}
	
	if(isset($mul)) {
		
		if($mul > 10000000) {
			$mul /= 1000000000;
			$suffix = 'G';
		} elseif($mul > 10000) {
			$mul /= 1000000;
			$suffix = 'M';
		} elseif($mul > 10) {
			$mul /= 1000;
			$suffix = 'k';
		}
		
		$result = round($code * $mul, 2);
		
		$unit = trim(strtoupper($unit));
		
		if($unit == 'F' || $unit == 'FARAD' || $unit == 'CAPACITOR' || $unit == '') {
			$map = array('k' => 'n', 'M' => 'μ', 'G' => 'm');
			if(array_key_exists($suffix, $map))
				$suffix = $map[$suffix];
			else
				$suffix = 'p';
			$unit = 'F capacitor';
		} elseif($unit == 'H' || $unit == 'HENRY' || $unit == 'INDUCTOR') {
			$map = array('k' => 'm', 'M' => '', 'G' => 'k');
			if(array_key_exists($suffix, $map))
				$suffix = $map[$suffix];
			else
				$suffix = 'μ';
			$unit = 'H inductor';
		} elseif($unit == 'OHM' || $unit == 'OHMS' || $unit == 'RESISTOR' || $unit == 'Ω') {
			$map = array('k' => 'k', 'M' => 'M', 'G' => 'G');
			if(array_key_exists($suffix, $map))
				$suffix = $map[$suffix];
			else
				$suffix = '';
			$unit = 'Ω resistor';
		} else {
			$unit = '';
		}
		
		return "{$result}{$suffix}{$unit}";
	}
}

function partByColor($code) {
	$valid = array('black', 'brown', 'red', 'orange', 'yellow', 'green', 'blue', 'violet', 'purple', 'grey', 'gray', 'white', 'gold', 'silver');

	$codes = array(
		'black' => 0,
		'brown' => 1,
		'red' => 2,
		'orange' => 3,
		'yellow' => 4,
		'green' => 5,
		'blue' => 6,
		'violet' => 7,
		'purple' => 7,
		'grey' => 8,
		'gray' => 8,
		'white' => 9,
	);

	$muls = array(
		'black' => 0,
		'brown' => 1,
		'red' => 2,
		'orange' => 3,
		'yellow' => 4,
		'green' => 5,
		'blue' => 6,
		'violet' => 7,
		'purple' => 7,
		'grey' => 8,
		'gray' => 8,
		'gold' => 0.1,
		'silver' => 0.01
	);
	
	$tolerances = array(
		'brown' => 1,
		'red' => 2,
		'green' => 0.5,
		'blue' => 0.25,
		'violet' => 0.1,
		'purple' => 0.1,
		'grey' => 0.05,
		'gray' => 0.05,
		'gold' => 5,
		'silver' => 10
	);
	
	$parts = explode(' ', trim($code));
	$colors = array();
	foreach($parts as $v) {
		$v = strtolower($v);
		if(in_array($v, $valid))
			$colors[] = $v;
	}
	
	if(count($colors) < 3) return;
	
	$suffix = '';
	if(count($parts) == count($colors) + 1)
		$suffix = $parts[count($parts)-1];
	
	if(count($colors) == 5) {
		$val = array_slice($colors, 0, 3);
		$mul = $muls[$colors[3]];
		$tol = $tolerances[$colors[4]];
	} elseif(count($colors) == 4) {
		if(isset($tolerances[$colors[3]])) {
			// 3 value bands plus tolerance
			$val = array_slice($colors, 0, 2);
			$mul = $muls[$colors[2]];
			$tol = $tolerances[$colors[3]];
		} else {
			if(isset($muls[$colors[3]])) {
				// 4 value bands, no tolerance
				$val = array_slice($colors, 0, 3);
				$mul = $muls[$colors[3]];
				$tol = '';
			} else {
				return 'Invalid color code';
			}
		}
	} elseif(count($colors) == 3) {
		$val = array_slice($colors, 0, 2);
		$mul = $muls[$colors[2]];
		$tol = '';
	}
	
	$out = '';
	foreach($val as $k => $v) {
		$out .= $codes[$v];
	}
	
	if($mul == 0.1) {
		$first = substr($out, 0, 1);
		$rest = substr($out, 1);
		$call = "{$first}R{$rest}";
	} elseif($mul == 0.01) {
		$first = 0;
		$rest = $out;
		$call = "{$first}R{$rest}";
	} else {
		$call = "{$out}{$mul}";
	}
	
	$callval = $call;
	
	if($suffix)
		$call .= " {$suffix}";
	else
		$call .= ' resistor';
	
	echo 'Calling partByCode with ';
	var_dump($call);
	$res = partByCode($call);
	if($tol) {
		$tolsuffix = '';
		if(preg_match('/^([0-9\.]+)([kMG]?)(.*)$/', $res, $valsuffix)) {
			$out = $valsuffix[1];
			$unit = explode(' ', $valsuffix[3])[0];
			if($valsuffix[2] == ' kilo') $valsuffix = 'k' . $unit;
			elseif($valsuffix[2] == ' mega') $valsuffix = 'M' . $unit;
			elseif($valsuffix[2] == ' giga') $valsuffix = 'G' . $unit;
			else $valsuffix = $valsuffix[2] . $unit;
			
		} else {
			$valsuffix = '';
		}
		
		if($mul > 0.1) {
			$mintol = $out - ($out * ($tol/100)) . $valsuffix;
			$maxtol = $out + ($out * ($tol/100)) . $valsuffix;
			$tolsuffix = " ({$mintol} - {$maxtol})";
		}
		return "[$callval] {$res} with {$tol}% Tolerance{$tolsuffix}";
	} else
		return "[$callval] {$res}";
}

class electronics extends IRCServerChannel
{
	public $lastUsed = array();
	public $recentParts = array();
	public $recentPartTimes = array();

	public function event_joined() {
	}

	public function event_msg($who, $message)
	{

		if(preg_match('/(https?:\/\/.*?)(?:\s+|$)/', $message, $match)) {

	if($who->nick == 'mnrmnaugh') return;
//	if(substr($who->nick,0,8) == 'password') return;

			$url = $match[1];

			$ourl = $url;
			$c = curl_init($url);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($c, CURLOPT_COOKIE, 'PREF=f1=50000000&gl=AU&al=en&f6=400&f5=30000&hl=en');
			curl_setopt($c, CURLOPT_HEADER, true);
			curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($c, CURLOPT_TIMEOUT, 5);

			$ret = curl_exec($c);
			curl_close($c);
			$out = '';

			$orghost = parse_url($url, PHP_URL_HOST);

			// Fetch destination host from redirect
			if(preg_match('/^Location:\s*(.*)\s*$/im', $ret, $match)) {
					$url = $match[1];
					$host = parse_url($url, PHP_URL_HOST);
			} else {
					$url = '';
					$host = '';
			}
			var_dump($orghost, $host, $url, $ourl);
			
			// Fetch page title
			if(preg_match('/<title[^>]*>(.*?)<\/title>/i', $ret, $match)) {
					$title = $match[1];
			} else {
					$title = '';
			}

			// Fetch Youtuber name
			if(preg_match('/"author":"(.*?)"/i', $ret, $matchowner)) {
				$owner = $matchowner[1];
			} else {
				$owner = '';
			}

			if(preg_match('/itemprop="datePublished" content="(.*?)"/', $ret, $matchdate)) {
				$postdate = $matchdate[1];
			} else {
				$postdate = '';
			}

			if(preg_match('/itemprop="duration" content="PT(.*?)M(.*?)S"/', $ret, $matchdur)) {
				$duration = "{$matchdur[1]}:" . str_pad($matchdur[2],2,'0',STR_PAD_LEFT);
			} else {
				$duration = '';
			}

			if(preg_match('/itemprop="isLiveBroadcast" content="True"/', $ret, $matchlive)) {
				$duration = 'Livestream';
				$postdate = '';
			}

			// Ignore regional redirects due to OVH hosting
			if(substr($orghost, 0, 3) == 'www' && (substr($host, 0, 2) == 'it' || substr($host, 0, 2) == 'fr' || substr($host, 0, 2) == 'br')) {
				$host = '';
				$title = '';
			}

			// Ignore globalized redirects
			if($orghost == str_replace('.au', '', $host)) {
				$host = '';
				$title = '';
			}

			// ???
			if($orghost == substr($host, 4) || substr($orghost, 4) == $host || substr($host, 2) == $orghost) {
				$host = '';
				$title = '';
			}

			// Ignore list
			if($orghost == 'ali.viper-7.com' || $host == 'sec.aliexpress.com' || substr($host, -3) == '.fr' || substr($host, -3) == '.ca' || substr($host, -3) == '.br' || $orghost == 'goo.gl') {
				$orghost = '';
				$host = '';
				$title = '';
			}

			// Strip ali regional redirects
			if(preg_match('/(\w+)\.aliexpress.com/i', $orghost, $ali) && $orghost != 's.click.aliexpress.com') {
				if($ali[1] == 'tmall') {
					$out = "Warning! clicking TMall links will change your aliexpress language to Russian. Click Use Global Site (English) in the top right of the window to reset it";
				} elseif($ali[1] != 'www' && $ali[1] != 'my' && $ali[1] != 'm' && $ali[1] != 's.click') {
					if(!$url) $url = $ourl;
					var_dump($url);
					$url = preg_replace('/\w+.aliexpress\.com\/item\/[^\/]+?\/(.*)(?:\?.+)?$/i', 'www.aliexpress.com/item//$1', $url);
					if($url && $url != $ourl) {
						var_dump($url);
						$out = "Non-localized version of {$who->nick}'s link: {$url}";
					}
				}
			}

			if(substr(trim($host),0,10) == 'www.amazon' && substr($orghost,0,12) == 'smile.amazon') {
				$host = '';
				$title = '';
			}

			// Ignore google links
			if(substr(trim($host),0,11) == 'ipv4.google' || substr($orghost, 0, 10) == 'www.google') {
				$host = '';
				$title = '';
			}

			// Ignore pdf subdomain redirects
			if(substr($orghost,0,3) == 'pdf' && substr($host,0,3) == 'www') {
				$host = ''; $title = '';
			}

			// De-HTMLify title text
			if($title) {
				$title = html_entity_decode($title, ENT_QUOTES | ENT_HTML401, 'UTF-8');
			}

			if($host == 'login.aliexpress.com') return;

			// Advertise link target
			if(!$out && $orghost != $host && $host != '' && $orghost != 's.click.aliexpress.com' && $orghost != 'drive.google.com') {
				$out = $who->nick . ' just linked to ' . $host;
				if($title) {
						$out .= ' (' . $title . ')';
				}
			} elseif($title && ($orghost == 'youtube.com' || $orghost == 'www.youtube.com' || $orghost == 'youtu.be' || $orghost == 's.click.aliexpress.com')) {
				$title = str_replace(' - YouTube', '', $title);
                                $title = str_replace(' | ', '', $title);
                                $title = str_replace('Alibaba Group', '', $title);

				$out = $who->nick . ' just linked to ' . $title;
				if($owner)
					$out .= ' - ' . $owner;
				if($postdate)
					$out .= ' - ' . $postdate;

				if($duration)
					$out .= ' - ' . $duration;

				if($orghost == 's.click.aliexpress.com') {
//					$tiny = tinyurl($ourl);
//					if($tiny) { $out .= ' - ' . $tiny; }
				}

			} elseif($title && $orghost == 'www.aliexpress.com') {
var_dump($ourl);
				if(preg_match('/\.com\/item\/\d{9}/', $ourl, $match)) {
var_dump($match);
					$title = str_replace(' | ', '', $title);
					$title = str_replace('Alibaba Group', '', $title);
					$title = preg_replace('/\-in[\w\s]+from[\w\s]+on\sAliexpress.com$/is', '', $title);

					if(strlen($title) > 50) $title = substr($title,0,45) . '...';

					$out = $who->nick . ' just linked to ' . $title;
					$tiny = tinyurl($ourl);
					if($tiny) { $out .= ' - ' . $tiny; }
				}
			}
		}

		if(substr($message,0,1) == '!' && !empty($this->users)) {
			if(!empty($this->lastUsed[$who->nick][6])) {
				if($this->lastUsed[$who->nick][6] > time() - 300) {
					return $this->send_msg($who->nick . ': You have used too many triggers in the past 5 minutes, please PM me in future');
				}
			}
                        if(!empty($this->lastUsed[$who->nick][5])) {
                                        $this->lastUsed[$who->nick][6] = $this->lastUsed[$who->nick][2];
                        }
                        if(!empty($this->lastUsed[$who->nick][4])) {
                                        $this->lastUsed[$who->nick][5] = $this->lastUsed[$who->nick][2];
                        }
                        if(!empty($this->lastUsed[$who->nick][3])) {
                                        $this->lastUsed[$who->nick][4] = $this->lastUsed[$who->nick][2];
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

		if($this->channel != '##electronics' && substr($message, 0, 4) == 'http') {
			//$this->send_msg(file_get_contents('http://tinyurl.com/api-create.php?url=' . urlencode($message)));
			return;
		}

		if(substr($message, 0, 5) == '!part') {
			$message = substr($message, 6);

			if(empty($out)) {
				//if(preg_match_all('/\b([0-9]{0,2}[A-Z]{1,6}[0-9]{1,5}(?:[A-Z]{1,2}[0-9]{2,4})?(?:[A-Z0-9]{2,5})?)(?:\b|\?)/', $message, $match)) {
				
				$out = partByColor($message);
				if(!$out) $out = partByCode($message);
				if(!$out) {
					$part = strtoupper($message);
					$part = explode(' ', $part)[0];

					//	$part = $match[1][0];

					$search = $part;

					//	$banned = array('ESP8266','STM32','STM8','TP4056','SOT23','N64','PS2','PS3','PS4','PS1','ESP1','ESP32','USB3','USB2','USB1','USB','ATMEGA328','ATMEGA168','CC0','C18','CC1','RS232','RS485','RS422');
					//	if(in_array($part, $banned)) return;
					// || count($match[1]) > 1 || preg_match('/\b[0-9]{1,2}[A-Z][0-9]\b/', $part) || preg_match('/\bATTINY|shell|Solution/i', $part) || preg_match('/\bhttps?:\/\//', $message) || preg_match('/\b\w\d\w\d\b/', $part) || preg_match('/^\w{2}\d$|^STM32[FL]\d$/', $part) || preg_match('/HTTP|^DS\d+\w?$|0X[a-f0-9]{1,2}|^\w{2,3}\d$|^DIP\d+$/',$part)) return;

					/*	if(in_array($part, $this->recentParts)) {
							if($this->recentPartTimes[$part] > time() - (6*60*60)) {
								return;
							}
						}

						if(count($this->recentParts) > 10) {
							$this->recentParts = array_slice($this->recentParts, -10, 10);
						}
					*/
					//	if(preg_match('/^[A-Z][0-9]{1,2}$/', $part)) return;

					//	$this->recentParts[] = $part;
					//	$this->recentPartTimes[$part] = time();
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'https://octopart.com/api/v3/parts/search?apikey=58710656&include[]=descriptions&q=' . $part);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					$ret = curl_exec($ch);

					file_put_contents('/tmp/lastsearch', $ret);

					$item = null;
					
					$data = json_decode($ret);
					foreach($data->results as $res) {
						$item = $res->item;
						if($item->mpn == $search) break;
						$item = null;
					}
					
					if(!$item) {
						foreach($data->results as $res) {
							$item = $res->item;
							if(strpos($item->mpn, $search) !== false) { break; }
							$item = NULL;
						}
					}
					
					if($item) {
						$part = $item->mpn;
						$vendor = $item->brand->name;
						echo "Found {$part} from {$vendor}\n";

						foreach($item->descriptions as $des) {
							if(isset($des->attribution->sources[0]->name) && $des->attribution->sources[0]->name == 'LTL Group')
								continue;
							if(isset($des->value)) {
								$desc = $des->value;
								echo "{$desc}\n";

								if(preg_match('/eval|Demo|Breakout|Board|Development|Experimenter|Xplained|Evaluation|Solution|Meter|RMS|Resistor/i', $desc)) {
									$desc = '';
									$part = '';
									echo "Rejecting part from search {$part} - Evaluation model\n";
									continue;
								}
								break;
							}
						}
					}

					if(!isset($part) || $part == '') {
						$this->send_msg('No part found');
					}

					echo "Found part from search {$part}\n";
					
					$params = array(
						'apikey' => '58710656',
						'queries' => json_encode(array(array('mpn' => $part))),
						'pretty_print' => true,
						'include[]' => 'datasheets',
					);
					
					curl_setopt($ch, CURLOPT_URL, $qry = 'https://octopart.com/api/v3/parts/match?' . http_build_query($params) . '&include[]=short_description');
					$ret = curl_exec($ch);
					file_put_contents('/tmp/lastmatch', $ret);

					$data = json_decode($ret);


					if(!isset($data->results) || count($data->results) == 0) {
						echo 'No match found for mpn ' . $part . "\n";
						echo "{$qry}\n";
					}

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
//						$tiny = tinyurl($ds);
//						if($tiny) $ds=$tiny;
					}

					if(isset($desc) && strlen($desc) > 80) unset($desc);

					if(isset($vendor) && isset($part) && isset($desc) && isset($ds)) {
						if(isset($lowest) && $lowest != '$9990')
							$out = "{$vendor} {$part} {$desc} ({$lowest} ea @ 1k) - {$ds}";
						else
							$out = "{$vendor} {$part} {$desc} - {$ds}";
					} elseif(isset($vendor) && isset($part) && isset($ds)) {
						if(isset($lowest) && $lowest != '$9990')
							$out = "{$vendor} {$part} ({$lowest} ea @ 1k) - {$ds}";
						else
							$out = "{$vendor} {$part} - {$ds}";
					} else {
						$this->send_msg("Sorry, I couldn't find any data for {$part}");
					}
				}
			}
		}
		
		if(!empty($out))
			return $this->send_msg($out);

		$message_parts = explode(' ', $message);
		switch($message_parts[0])
		{
case '!battery':
	$bdb = new PDO('sqlite:battery.db');

if(count($message_parts) == 1) {
	$stmt = $bdb->prepare('SELECT code, name, cutoff, nominal, maxcharge FROM batteries WHERE rechargable=1');
	$stmt->execute();
	$set = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$list = array();
	foreach($set as $data) {
		$list[] = "{$data['Code']} ({$data['Name']}) {$data['Cutoff']}V-{$data['Nominal']}V-{$data['MaxCharge']}V";
	}

	return $this->send_msg(implode(', ', $list));
} else {
	$bat = trim($message_parts[1]);
	if($bat == '2032') $bat = 'CR2032';
		if(preg_match('/^(\w{2,3}?)(\d+)(?:[a-zA-Z]*$)/', $bat, $match)) {
                        $code = $match[1];
                        $size = $match[2];
                } else {
                        $size = (int)$bat;
                        $code = str_replace($size, '', $bat);
                }

                if(!is_numeric($code) && strlen($code) == 2 && strlen($size) == 4) {
                        $dim = "coin cell - " . substr($size,0,2) . "mm diameter, " . (substr($size,2)*0.1) . "mm thickness";
                } elseif(strlen($code) == 3 || strlen($bat) == 4 || strlen($bat) == 5) {
			$size = preg_replace('/[^0-9]+/','', $bat);
                        if(strlen($size) == 4) $size .= '0';
                        $dim = "cylindrical cell - " . (substr($size,0,2)) . "mm diameter, " . (substr($size,2)*0.1) . "mm length";
                } else {
			if(strlen($code) == 2 && strlen($size) == 4) $size = $code.$size;
                        if(strlen($size) == 6) {
                                $x = substr($size,2,2);
                                $y = substr($size,4,2);
                                $dim = "prismatic cell - " . (substr($size,0,2)*0.1) . "mm thickness, {$x}mm width, {$y}mm length";
                        } else {
                                $dim = "unknown package";
                        }
                }

                $stmt = $bdb->prepare('SELECT batteries.id, batteries.code, name, cutoff, nominal, maxcharge, rechargable FROM batteries JOIN batterycodes WHERE batterycodes.batteryid=batteries.id AND batterycodes.code LIKE ?');
                $stmt->execute(array($code));
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if($data) {
                        $b = $data[0];
                        return $this->send_msg("{$b['Code']}{$size} - {$b['Name']} - Nominal Voltage: {$b['Nominal']}V, " . ($b['MaxCharge']?"Valid Range {$b['Cutoff']}V-{$b['MaxCharge']}V":"Cutoff {$b['Cutoff']}V") . " - " . ($b['Rechargable']?'':'NOT ') . "Rechargeable {$dim}");
                } else {
                        return $this->send_msg(ucfirst($dim) . ' - Unknown chemistry - Assume 3.0V-4.2V Valid Range.');
                }
}
			case '!redneck':
				$db = new PDO('sqlite:redneck.db');
				$stmt = $db->prepare('SELECT id, line FROM redneck WHERE said=(SELECT MIN(said) FROM redneck) ORDER BY RANDOM() LIMIT 1');
				$stmt2 = $db->prepare('UPDATE redneck SET said=said+1 WHERE id=?');

				$stmt->execute();
				$data = $stmt->fetchAll();
				$stmt2->execute(array($data[0]['id']));
				$nick = trim($who->nick);
                                if($nick == 'Viper-7') {
                                        if(isset($message_parts[1]))
                                                $nick = $message_parts[1];
                                        else
                                                $nick = 'tawr';
                                }

				return $this->send_msg($nick . ' § You might be a redneck if: ' . $data[0]['line']);
                        case '!yomama':
                                $db = new PDO('sqlite:redneck.db');
                                $stmt = $db->prepare('SELECT id, line FROM mama WHERE said=(SELECT MIN(said) FROM mama) ORDER BY RANDOM() LIMIT 1');
                                $stmt2 = $db->prepare('UPDATE mama SET said=said+1 WHERE id=?');

                                $stmt->execute();
                                $data = $stmt->fetchAll();
                                $stmt2->execute(array($data[0]['id']));
				$nick = trim($who->nick);
				if($nick == 'Viper-7') {
					if(isset($message_parts[1]))
						$nick = $message_parts[1];
					else
						$nick = 'password2';
				}

                                return $this->send_msg($nick . ' § ' . $data[0]['line']);
                        case '!chuck':
                                $db = new PDO('sqlite:redneck.db');
                                $stmt = $db->prepare('SELECT id, line FROM chuck WHERE said=(SELECT MIN(said) FROM chuck) ORDER BY RANDOM() LIMIT 1');
                                $stmt2 = $db->prepare('UPDATE chuck SET said=said+1 WHERE id=?');

                                $stmt->execute();
                                $data = $stmt->fetchAll();
                                $stmt2->execute(array($data[0]['id']));
                                return $this->send_msg($who->nick . ' § ' . html_entity_decode($data[0]['line']));

			case '!dean':
				$fp = fopen('dean.txt', 'r');
				$lines = array();
				while(!feof($fp)) $lines[] = fgets($fp);
				$lines = array_filter($lines, 'strlen');
				$key = array_rand($lines);
				$line = $lines[$key];
				return $this->send_msg($who->nick . ' § ' . html_entity_decode($line));
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
					if(preg_match('/^!units\s*[\'"]?([^\'"]+)[\'"]?\s+(?:to|in|by|as)\s+[\'"]?([^\'"]*?)[\'"]?\s*$/im', $message, $res)) {
						list(,$message_parts[1], $message_parts[2]) = $res;
					} elseif(preg_match('/^!units\s*[\'"]?([^\'"]+)[\'"]?\s+[\'"]?([^\'"]*)[\'"]?\s*$/im', $message, $res)) {
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

					if(preg_match('/^!units\s*(\d+)([CKF])\s+([CKF])\s*$/im', $message, $res)) {
						$message_parts[1] = 'temp' . strtoupper($res[2]) . '(' . $res[1] . ')';
						$message_parts[2] = 'temp' . strtoupper($res[3]);
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
			case '!impwire':
				if(empty($message_parts[1])) {
					$this->send_msg('Usage: !impwire <awg> - gives imperial stats for copper wire of <awg> size');
				} else {
					exec(escapeshellcmd('units') . ' "wiregauge(' . ((double)$message_parts[1]) . ')" "mil"', $result);
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
						if(preg_match('/^0+$/', $trimmed = trim($message_parts[1], ' #SAWG'))) {
								$count = substr_count($trimmed, '0');
								$message_parts[1] = 1 + ($count * -1);
						}

						exec(escapeshellcmd('units') . ' -t "brwiregauge(' . floatval(trim($message_parts[1])) . ')" "wiregauge"', $result);
						$message_parts[1] = trim($result[0], " \t\r\n");
						$result = array();
					}
					
					if(substr(trim($message_parts[1]), -3) == 'mil' || substr(trim($message_parts[1]), -4) == 'mils') {
						$result = array();
						if(preg_match('/^0+$/', $trimmed = trim($message_parts[1], ' #mils'))) {
								$count = substr_count($trimmed, '0');
								$message_parts[1] = 1 + ($count * -1);
						}

						exec(escapeshellcmd('units') . ' -t "' . floatval(trim($message_parts[1])) . ' mils" "wiregauge"', $result);
						$message_parts[1] = trim($result[0], " \t\r\n");
						$result = array();
					}
					
					if(substr(trim($message_parts[1]), -2) == 'mm') {
						$message_parts[0] = '!mwire';
						return $this->event_msg($who, implode(' ', $message_parts));
					} else if(substr(trim($message_parts[1]), -3) == 'mm²' || substr(trim($message_parts[1]), -1 * strlen('mm²')) == 'mm²' || substr(trim($message_parts[1]), -3) == 'mm2' || substr(trim($message_parts[1]), -4) == 'mm^2') {
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

					if(preg_match('/^0+$/', $trimmed = trim($message_parts[1], ' #AWG'))) {
							$count = substr_count($trimmed, '0');
							$message_parts[1] = 1 + ($count * -1);
					}

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
							$free = 1.48 * 2.71828183**($diameter/0.01611)-1.9;
							$enclosed = 0.9 * 2.71828183**($diameter/0.0155)-1.1;
					} else {
						$free = 500.01941 * 2.71828183**($diameter/0.79238)-511.15794;
						$enclosed = 287.19085 * 2.71828183**($diameter/0.76306)-294.40413;
					}
					if($free < 0) $free = 0;
					if($enclosed > $free) $enclosed = 0;
					
					//	$cmil = ($diameter*1000)**2;
					//	$sqmm = round($cmil / 1973.5, 2);

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
			case '!trace':
				if(empty($message_parts[1])) {
					$this->send_msg('Usage: !trace <mils> <oz> - tells ohms of trace <mils> wide on <oz> weight copper clad');
				} else {
					$mils = trim($message_parts[1], 'mils');
					$oz = $message_parts[2] ?: 1;
					$ohms = 10.37*(1/($mils*1.755*$oz))/12;

					$this->send_msg($who->nick . ': ' . $mils . ' mil wide trace on ' . $oz . 'oz copper clad: ' . round($ohms, 6) . ' Ohms/Inch');
				}
				break;
			case '!tracea':
				if(empty($message_parts[1])) {
					$this->send_msg('Usage: !tracea <amps> <oz> <tempRiseInC> - tells required trace width for copper clad of weight <oz> to carry <amps> with a max temperature rise of <tempRiseInC> over ambient');
				} else {
					$amps = $message_parts[1];
					$oz = $message_parts[2] ?: 1;
					$temprise = $message_parts[3] ?: 10;
					$intarea =($amps/(0.0150*($temprise)**0.5453))**(1/0.7349);
					$intwidth=$intarea/($oz*1.378);
					$surfarea=($amps/(0.0647*($temprise)**0.4281))**(1/0.6732);
					$surfwidth=$surfarea/($oz*1.378);
					$this->send_msg($who->nick . ': Internal: ' . round($intwidth,0) . 'mils, Surface: ' . round($surfwidth, 0) . 'mils for a ' . $temprise . 'C temperature rise on ' . $oz . 'oz copper at ' . $amps . " amps");
				}
				break;
			case '!drill':
				if(empty($message_parts[1])) {
					$this->send_msg('Usage: !drill <size> - tells required tap & clearance drill sizes for an M<size> bolt');
				} else {
					$thread = trim($message_parts[1], ' Mm');
					$tapdrill = array(
						'1' => array('0.25' => 0.75),
						'1.1' => array('0.25' => 0.85),
						'1.2' => array('0.25' => 0.95),
						'1.4' => array('0.3' => 1.1),
						'1.6' => array('0.35' => 1.25),
						'1.8' => array('0.35' => 1.45),
						'2' => array('0.4' => 1.6),
						'2.2' => array('0.45' => 1.75),
						'2.5' => array('0.45' => 2.05),
						'3' => array('0.5' => 2.5),
						'3.5' => array('0.6' => 2.9),
						'4' => array('0.35' => 3.6, '0.5' => 3.5, '0.7' => 3.3),
						'4.5' => array('0.75' => 3.7),
						'5' => array('0.5' => 3.5, '0.8' => 4.2),
						'6' => array('0.5' => 5.5, '0.75' => 5.25, '1' => 5),
						'7' => array('0.75' => 6.25, '1' => 6),
						'8' => array('0.5' => 7.5, '0.75' => 7.25, '1' => 7, '1.25' => 6.8),
						'9' => array('1' => 8, '1.25' => 7.8),
						'10' => array('0.75' => 9.25, '1' => 9, '1.25' => 8.75, '1.5' => 8.5),
						'11' => array('1' => 10, '1.5' => 9.5),
						'12' => array('0.75' => 11.25, '1' => 11, '1.5' => 10.5, '1.75' => 10.2),
						'14' => array('1' => 13, '1.25' => 12.8, '1.5' => 12.5, '2' => 12),
						'16' => array('1' => 15, '1.5' => 14.5, '2' => 14),
						'18' => array('1' => 17, '2' => 16, '2.5' => 15.5),
						'20' => array('1' => 19, '1.5' => 18.5, '2' => 18, '2.5' => 17.5),
						'22' => array('1' => 21, '1.5' => 20.5, '2' => 20, '2.5' => 19.5),
						'24' => array('1.5' => 22.5, '2' => 22, '3' => 21),
						'27' => array('1.5' => 25.5, '2' => 25, '3' => 24),
						'30' => array('1.5' => 28.5, '2' => 28, '3.5' => 26.5),
						'33' => array('2' => 31, '3.5' => 29.5),
						'36' => array('3' => 36, '4' => 32),
						'39' => array('4' => 35),
						'42' => array('4.5' => 37.5),
						'45' => array('4.5' => 40.5),
						'48' => array('5' => 43),
						'52' => array('5' => 47),
					);
					
					$threads = array(1.6, 2, 2.5, 3, 3.5, 4, 5, 6, 7, 8, 10, 12, 14, 16, 18, 20, 22, 24, 27, 30, 33, 36, 39, 42, 45, 48, 52);
					$close = array(1.7, 2.2, 2.7, 3.2, null, 4.3, 5.3, 6.4, 7.4, 8.4, 10.5, 13, 15, 17, 19, 21, 23, 25, 28, 31, 34, 37, 40, 43, 46, 50, 54);
					$normal = array(1.8, 2.4, 2.9, 3.4, null, 4.5, 5.5, 6.6, 7.6, 9, 11, 14, 16, 18, 20, 22, 24, 26, 30, 33, 36, 39, 42, 45, 48, 52, 56);
					$loose = array(2, 2.6, 3.1, 3.6, null, 4.8, 5.8, 7, 8, 10, 12, 15, 17, 19, 21, 24, 26, 28, 32, 35, 38, 42, 45, 48, 50, 54, 58);
					$max = array(2.25, 2.85, 3.4, 3.9, 4.1, 5.1, 6.1, 7.36, 8.36, 10.36, 12.36, 14.93, 16.93, 19.02, 21.52, 24.52, 26.52, 28.52, 32.62, 35.62, 38.62, 42.62, 45.62, 48.62, 52.74, 56.47, 62.74);
					
					$message = "";
					if($key = array_search($thread, $threads)) {
						exec(escapeshellcmd('units') . ' -t ' . escapeshellarg($normal[$key] . 'mm') . ' mils', $resarr);
	                                        $result = trim($resarr[0]);
        	                                if($result == 'reciprocal conversion' || $result == 'conformability error') $result = $resarr[1];
						$result = round($result);

						$message .= "Clearance drills for an M{$thread} bolt: {$normal[$key]}mm [{$result} mils] ({$close[$key]}mm - {$loose[$key]}mm recommended, {$max[$key]}mm max). ";
					}
					
					if(isset($tapdrill[$thread])) {
						$message .= "Tap drills: ";
						$taps = array();
						foreach($tapdrill[$thread] as $pitch => $dia) {
							$taps[] = "{$dia}mm drill for {$pitch}mm pitch";
						}
						$message .= implode(', ', $taps);
					}
					
					if(!$message) { 
						$message = $who->nick . ": I do not have size data for M{$thread} bolts";
					} else {
						$message = $who->nick . ": " . $message;
					}
					
					$this->send_msg($message);
				}
				break;
			case '!help':
				$this->send_msg('Available Triggers: !units, !wire, !impwire, !trace, !tracea, !drill');
				break;
		}
	}
}


