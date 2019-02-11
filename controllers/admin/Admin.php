<?php
class Admin extends Admin_Controller
{
	var $active_menu = "";
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	var $current_admin	= false;
	function __construct()
	{
		parent::__construct();
		$this->auth->check_access('Admin', true);

		$this->current_admin	= $this->session->userdata('admin');
		
		$admin_data = $this->session->userdata('admin');
		$this->access = $admin_data['access'];
		$this->active_menu = 'admin';
	}

	function index()
	{
		$data['page_title']	= lang('admins');
		$data['admins']		= $this->auth->get_admin_list();

		$this->load->view($this->config->item('admin_folder').'/admins', $data);
	}
	
	function delete($id)
	{
		//even though the link isn't displayed for an admin to delete themselves, if they try, this should stop them.
		if ($this->current_admin['id'] == $id)
		{
			$this->session->set_flashdata('message', lang('error_self_delete'));
			redirect($this->config->item('admin_folder').'/admin');	
		}
		
		//delete the user
		if ($id != 1)
		{
			$this->Rs_model->delete('admin', 'id', $id);
			$this->session->set_flashdata('message', lang('element_deleted_successfully'));
			redirect($this->config->item('admin_folder').'/admin');
		}
	}
	
	function form($id = false)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		//$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$data['page_title']		= lang('admin_form');
		
		//default values are empty if the customer is new
		$data['id']		= '';
		$data['firstname']	= '';
		$data['lastname']	= '';
		$data['email']		= '';
		$data['access']		= '';

		if ($id)
		{
			$this->admin_id	= $id;
			$admin			= $this->auth->get_admin($id);
			//if the administrator does not exist, redirect them to the admin list with an error
			if (!$admin)
			{
				$this->session->set_flashdata('message', lang('error_element_not_found'));
				redirect($this->config->item('admin_folder').'/admin');
			}
			
			//set values to db values
			$data['id']			= $admin->id;
			$data['firstname']	= $admin->firstname;
			$data['lastname']	= $admin->lastname;
			$data['email']		= $admin->email;
			$data['access']		= $admin->access;
		}
		
		$this->form_validation->set_rules('firstname', 'lang:firstname', 'trim|max_length[32]');
		$this->form_validation->set_rules('lastname', 'lang:lastname', 'trim|max_length[32]');
		$this->form_validation->set_rules('email', 'lang:email', 'trim|required|max_length[128]|callback_check_email');
		$this->form_validation->set_rules('access', 'lang:access', 'trim|required');
		
		//if this is not a new account require a password, or if they have entered either a password or a password confirmation
		if ( !$id || $this->input->post('password') != '' || $this->input->post('confirm') != '')
		{
			$this->form_validation->set_rules('password', 'lang:password', 'required|min_length[6]');
			$this->form_validation->set_rules('confirm', 'lang:confirm_password', 'required|matches[password]');
		}
	
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view($this->config->item('admin_folder').'/admin_form', $data);
		}
		else
		{
			$save['id']		= $id;
			$save['firstname']	= $this->input->post('firstname');
			$save['lastname']	= $this->input->post('lastname');
			$save['email']		= $this->input->post('email');
			$save['access']		= $this->input->post('access');
			
			if ($this->input->post('password') != '')
			{
				$this->load->helper('string');
				$save['password']	= sha1($this->input->post('password'));
			}

			$last_inserted_id = $this->Rs_model->save_where($save, 'admin', 'id');
			$this->session->set_flashdata('message', lang('message_saved'));
			
			//go back to the list
			if($this->input->post('submit_save_and_return'))
				redirect($this->config->item('admin_folder').'/admin/form/' . $last_inserted_id);
			else
				redirect($this->config->item('admin_folder').'/admin');
		}
	}
	
	function check_email($str)
	{
		$email = $this->auth->check_email($str, $this->admin_id);
		if ($email)
		{
			$this->form_validation->set_message('check_email', lang('error_email_taken'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	
	
	function user_rights()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$data['page_title']	= lang('user_rights');
		$this->active_menu = 'user_rights';
		
		$user_rights = $this->Rs_model->get_all_elements('user_rights', 'id', 'ASC', false);
		$data['templates'] = $this->Rs_model->get_all_elements('templates', 'id', 'ASC', false);
		
		$data['users'] = $this->Rs_model->get_elements_by('admin', 'access', 'User', 'id', 'ASC', $group_by = false);
		
		
		
		
		$data['user_rights_array'] = array();
		$data['user_rights_array_id'] = array();
		foreach ($user_rights as $user_r)
		{
			$data['user_rights_array_id'][$user_r->controller_id][$user_r->user_id] 	= $user_r->id;
			$data['user_rights_array'][$user_r->controller_id][$user_r->user_id] 		= $user_r->rights;
		}
		
		$this->form_validation->set_rules('hidden_element', '', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view($this->config->item('admin_folder').'/user_rights_form', $data);
		}
		else
		{
			$rights_field	= $this->input->post('rights_field');
			$rights	= $this->input->post('rights');

			if(count($rights_field) > 0)
			foreach($rights_field as $key=>$value)
			{
				$r = explode('-' , $key);

				$save	= array();
				if($r[2] != '')
					$save['id']			= $r[2];
				else
					$save['id']			= false;
				$save['user_id']	= $r[1];
				$save['controller_id']	= $r[0];

				if(isset($rights[$r[0].'-'.$r[1].'-'.$r[2]]))
					$save['rights']		= (int)$rights[$r[0].'-'.$r[1].'-'.$r[2]];
				else
					$save['rights']		= 0;

				//save the user right
				$user_right_id	= $this->Rs_model->save_where($save, $table = 'user_rights', 'id');
			}			
			
			$this->session->set_flashdata('message', lang('message_saved'));
			
			//go back to the user list
			redirect($this->config->item('admin_folder').'/admin/user_rights');
		}
	}
	
	
}