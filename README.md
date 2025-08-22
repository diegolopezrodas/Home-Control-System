# Home-Control-System WIP
This is a collaborative project where a web interface was created to act as a control panel for home devices like lights and fans, and a microcontroller controls the devices physically. Currently a work in progress.

## Modifications to the php-serial library
- Replaced deprecated `trigger_error(E_USER_ERROR)` with an exception for PHP 8.4 compatibility
- Adjusted COM port detection to better support Windows environments
- Added fallback handling for missing `stty` on Windows (since `stty` is a Linux/Unix command not available by default)

## License
This project uses the **PHP-Serial** library by **Xowap**, which is licensed under **GPL-2.0 or later**.

- Original `PHP-Serial` source: https://github.com/Xowap/PHP-Serial  
- This project (including derivative code) is also released under the **GPL-2.0 (or later)** license.

See the included `LICENSE` file for full GPL-2.0 license text.

## Contributors
- **Diego Lopez-Rodas** – Web interface
- **Luis M. Lopez** – Microcontroller logic
