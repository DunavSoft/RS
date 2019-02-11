<?php
class Logs extends Admin_Controller
{
	var $active_menu = "";
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->library('form_validation');

		//$admin_data = $this->session->userdata('admin');
		$this->active_menu = 'logs';
		
	}
	
	function index($page = 0)
	{
		$data['page_title']	= lang('logs');
		
		$this->load->library('pagination');
		
		$config['uri_segment'] = 4;
		$config['base_url'] = '/' . $this->config->item('admin_folder').'/logs/index/';
		$config['total_rows'] = $this->Dc_model->count_m_by_n('log', $where_array = false, $group_array = false);
		$config['per_page'] = 100;
		
		$this->pagination->initialize($config);
		
		
		$data['users']	= $this->Dc_model->get_elements_by('admin', 'id >', 0, 'firstname', 'ASC', $group_by = false);
		$data['user_data'] = array();
		foreach($data['users'] as $user)
		{
			$data['user_data'][$user->id] = $user->firstname. ' ' .$user->lastname;
		}
		
		$order_array['id'] = 'DESC';
		$limit_offset_array[$config['per_page']] = $page;
		
		$data['elements']	= $this->Dc_model->get_m_by_n('log', $where_array = false, $order_array, $limit_offset_array, $group_array = false);
		
		$data['types'] = array();
		$data['types'][0] = lang('select');
		$data['types'][1] = lang('company');
		$data['types'][2] = lang('object');
		$data['types'][3] = lang('template');
		$data['types'][4] = lang('document');
		
		$data['actions'] = array();
		$data['actions']['none'] 	= lang('select');
		$data['actions']['edit'] 	= lang('edit');
		$data['actions']['insert']	= lang('insert');
		$data['actions']['delete']	= lang('delete');
		
		$this->load->view($this->config->item('admin_folder').'/logs_view', $data);
	}
	
	

	function filter()
	{
		$type 		=  $this->input->post('type');
		$record_id 	=  $this->input->post('record_id');
		$action 	=  $this->input->post('action');
		$user_id 	=  $this->input->post('user_id');
		$date_to 	=  $this->input->post('date_to');
		$date_from 	=  $this->input->post('date_from');

		$where_array = array();
		if($user_id != 0)
			$where_array['admin.id'] = $user_id;
		
		if($type != 0)
			$where_array['log.type'] = $type;
		
		if($record_id != '')
			$where_array['log.record_id'] = $record_id;
		
		if($action != 'none')
			$where_array['log.action'] = $action;
		
		if($date_from != '')
		{
			$hour_begin = $date_from . ' 00:00:00';
			$where_array['date >='] = strtotime($hour_begin);
		}

		if($date_to != '')
		{
			$hour_begin = $date_to . ' 23:59:59';
			$where_array['date <'] = strtotime($hour_begin);
		}
		

		$order_array['log.id'] = 'DESC';
		$on_array[] = 'id';
		$on_array[] = 'user_id';
		$data['elements'] = $this->Dc_model->get_rows_2_tables_by_n('admin', 'log', $on_array, $where_array , $where_in_array = false, $order_array, $group_array = false);
		

		$data['users']	= $this->Dc_model->get_elements_by('admin', 'id >', 0, 'firstname', 'ASC', $group_by = false);
		$data['user_data'] = array();
		foreach($data['users'] as $user)
		{
			$data['user_data'][$user->id] = $user->firstname. ' ' .$user->lastname;
		}
		
		$data['types'] = array();
		$data['types'][0] = lang('select');
		$data['types'][1] = lang('company');
		$data['types'][2] = lang('object');
		$data['types'][3] = lang('template');
		$data['types'][4] = lang('document');

		$this->load->view($this->config->item('admin_folder').'/log_ajax', $data);
	}
}	