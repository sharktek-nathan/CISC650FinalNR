<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| EMAIL SENDING SETTINGS
| -------------------------------------------------------------------
*/
# alias to postfix in a typical Postfix server
//$config['protocol'] = 'sendmail'; 
//$config['mailpath'] = '/usr/sbin/sendmail'; 
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.gmail.com';
$config['smtp_port'] = '465';
$config['smtp_user'] = 'support@redfax.com';
$config['smtp_pass'] = 'Sharktek1';
$config['newline'] = "\r\n";
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['wordwrap'] = 'TRUE';
$config['priority'] = '1';

// other email options

/* End of file email.php */
/* Location: .lication/config/email.php */