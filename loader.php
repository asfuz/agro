<?php

function dd($dd){
    echo '<pre>';
    print_r($dd);
}

require_once 'config.php';
require_once 'app/DB.php';
require_once 'app/User.php';