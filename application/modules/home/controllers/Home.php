<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->spa_render('home', array(
			'heading' => 'Home',
			'message' => 'This page is served from the home HMVC module via the SPA shell.',
		), 'Home');
	}
}
