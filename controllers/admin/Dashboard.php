<?php
class Dashboard extends Admin_Controller
{
	var $active_menu = "";
	var $active_submenu = "";
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
		$this->active_menu = 'homepage';
		
		$this->load->model('Speditor_model');
		$this->load->model('Expecta_model');
	}

	function index()
	{
		$data['page_title']	= lang('dashboard');
		
		//phpinfo();
		$data['connection_speditor'] = false;
		$data['connection_expecta'] = false;
		
		if($this->Speditor_model->connected) {
			$data['connection_speditor'] = true;
		}else {
			$this->config->set_item('use_speditor', false);
		}
		
		if($this->Expecta_model->connected) {
			$data['connection_expecta'] = true;
		}else {
			$this->config->set_item('use_expecta', false);
		}

		$this->load->view($this->config->item('admin_folder') . '/dashboard_view', $data);
	}
}