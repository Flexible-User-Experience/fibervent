<?php
if (extension_loaded('gd') && function_exists('gd_info')) {
    echo "PHP GD library is installed on your web server" . PHP_EOL;
    echo "GD version: " . gd_info()['GD Version'] . PHP_EOL;
}
else {
    echo "PHP GD library is NOT installed on your web server" . PHP_EOL;
}
