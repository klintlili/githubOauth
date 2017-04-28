<?php
//回调地址页，用于接收code并发送请求获取access_token
require_once 'vendor/autoload.php';
use API\lib\GO;
session_start();
$go = new GO();
$access_token = $go->github_callback();
echo $access_token;
