<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| SPA Configuration
|--------------------------------------------------------------------------
|
| Controls single-page application behaviour for this HMVC project.
|
*/

$config['spa_enabled'] = TRUE;

/* Layout view used as the SPA shell (module/view path) */
$config['spa_shell'] = 'spa/shell';

/* DOM element id where page partials are injected */
$config['spa_container'] = 'spa-content';

/* Header name / value that marks an SPA AJAX content request */
$config['spa_ajax_header'] = 'X-SPA-Request';

/* Query-string flag also accepted as an SPA AJAX request */
$config['spa_ajax_param'] = 'spa';

/* Routes handled by the SPA client (module => label) */
$config['spa_nav'] = array(
	'home'    => 'Home',
	'welcome' => 'Welcome',
);
