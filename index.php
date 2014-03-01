<?php
/////////////////////////////////////////////////////////////////
//INEOSN开源轻博, Copyright (C)   2010 - 2011  www.ineosn.com 
//EMAIL:rovun@foxmail.com     开发者Q群:329354214   
//$Id: index.php 29 2011-10-14 05:54:29Z rovun $


define('APP_PATH',dirname(__FILE__));
define('IN_APP',TRUE);
define('SP_PATH',APP_PATH.'/init');
if(!is_file(APP_PATH.'/config.php')){header('Location:install/');}

if(isset($_REQUEST['ssid'])){session_id($_REQUEST['ssid']);}
require(APP_PATH.'/config.php');
$spConfig["view"]["config"]["template_dir"] = 'tplv2';
require(SP_PATH.'/init.php');
header('Content-type: text/html; charset=utf-8');


spRun();
?>
