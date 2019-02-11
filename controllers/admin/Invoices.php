<?php
class Invoices extends CI_Controller
{
	var $active_menu = "";
	var $active_submenu = "";
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	function __construct()
	{
		parent::__construct();
		$this->auth->check_access('Admin', true);
		
		$admin_data = $this->session->userdata('admin');

		$this->active_menu = 'invoices';
		$this->active_submenu = '';
	}

	function index()
	{
		$data['page_title']	= lang('invoices');
		
		$data['elements']	= $this->Rs_model->get_all_elements('invoices');
		foreach($data['elements'] as &$element)
		{
			$element->invoice_details = $this->Rs_model->get_elements_by('invoice_details', $where = 'INVOICE_ID', $element->id, $order_by = 'ROWNUM', 'ASC');
		}
		
		$data['is_export'] = false;

		$this->load->view($this->config->item('admin_folder').'/invoices_view', $data);
	}
	
	function exported()
	{
		$this->active_submenu = 'invoices_exported';
		
		$data['page_title']	= lang('invoices') . ' ' .lang('export');
		
		$where_array = array();
		$where_array['exported'] = 1;
		$order_array = array();
		$order_array['exported_on'] = 'DESC';
		$data['elements']	= $this->Rs_model->get_m_by_n('invoices', $where_array, $order_array, $limit_offset_array = false, $group_array = false);
		foreach($data['elements'] as &$element)
		{
			$element->invoice_details = $this->Rs_model->get_elements_by('invoice_details', $where = 'INVOICE_ID', $element->id, $order_by = 'ROWNUM', 'ASC');
		}
		
		$data['is_export'] = true;
		
		$this->load->view($this->config->item('admin_folder').'/invoices_view', $data);
	}
	
}