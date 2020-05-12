<?php 

//start de sessie
ob_flush();
session_start();

//zet de errors aan
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include alle benodigde libraries.
require('constants.class.php');
require('messages.class.php');
require('csrf.class.php');
require('mail.class.php');
require('db.class.php');
require('user.class.php');
require('poule.class.php');

$user = new User();

require('session.class.php');

//check de gehele sessie.
Session::Process();
Session::Check();

//check de csrf.
CSRF::Verify();
CSRF::Generate();