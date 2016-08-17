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
  curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
  curl_setopt ( $ch, CURLOPT_AUTOREFERER, true ); 
  $ret = curl_exec ( $ch );
  curl_close ( $ch );
  $ret = json_decode($ret, true);
  
  

  $data = array('heating'      => ($ret['devices']['thermostats'][0]['hvac_mode'] == 'heat' ? 1 : 0),
                'cooling'      => ($ret['devices']['thermostats'][0]['hvac_mode'] == 'cool' ? 1 : 0),
                'fan'          => ($ret['devices']['thermostats'][0]['fan_timer_active'] == true ? 1 : 0),
                'autoAway'     => ($ret['structures'][0]['away'] == 'auto-away' ? 1 : 0),
                'manualAway'   => ($ret['structures'][0]['away'] == 'away' ? 1 : 0),
                'leaf'         => ($ret['devices']['thermostats'][0]['has_leaf'] == true ? 1 : 0),
                'timestamp'    => $ret['devices']['thermostats'][0]['last_connection'],
                'target_temp'  => $ret['devices']['thermostats'][0]['target_temperature_f'],
                'current_temp' => $ret['devices']['thermostats'][0]['ambient_temperature_f'],
                'humidity'     => $ret['devices']['thermostats'][0]['humidity']
               );
  return $data;
}

function c_to_f($c) {
  return ($c * 1.8) + 32;
}

?>