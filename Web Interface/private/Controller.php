<?php
// Controller.php

if (isset($_POST['Submit'])) {
    echo "You pressed the <b>SUBMIT</b> Button<br>";

    // Accessing data submitted by the form
    $KitchenLightStatus = isset($_POST['KitchenLightStatus']) ? 'ON' : 'OFF';
    echo "KitchenLightStatus = $KitchenLightStatus<br>";

    // Prepare device and status
    $device = escapeshellarg('KITCHENLIGHT');
    $status = escapeshellarg($KitchenLightStatus);

    // Full path to Python interpreter and script
    $python = 'C:\\Users\\luism\\AppData\\Local\\Programs\\Python\\Python312\\python.exe';
    $script = realpath('../private/serial_comm.py');

    // Build and execute command
    $cmd = escapeshellcmd("$python $script $device $status");
    $output = shell_exec($cmd);

} 
else {
    echo "You did <b>NOT</b> press a button<br>";
}

// Include the main page
include('../public/index.php');
?>
