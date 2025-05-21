<?php
// common_functions.php

function is_admin() {
    return isset($_SESSION['id']) && isset($_SESSION['roles']) && $_SESSION['roles'] === 'Admin';
}
?>