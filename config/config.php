<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['module_config'] = array(
	'name'			=> 'Langs',
	'description'	=> 'A small module for editing language files in Bonfire..',
	'menus'	=> array(
		'developer'	=> 'languages/developer/_menu'
	)
);


//--------------------------------------------------------------------
// !CONFIG ITEMS 
//--------------------------------------------------------------------

// Set your default usual working langs here, for improved performance
$config['langs.list'] = array();