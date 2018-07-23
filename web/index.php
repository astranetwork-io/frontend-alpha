<?php
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');
$isLocal = $_SERVER['REMOTE_ADDR'] == '127.0.0.1';

if($isLocal)
{
    require 'app_dev.php';
}
else
{
    require 'app.php';
}