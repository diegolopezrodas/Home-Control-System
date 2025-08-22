
<?php

  if(isset($_POST['Submit'])) {
    echo "You pressed the <b>SUBMIT</b> Button<br>";

    //Accessing Data submitted by Submission Form
    if(isset($_POST['KitchenLightStatus'])) {
        $KitchenLightStatus = 'ON';
    }
    else {
        $KitchenLightStatus = 'OFF';
    }

    //Printing Variables to Verify content
    echo "KitchenLightStatus = $KitchenLightStatus";  
  }  
  else {
    echo "You did <b>NOT</b> press a button";
  }

  include('../private/serialCommunicator.php');
  include('../public/index.php');
?>
