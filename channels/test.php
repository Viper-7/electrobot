<?php
$bat = '546030';
$bdb = new PDO('sqlite:battery.db');
$stmt = $bdb->prepare('select * from batteries');
$stmt->execute();
var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
die();

function send_msg($a) { echo $a; }

        $bdb = new PDO('sqlite:battery.db');

                if(preg_match('/^(\w{2,3}?)(\d+)(?:\w$|$)/', $bat, $match)) {
                        $code = $match[1];
                        $size = $match[2];
                } else {
                        $size = (int)$bat;
                        $code = str_replace($size, '', $bat);
                }

                if(strlen($code) == 2 && strlen($size) == 4) {
                        //CR2032
                        $dim = "coin cell - " . substr($size,0,2) . "mm diameter, " . (substr($size,2)*0.1) . "mm thickness";
                } elseif(strlen($code) == 3) {
                        if(strlen($size == 4)) $size .= '0';
                        $dim = "cylindrical cell - " . (substr($size,0,2)) . "mm diameter, " . (substr($size,2)*0.1) . "mm length";
                } else {
                        if(strlen($size) == 6) {
                                $x = substr($size,2,2);
                                $y = substr($size,4,2);
                                $dim = "prismatic cell - " . (substr($size,0,2)*0.1) . "mm thickness, {$x}mm width, {$y}mm length";
                        } else {
                                $dim = "Unknown Package";
                        }
                }

                $stmt = $bdb->prepare('SELECT batteries.id, batteries.code, name, cutoff, nominal, maxcharge, rechargable FROM batteries JOIN batterycodes WHERE batterycodes.batteryid=batteries.id AND batterycodes.code LIKE ?');
                $stmt->execute(array($code));
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if($data) {
                        $b = $data[0];
                        return send_msg("{$b['Code']}{$size} - {$b['Name']} - Nominal Voltage: {$b['Nominal']}V, " . ($b['MaxCharge']?"Valid Range {$b['Cutoff']}V-{$b['MaxCharge']}V":"Cutoff {$b['Cutoff']}V") . " - " . ($b['Rechargable']?'':'NOT ') . "Rechargeable {$dim}");
                } else {
                        return send_msg(ucfirst($dim) . ' - Unknown Chemistry');
                }


