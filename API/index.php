<?php
//导入composer自动加载器
require_once 'vendor/autoload.php';
use API\lib\GO;
session_start();
$go = new GO();
$go->github_login();
