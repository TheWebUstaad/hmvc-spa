<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base HMVC + SPA controller.
 *
 * Module controllers should extend MY_Controller (which extends MX_Controller).
 * Use $this->spa_render('view', $data, 'Page Title') instead of load->view()
 * so full page loads get the SPA shell and AJAX requests get partials only.
 */
class MY_Controller extends MX_Controller
{
	/** @var array */
	protected $spa_data = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->config->load('spa', TRUE);
	}

	/**
	 * Detect SPA AJAX / partial content request.
	 *
	 * @return bool
	 */
	protected function is_spa_request()
	{
		if ( ! $this->config->item('spa_enabled', 'spa'))
		{
			return FALSE;
		}

		$header = $this->config->item('spa_ajax_header', 'spa');
		$param  = $this->config->item('spa_ajax_param', 'spa');

		if ($this->input->get_request_header($header, TRUE) === '1'
			|| $this->input->get_request_header('X-Requested-With', TRUE) === 'XMLHttpRequest')
		{
			return TRUE;
		}

		if ($this->input->get($param) === '1')
		{
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Render a module view as SPA partial or inside the shell layout.
	 *
	 * @param string $view  View name relative to the current module
	 * @param array  $data  View data
	 * @param string $title Page title
	 * @param bool   $return Whether to return HTML instead of outputting
	 * @return string|void
	 */
	protected function spa_render($view, $data = array(), $title = '', $return = FALSE)
	{
		$data = array_merge($this->spa_data, $data);
		$data['page_title'] = $title !== '' ? $title : (isset($data['page_title']) ? $data['page_title'] : 'Sobeat');

		if ($this->is_spa_request())
		{
			$html = $this->load->view($view, $data, TRUE);

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'title'   => $data['page_title'],
					'html'    => $html,
					'module'  => $this->router->fetch_module(),
					'method'  => $this->router->fetch_method(),
				)));

			return;
		}

		$data['spa_content']   = $this->load->view($view, $data, TRUE);
		$data['spa_nav']       = $this->config->item('spa_nav', 'spa');
		$data['spa_container'] = $this->config->item('spa_container', 'spa');
		$data['base_url']      = base_url();
		$data['site_url']      = site_url();

		$shell = $this->config->item('spa_shell', 'spa');

		return $this->load->view($shell, $data, $return);
	}
}
