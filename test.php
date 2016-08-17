<?php

require 'inc/config.php';
require 'collect.php';


$data = get_nest_data();


echo "\n<br>\n<br>";
stuff_we_care_about($data);

function stuff_we_care_about($data) {
  echo "Heating             : ";
  printf("%s\n<br>", $data['heating']);
  echo "Timestamp           : ";
  printf("%s\n<br>", $data['timestamp']);
  echo "Target temperature  : ";
  printf("%.02f\n<br>", $data['target_temp']);
  echo "Current temperature : ";
  printf("%.02f\n<br>", $data['current_temp']);
  echo "Current humidity    : ";
  printf("%d\n<br>", $data['humidity']);

}
