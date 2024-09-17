<?php

App::uses('CakeEmail', 'Network/Email');

class sendEmail 
{
	public	function to($tos,$msg)
	{

		$tms = array(
        	'host' => 'smtp.teammas.in',
        	'port' => 25,
        	'username' => 'bhanu.singh@teammas.in',
        	'password' => 'Desk@#22#11',
        	'transport' => 'Smtp',
        	'tls' => false
    		);

		$Email = new CakeEmail();
		$Email-> to($tos);
		$Email -> config($tms);
		$Email-> from(array('ispark@teammas.in' => 'teammas.in'));
		$Email-> emailFormat('html');
		$Email-> subject('Initial Invoice');
		$Email-> send($msg);
		//return $tos;
	}
}















?>