<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Configuración para la librería de envío de correo

$config['mailpath']  = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
$config['protocol']  = "smtp";
//$config['smtp_host'] = "smtp.gmail.com";
$config['smtp_host'] = "0.0.0.0";
$config['smtp_port'] = "25";
$config['mailtype'] = 'html';
$config['charset']  = 'utf-8';
