<?php 

ob_flush();
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('constants.class.php');
require('messages.class.php');
require('db.class.php');
require('user.class.php');
require('poule.class.php');

$user = new User();

require('session.class.php');

Session::Process();
Session::Check();