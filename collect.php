<?php

require 'inc/config.php';


date_default_timezone_set($config['local_tz']);

function get_nest_data() {
  global $config;
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, 'https://developer-api.nest.com/');
  curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/json', 'Authorization: Bearer '.$config['nest_token']));
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
  curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
  curl_setopt ( $ch, CURLOPT_AUTOREFERER, true ); 
  $ret = curl_exec ( $ch );
  curl_close ( $ch );
  $ret = json_decode($ret, true);
  
  //Assume there is only one home and one thermostat
  $current_thermostats = current($ret['devices']['thermostats']);
  $current_home = current($ret['structures']);

  $data = array('heating'      => ($current_thermostats['hvac_mode'] == 'heat' ? 1 : 0),
                'cooling'      => ($current_thermostats['hvac_mode'] == 'cool' ? 1 : 0),
                'fan'          => ($current_thermostats['fan_timer_active'] == true ? 1 : 0),
                'autoAway'     => ($current_home['away'] == 'auto-away' ? 1 : 0),
                'manualAway'   => ($current_home['away'] == 'away' ? 1 : 0),
                'leaf'         => ($current_thermostats['has_leaf'] == true ? 1 : 0),
                'timestamp'    => $current_thermostats['last_connection'],
                'target_temp'  => $current_thermostats['target_temperature_f'],
                'current_temp' => $current_thermostats['ambient_temperature_f'],
                'humidity'     => $current_thermostats['humidity']
               );
  return $data;
}

function c_to_f($c) {
  return ($c * 1.8) + 32;
}

?>