const int kitchenLight = 13; //On-board LED for testing purposes
String incomingSerialMessage = "";

void setup(){
  pinMode(kitchenLight, OUTPUT);
  Serial.begin(9600); //Begins serial communication
}

void loop(){ //Loops indefinitely
  while(Serial.available() > 0){ //As long as there are bytes in the serial buffer, or as long as there is something to be read in the serial buffer
    char currentSerialCharacter = Serial.read();

    if(currentSerialCharacter == '\n'){ //If the current character in the serial message is the end-of-line indicator
      incomingSerialMessage.trim(); //Removes any extra spaces or line breaks from the serial message

      int spaceIndex = incomingSerialMessage.indexOf(" ");

      if(spaceIndex > 0){ //A space is present after index 0
        String device = incomingSerialMessage.substring(0, spaceIndex); //Obtains the name of the device from the serial message
        String command = incomingSerialMessage.substring(spaceIndex + 1); //Obtains the command from the serial message

        serialMessageEvaluator(device, command);
      }
      else{ //Either a space was not present, space index = -1, or the space was the first character in the serial message, space index = 0
        Serial.println("Invalid Serial Message Format");
      }

      incomingSerialMessage = ""; //Clears the string so that it is empty for the next serial message, once we are done with the current serial message
    }
    else{
      incomingSerialMessage += currentSerialCharacter; //The serial message is stored character by character per iteration of this function
    }
  }
}

void serialMessageEvaluator(String device, String command){
  device.toUpperCase();
  command.toUpperCase();

  if(device.equals("KITCHENLIGHT")){
    if(command.equals("ON")){
      digitalWrite(kitchenLight, HIGH); 
      Serial.println("Kitchen Light " + command);
    }
    else if(command.equals("OFF")){
      digitalWrite(kitchenLight, LOW);
      Serial.println("Kitchen Light " + command);
    }
    else{
      Serial.println("Invalid Command Selected: " + command);
    }
  }
  else if(device.equals("PLACEHOLDERDEVICE")) //Repeat this else if block for the number of other devices to be controlled
    if(command.equals("ON")){
      //Turn device on
      //Print device is on
    }
    else if(command.equals("OFF")){
      //Turn device off
      //Print device is off
    }
    else{
      Serial.println("Invalid Command Selected: " + command);
    }
  else{
    Serial.println("Unknown Device Selected: " + device); //The serial message was of valid format, but the device selected is not designated to be controlled
  }
}
