
<?php
  include "../private/php_serial.class.php";

  $serial = new phpSerial;
  $serial->deviceSet("COM4");
  $serial->confBaudRate(9600);

  $serial->deviceOpen();
  $serial->sendMessage("KITCHENLIGHT $KitchenLightStatus");

  sleep(1);
  $response = $serial->readPort();

  $serial->deviceClose();
?>