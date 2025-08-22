<?php
define("SERIAL_DEVICE_NOTSET", 0);
define("SERIAL_DEVICE_SET", 1);
define("SERIAL_DEVICE_OPENED", 2);

class PhpSerial {
    private $_device = null;
    private $_windevice = null;
    private $_dHandle = null;
    private $_dState = SERIAL_DEVICE_NOTSET;
    private $_buffer = "";
    private $_os = "";
    public $autoflush = true;

    public function __construct() {
        setlocale(LC_ALL, "en_US");
        $sysname = php_uname();

        if (stripos($sysname, "Windows") === 0) {
            $this->_os = "windows";
            register_shutdown_function([$this, "deviceClose"]);
        }
        elseif (stripos($sysname, "Linux") === 0) {
            $this->_os = "linux";
            register_shutdown_function([$this, "deviceClose"]);
        }
        elseif (stripos($sysname, "Darwin") === 0) {
            $this->_os = "osx";
            register_shutdown_function([$this, "deviceClose"]);
        }
        else {
            trigger_error("Unsupported OS: {$sysname}", E_USER_ERROR);
        }
    }


    public function deviceSet($device) {
        if ($this->_dState === SERIAL_DEVICE_OPENED) {
            trigger_error("Close the device before setting a new one", E_USER_WARNING);
            return false;
        }
        if ($this->_os === "linux" || $this->_os === "osx") {
            if (preg_match("@^COM(\d+)$@i", $device, $m)) {
                $device = "/dev/ttyS" . ($m[1] - 1);
            }
            $cmd = ($this->_os === "linux") ? "stty -F $device" : "stty -f $device";
            if ($this->_exec($cmd) === 0) {
                $this->_device = $device;
                $this->_dState = SERIAL_DEVICE_SET;
                return true;
            }
        }
        elseif ($this->_os === "windows") {
            if (preg_match("@^COM(\d+)$@i", $device, $m) && $this->_exec("mode " . $device) === 0) {
                $this->_windevice = "COM" . $m[1];
                $this->_device = "\\\\.\\COM" . $m[1];
                $this->_dState = SERIAL_DEVICE_SET;
                return true;
            }
        }
        trigger_error("Invalid serial port", E_USER_WARNING);
        return false;
    }

    public function confBaudRate($rate) {
        if ($this->_os === "windows") {
            $this->_exec("mode {$this->_windevice}: baud=$rate parity=N data=8 stop=1");
        } else {
            $this->_exec("stty -F {$this->_device} $rate");
        }
    }

    public function deviceOpen() {
        if ($this->_dState !== SERIAL_DEVICE_SET) {
            trigger_error("Device not set or already opened", E_USER_WARNING);
            return false;
        }
        if ($this->_os === "windows") {
            $this->_dHandle = fopen($this->_device, "w+");
        } else {
            $this->_dHandle = fopen($this->_device, "r+");
        }
        if (!$this->_dHandle) {
            trigger_error("Unable to open serial port", E_USER_WARNING);
            return false;
        }
        $this->_dState = SERIAL_DEVICE_OPENED;
        return true;
    }

    public function sendMessage($message) {
        if ($this->_dState !== SERIAL_DEVICE_OPENED) {
            trigger_error("Device not opened", E_USER_WARNING);
            return false;
        }
        fwrite($this->_dHandle, $message);
        if ($this->autoflush) {
            $this->deviceFlush();
        }
        return true;
    }

    public function readPort() {
        if ($this->_dState !== SERIAL_DEVICE_OPENED) {
            return false;
        }
        $data = "";
        if ($this->_os === "windows") {
            usleep(100000);
            $data = "";
            while (!feof($this->_dHandle)) {
                $data .= fread($this->_dHandle, 128);
            }
        } else {
            $data = fgets($this->_dHandle);
        }
        return $data;
    }

    public function deviceClose() {
        if ($this->_dState === SERIAL_DEVICE_OPENED && $this->_dHandle) {
            fclose($this->_dHandle);
            $this->_dState = SERIAL_DEVICE_NOTSET;
            return true;
        }
        return false;
    }

    private function deviceFlush() {
        if ($this->_dHandle) {
            if ($this->_os === "windows") {
                fflush($this->_dHandle);
            } else {
                $this->_exec("stty -F {$this->_device} hupcl");
            }
        }
    }

    private function _exec($cmd) {
        exec($cmd, $out, $value);
        return $value;
    }
}
?>
