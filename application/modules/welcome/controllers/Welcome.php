<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->spa_render('welcome_message', array(
			'heading' => 'Welcome',
			'message' => 'Welcome module (HMVC). CodeIgniter ' . CI_VERSION . ' with Modular Extensions.',
		), 'Welcome');
	}
}
