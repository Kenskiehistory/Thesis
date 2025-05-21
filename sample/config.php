<?php

if (session_status() === PHP_SESSION_NONE) {

    session_start();

}



if (!defined('BASE_URL')) {

    define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/sample');

}



// Other configuration settings...

?>

