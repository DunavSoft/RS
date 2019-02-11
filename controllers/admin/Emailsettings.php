<?php
class Emailsettings extends Admin_Controller
{
	var $active_menu = "";

	var $current_admin	= false;
	function __construct()
	{
		parent::__construct();
		$this->auth->check_access('Admin', true);

		$this->current_admin	= $this->session->userdata('admin');
		
		$admin_data = $this->session->userdata('admin');
		$this->access = $admin_data['access'];
		$this->active_menu = 'emailsettings';
		$this->lang->load('email_lang');
	}

	function index()
	{
		$data['page_title']	= lang('email_settings');
		
		$data['elements']	= $this->Rs_model->get_all_elements('emailsettings', $order_by = 'id', $ord_by = 'DESC', $group_by = false);
		
		$this->load->view($this->config->item('admin_folder') . '/emailsettings_view', $data);
	}
	
	function form($id = false, $duplicate = false)
	{
		$data['page_title']	= lang('email_settings');
		
		$this->load->library('form_validation');
		$this->load->helper('text');
		
		//SET DEFAULT VALUES
		$data['id']			= '';
		$data['name']		= '';
		$data['protocol']	= '';
		$data['smtp_host']	= '';
		$data['smtp_port'] 	= '';
		$data['smtp_timeout'] = '';
		$data['smtp_user'] 	= '';
		$data['smtp_pass'] 	= '';
		$data['smtp_crypto'] = '';
		$data['default_company_email'] = '';
		$data['default_company_email_name'] = '';
		$data['default_settings'] = '';
		
		$data['test_test_email'] = '';
		$data['test_subject'] = '';
		$data['test_body'] = '';
		
		$data['logs'] 		= array();
		if ($id)
		{
			$element					= $this->Rs_model->get_element_by('emailsettings', 'id', $id);
			
			//if the element does not exist, redirect them to the list with an error
			if (!$element)
			{
				$this->session->set_flashdata('error', lang('error_not_found'));
				redirect($this->config->item('admin_folder') . '/emailsettings');
			}
			
			//SET DEFAULT VALUES
			$data['id']			= $id;
			$data['name']		= $element->name;
			$data['protocol']	= $element->protocol;
			$data['smtp_host']	= $element->smtp_host;
			if($element->smtp_port > 0)
				$data['smtp_port']	= $element->smtp_port;
			else
				$data['smtp_port']	= '';
			if($element->smtp_timeout > 0)
				$data['smtp_timeout']	= $element->smtp_timeout;
			else
				$data['smtp_timeout']	= '';
			$data['smtp_user']		= $element->smtp_user;
			$data['smtp_pass']		= $element->smtp_pass;
			$data['smtp_crypto']	= $element->smtp_crypto;
			$data['default_company_email']	= $element->default_company_email;
			$data['default_company_email_name']	= $element->default_company_email_name;
			$data['default_settings']	= $element->default_settings;
			
			
			$data['logs'] 		= $this->log_action->get_log(2, $id);
		}
		
		/////// FORM VALIDATION
		$this->form_validation->set_rules('name', 'lang:name', 'trim|required|max_length[128]');
		$this->form_validation->set_rules('protocol', 'lang:protocol', 'trim');
		$this->form_validation->set_rules('smtp_host', 'lang:smtp_host', 'trim');
		$this->form_validation->set_rules('smtp_port', 'lang:smtp_port', 'trim');
		$this->form_validation->set_rules('smtp_timeout', 'lang:smtp_timeout', 'trim');
		$this->form_validation->set_rules('smtp_user', 'lang:smtp_user', 'trim');
		$this->form_validation->set_rules('smtp_pass', 'lang:smtp_pass', 'trim');
		$this->form_validation->set_rules('smtp_crypto', 'lang:smtp_crypto', 'trim');
		$this->form_validation->set_rules('default_company_email', 'lang:default_company_email', 'trim|valid_email');
		$this->form_validation->set_rules('default_company_email_name', 'lang:default_company_email_name', 'trim');
		$this->form_validation->set_rules('default_settings', 'lang:default_settings', 'trim');
		
		if($duplicate)
		{
			$data['id']	= false;
			$data['duplicate']	= 1;
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view($this->config->item('admin_folder').'/emailsettings_form', $data);
		}
		else
		{
			// SAVE ALL OPTIONS
			$save['id']				= $id;
			$save['name']			= $this->input->post('name');
			$save['protocol']		= $this->input->post('protocol');
			$save['smtp_host']		= $this->input->post('smtp_host');
			$save['smtp_port']		= $this->input->post('smtp_port');
			$save['smtp_timeout']	= $this->input->post('smtp_timeout');
			$save['smtp_user']		= $this->input->post('smtp_user');
			$save['smtp_pass']		= $this->input->post('smtp_pass');
			$save['smtp_crypto']	= $this->input->post('smtp_crypto');
			$save['default_company_email']	= $this->input->post('default_company_email');
			$save['default_company_email_name']	= $this->input->post('default_company_email_name');
			$save['default_settings']	= $this->input->post('default_settings');

			// save data 
			$last_insert_id	= $this->Rs_model->save_where($save, 'emailsettings', 'id');
			
			if($save['default_settings'] == 1) {
				
				$all_elements	= $this->Rs_model->get_all_elements('emailsettings', $order_by = 'id', $ord_by = 'DESC', $group_by = false);
				foreach($all_elements as $elem)
				{
					$save_ds = array();
					$save_ds['default_settings'] = 0;
					$save_ds['id'] = $elem->id;
					
					if($last_insert_id != $elem->id)
						$def_sett	= $this->Rs_model->save_where($save_ds, 'emailsettings', 'id');
				}
				
			}
			
			
			$this->session->set_flashdata('message', lang('message_saved'));
			
			if($id)
			{
				$this->log_action->insert_log($type = 2, $last_insert_id, 'edit');
			}else
			{
				$this->log_action->insert_log($type = 2, $last_insert_id, 'insert');
			}

			if($this->input->post('submit_save_and_return'))
				redirect($this->config->item('admin_folder').'/emailsettings/form/'.$last_insert_id);
			else
				redirect($this->config->item('admin_folder').'/emailsettings');
		}
	}
	
	
	function delete($id = false)
	{
		$this->auth->check_access('Admin', true, $this->config->item('admin_folder').'/emailsettings');
		
		if($id)
			$data['elements'] = $this->Rs_model->get_elements_by('emailsettings', 'id', $id);
		
		if(count($data['elements']) > 0)
		{
			$this->log_action->insert_log($type = 2, $id, 'delete');
			$this->Rs_model->delete_where('emailsettings', 'id', $id);
			$this->session->set_flashdata('message', lang('element_deleted_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', lang('error_element_not_found'));
		}
		
		unset($data);
		
		redirect($this->config->item('admin_folder').'/emailsettings');
	}
	
	
	function send($id = false)
	{
		$this->load->library('form_validation');
		$this->load->helper('text');

		if ($id)
		{
			$element					= $this->Rs_model->get_element_by('emailsettings', 'id', $id);
			
			//if the element does not exist, redirect them to the list with an error
			if (!$element)
			{
				$this->session->set_flashdata('error', lang('error_not_found'));
				redirect($this->config->item('admin_folder') . '/emailsettings');
			}
		}
		
		/////// FORM VALIDATION
		$this->form_validation->set_rules('test_test_email', 'lang:test_test_email', 'trim|valid_email');
		$this->form_validation->set_rules('test_subject', 'lang:test_subject', 'trim');
		$this->form_validation->set_rules('test_body', 'lang:test_body', 'trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view($this->config->item('admin_folder').'/emailsettings_form', $data);
		}
		else
		{
			
			$this->load->library('email');
			$config['protocol'] = $element->protocol;
			
			
			if($config['protocol'] == 'smtp') {
				$config['smtp_host']    = $element->smtp_host;
				if($config['smtp_port'] > 0)
					$config['smtp_port']    = $element->smtp_port;
				if($config['smtp_timeout'] > 0)
					$config['smtp_timeout'] = $element->smtp_timeout;
				$config['smtp_user']    = $element->smtp_user;
				$config['smtp_pass']    = $element->smtp_pass;
				$config['smtp_crypto']   = $element->smtp_crypto;
			}
			
			/*
			$config['smtp_host']    = 'smtp.sendgrid.net';
			$config['smtp_port']    = '587';
			$config['smtp_timeout'] = '60';
			$config['smtp_user']    = 'apikey';
			$config['smtp_pass']    = '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';
			$config['smtp_crypto']    = 'TLS';
			*/
			
			
			$config['crlf']    		= "\r\n";
			$config['newline']    	= "\r\n";
			$config['charset']    	= "utf-8";
			$config['mailtype'] 	= 'html';
			
			$this->email->clear(TRUE);
			$this->email->initialize($config);
			$this->email->from($element->default_company_email, $element->default_company_email_name);
			$this->email->to($this->input->post('test_test_email'));

			$this->email->subject($this->input->post('test_subject'));
			$this->email->message($this->input->post('test_body'));
			
			if($this->email->send()) {
				$pr_debug = $this->email->print_debugger(array('headers'));
				
				$this->session->set_flashdata('message',lang('test_success') . $pr_debug);
			}else {
				
				$error = '';
				foreach ($this->email->get_debugger_messages() as $debugger_message)
				  $error .= $debugger_message;
				  
				$this->session->set_flashdata('error', $error);

				// Remove the debugger messages as they're not necessary for the next attempt.
				$this->email->clear_debugger_messages();
			}
			
			redirect($this->config->item('admin_folder').'/emailsettings/form/' . $id);
		}
	}
	
	
}