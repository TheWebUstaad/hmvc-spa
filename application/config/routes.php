<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING (HMVC + SPA)
| -------------------------------------------------------------------------
*/

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| SPA / module routes
| module/controller/method  — HMVC handles these automatically
*/
$route['home'] = 'home';
$route['welcome'] = 'welcome';
