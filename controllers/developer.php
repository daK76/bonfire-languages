<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends Admin_Controller {

	private $title = 'Edit Language';

	//--------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();
		
		//$this->auth->restrict('Bonfire.Developer.View');
		
		$this->load->helper('languages');
		Template::set('languages', list_languages());
	}

	//---------------------------------------------------------------
	
	public function _remap() 
	{
		$language = $this->uri->segment(4);
		
		/* 
			Set the default language to english if none 
			specified in URL.
		*/
		if (empty($language))
		{
			redirect(current_url() .'/english');
		}
		
		// Take care of edit actions, etc.
		if ($action = $this->uri->segment(5))
		{
			$this->$action($language);
		}
		// Do the index for everything else
		else 
		{
			$this->index($language);
		}
	}
	
	//--------------------------------------------------------------------
	
	public function index($language='english') 
	{ 
		Template::set('toolbar_title', $this->title .': '. ucfirst($language));
	
		Template::set('lang_files', list_lang_files());
	
		Template::set_view('developer/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function edit($language='english') 
	{
		$file = $this->uri->segment(6);
		
		if ($this->input->post('submit'))
		{
			$this->save_file($file, $language);
		}
		
		Template::set('file', $file);
		
		Template::set('english_array', load_lang_file($file, 'english'));
		Template::set('lang_array', load_lang_file($file, $language));
		
		Template::set_view('developer/edit');
		Template::render();
	}
	
	//--------------------------------------------------------------------

	private function save_file($file=null, $language='english') 
	{
		if (empty($file))
		{
			return false;
		}
	
		unset($_POST['submit']);
	
		return save_lang_file($file, $language, $_POST);
	}
	
	//--------------------------------------------------------------------
	
	
}