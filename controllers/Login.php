<?php

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		//we check if they are logged in, generally this would be done in the constructor, but we want to allow customers to log out still
		//or still be able to either retrieve their password or anything else this controller may be extended to do
		$redirect	= $this->auth->is_logged_in(false, false);
		//if they are logged in, we send them back to the dashboard by default, if they are not logging in
		if ($redirect)
		{
			redirect($redirect);
		}
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		/////// FORM VALIDATION
		$this->form_validation->set_rules('email', 'lang:email', 'trim|required|max_length[256]');
		$this->form_validation->set_rules('password', 'lang:password', 'trim|required|max_length[256]');
		
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['redirect']	= '';
			$this->load->view('login_view', $data);
		}else{
		
		$data['redirect']	= $this->session->flashdata('redirect');
		$submitted 			= $this->input->post('submitted');
		
		if ($submitted == 'submitted')
		{
			$email		= $this->input->post('email');
			$password	= $this->input->post('password');
			$redirect	= $this->input->post('redirect');
			$login		= $this->auth->login_admin($email, $password);
			if ($login)
			{
				$checked_access = $this->auth->check_access('Admin', $this->config->item('admin_folder') . '/dashboard', 'main');
				if ($checked_access === true)
				{
					$redirect = $this->config->item('admin_folder') . '/dashboard';
					redirect($redirect);
				}
			//	redirect($redirect);
			}
			else
			{
				//this adds the redirect back to flash data if they provide an incorrect credentials
				$this->session->set_flashdata('redirect', $redirect);
				$this->session->set_flashdata('error', lang('error_authentication_failed'));
				redirect('login');
			}
		}
		
		}
	//	$this->load->view('login_view', $data);
	}
	
	function logout()
	{
		$this->auth->logout();
		
		//when someone logs out, automatically redirect them to the login page.
		$this->session->set_flashdata('message', lang('message_logged_out'));
		redirect('login');
	}
}