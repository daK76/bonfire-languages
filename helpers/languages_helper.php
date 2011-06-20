<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function list_languages()
{
	$folder = APPPATH .'language/';
	
	$ci =& get_instance();
	
	$ci->benchmark->mark('dir.map_start');	
	// if there's no custom config for menu
	// we'll use dir helper

	
	$ci->load->helper('directory');
	
	$folders = directory_map($folder, 1);

	$ci->benchmark->mark('dir.map_end');
	$ci->benchmark->elapsed_time('dir.map_start', 'dir.map_end');

	return $folders;
}

//--------------------------------------------------------------------

/*
	Finds a list of all language files for a specific language by
	searching the application/languages folder as well as all core_module
	folders for folders matching the language name. 
	
	Returns an array of files.
*/
function list_lang_files($language='english')
{
	$ci =& get_instance();
	$ci->load->helper('file');

	$lang_files = array();

	// Base language files.
	$lang_files = array_merge($lang_files, find_lang_files(APPPATH .'language/'. $language .'/', $language));
	
	// Module lang files
	$modules = module_list();
	$custom_modules = module_list(true);

	foreach ($modules as $module)
	{
		$module_langs = module_files($module, 'language');

		if (isset($module_langs[$module]['language'][$language]))
		{
			$path = implode('/', array($module, 'language', $language));
			
			if (in_array($module, $custom_modules))
			{
				$files = find_lang_files(APPPATH .'../modules/'. $path .'/', $language);
			}
			else
				$files = find_lang_files(APPPATH .'core_modules/'. $path .'/', $language);

			foreach ($files as $file)
			{
				$lang_files[] = $file;
			}
		}
	}
	
	return $lang_files;
}

//--------------------------------------------------------------------

/*
	Searches an individual folder for any language files, 
	and returns an array appropriate for adding to the $lang_files
	array in get_lang_files() function.
*/
function find_lang_files($path=null, $language='english')
{
	if (!is_dir($path) || empty($language))
	{
		return null;
	}
	
	$files = array();
	
	foreach (glob("{$path}*_lang.php") as $filename)
	{
		$files[] = basename($filename);
	}
	
	return $files;
}

//--------------------------------------------------------------------

function load_lang_file($filename=null, $language='english')
{
	if (empty($filename))
	{
		return null;
	}
	
	$array = array();
	
	// Is it the application_lang file? 
	if ($filename == 'application_lang.php')
	{
		$path = APPPATH .'language/'. $language .'/'. $filename;
	}
	// Look in core_modules
	else 
	{
		$module = str_replace('_lang.php', '', $filename);
		
		$path = module_file_path($module, 'language', $language .'/'. $filename);
	}
	
	// Load the actual array
	include($path);
	
	if (isset($lang) && is_array($lang))
	{
		$array = $lang;
		unset($lang);
	}
	
	return $array;
}

//--------------------------------------------------------------------

function save_lang_file($filename=null, $language='english', $settings=null)
{ 
	if (empty($filename) || !is_array($settings))
	{
		return false;
	}
	
	// Is it the application_lang file? 
	if ($filename == 'application_lang.php')
	{
		$path = APPPATH .'language/'. $language .'/'. $filename;
	}
	// Look in core_modules
	else 
	{
		$module = str_replace('_lang.php', '', $filename);
		
		$path = module_file_path($module, 'language', $language .'/'. $filename);
	}

	// Load the file so we can loop through the lines
	if (is_file($path))
	{
		$contents = file_get_contents($path);
		$empty = false;
	} else 
	{
		$contents = '';
		$empty = true;
	}

	// Save the file.
	foreach ($settings as $name => $val)
	{
		// Is the config setting in the file? 
		$start = strpos($contents, '$lang[\''.$name.'\']');
		$end = strpos($contents, ';', $start);
		
		$search = substr($contents, $start, $end-$start+1);
		
		//var_dump($search); die();
		
		if (is_array($val))
		{
			$tval  = 'array(\'';
			$tval .= implode("','", $val);
			$tval .= '\')';
		
			$val = $tval;
			unset($tval);
		} else 
		if (is_numeric($val))
		{
			$val = $val;
		} else
		{
			$val ='"' . htmlspecialchars($val) .'"';
		}
		
		if (!$empty)
		{
			$contents = str_replace($search, '$lang[\''.$name.'\'] = '. $val .';', $contents);
		}
		else 
		{
			$contents .= '$lang[\''.$name.'\'] = '. $val .";\n";
		}
	}
	
	// Make sure the file still has the php opening header in it...
	if (strpos($contents, '<?php') === FALSE)
	{
		$contents = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');' . "\n\n" . $contents;
	}
	
	// Write the changes out...
	if (!function_exists('write_file'))
	{
		$CI = get_instance();
		$CI->load->helper('file');
	}

	$result = write_file($path, $contents);
	
	if ($result === FALSE)
	{
		return false;
	} else {
		return true;
	}
}

//--------------------------------------------------------------------
