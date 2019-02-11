<?php
class Invoicesspeditor extends CI_Controller
{
	var $active_menu = "";
	var $active_submenu = "";
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	var $connected		= false;
	function __construct()
	{
		parent::__construct();
		//	$this->auth->check_access('Admin', true);
	
		if($this->config->item('use_speditor4')) {
			$this->load->model('Speditor_model');
		}
		
		if($this->Speditor_model->connected) {
			$this->connected = true;
		}
		
		$admin_data = $this->session->userdata('admin');

		$this->active_menu = 'invoicesspeditor';
		$this->active_submenu = '';
	}

	function index()
	{
		$data['page_title']	= lang('invoices');
		
		$this->importFromSpeditor();
		
		
		
	//	$this->load->model('Speditor_model');
		
	//	$elements_to_import	= $this->Speditor_model->get_one_element_by('INVOICES',$where = 'ID', $where2 = 807);
	//	var_dump($elements_to_import);
		
		
		///$save = array();
	//	$save['ID'] = 82;
	//	$save['MOL'] = mb_convert_encoding('Иван Иванов - 1982', "Windows-1251", "utf-8");
	//	$s	= $this->Speditor_model->query($save);
		
	//	$this->Speditor_model->disconnect();
	//	$elements_to_import	= $this->Speditor_model->get_one_element_by('INVOICES', $where = 'ID', $where2 = 82);
		//$elements_to_import	= $this->Speditor_model->disconnect();
		//$elements_to_import	= $this->Speditor_model->get_elements_by('INVOICES');
	//	echo mb_convert_encoding($elements_to_import->MOL, "utf-8", "Windows-1251");
	//	var_dump($elements_to_import);
		//echo $elements_to_import->ID; echo mb_convert_encoding($elements_to_import->MOL, "utf-8", "Windows-1251");
		
		
	/*	$this->load->model('Microinvest_model');
		$vies_array = $this->Microinvest_model->get_all_elements_by('vieses', false, false);*/
	//	var_dump($vies_array);
	//	exit;
	
	//GET CHARACHTER ENCODING
	//	$elements = $this->Speditor_model->query('SELECT a.RDB$CHARACTER_SET_NAME FROM RDB$DATABASE a');
	//	var_dump($elements);
		
		//echo 'da';
		
		
		$where_array['exported'] = 0;
		$where_array['system_data'] = 1;
		$data['elements']	= $this->Rs_model->get_m_by_n('invoices', $where_array, $order_array = false, $limit_offset_array = false, $group_array = false);
		foreach($data['elements'] as &$element)
		{
			$element->invoice_details = $this->Rs_model->get_elements_by('invoice_details', $where = 'INVOICE_ID', $element->id, $order_by = 'ROWNUM', 'ASC');
		}
		
		$data['is_export'] = false;

		$this->load->view($this->config->item('admin_folder').'/invoices_view', $data);
	}
	
	
	function export()
	{
		$this->load->library('form_validation');
		
		$data['page_title']	= lang('invoices') . ' ' .lang('export');
		
		$this->updateFromSpeditor();
		
		$where_array['exported'] = 0;
		$where_array['system_data'] = 1;
		$data['elements']	= $this->Rs_model->get_m_by_n('invoices', $where_array, $order_array = false, $limit_offset_array = false, $group_array = false);
		foreach($data['elements'] as &$element)
		{
			$element->invoice_details = $this->Rs_model->get_elements_by('invoice_details', $where = 'INVOICE_ID', $element->id, $order_by = 'ROWNUM', 'ASC');
		}
		
		$data['is_export'] = true;
		
		$this->form_validation->set_rules('export_action', '', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view($this->config->item('admin_folder').'/invoices_view', $data);
		}else {
			$invoices_for_export = $this->input->post('invoices_for_export');
			
			$data = $this->xml($invoices_for_export);
			
			foreach($invoices_for_export as $ife)
			{
				$save = array();
				$save['id'] = $ife;
				$save['exported'] = 1;
				$save['exported_on'] = time();
				
				$this->Rs_model->save_where($save, 'invoices', 'id');
			}
			
			$this->load->helper('download_helper');
			force_download_content('export_'.date("d.m.Y", time()).'.xml', $this->load->view('xml_view', $data, true));
			
			//redirect($this->config->item('admin_folder').'/invoicesspeditor/xml');
			//redirect($this->config->item('admin_folder').'/invoicesspeditor/export');
		}
		
	}
	
	
	
	function xml($invoices_for_export = array())
	{
		// Load XML writer library
        $this->load->library('xml_writer');
        
        // Initiate class
        $xml = new Xml_writer();
        $xml->setRootName('TransferData');
		
		$xml->initiate();
		
		// Start branch 'Accountings'
		$xml->startBranch('Accountings');
		foreach($invoices_for_export as $exported_invoice)
		{
			$invoice_arr = $this->Rs_model->get_element_by('invoices', 'id', $exported_invoice);
			$invoice_details_arr = $this->Rs_model->get_elements_by('invoice_details', 'INVOICE_ID', $invoice_arr->id, 'ROWNUM', 'ASC');

			  $xml->startBranch('Accounting', array('AccountingDate' =>$invoice_arr->IDATE, 'Number'=>$invoice_arr->NUMBER, 'Reference'=>$invoice_arr->CLIENT_REF, 'OptionalReference'=>'' , 'Term'=>'', 'Vies'=>'UNK', 'ViesMonth'=>'' ));
				$xml->startBranch('Document', array('Date'=>$invoice_arr->IDATE, 'Number'=>$invoice_arr->NUMBER, 'DocumentType'=>'')); $xml->endBranch();
				$xml->startBranch('Company', array('Name'=>$invoice_arr->COMPANY, 'Bulstat'=>$invoice_arr->BULSTAT, 'VatNumber'=>$invoice_arr->BULSTAT)); $xml->endBranch();
				  $xml->startBranch('AccountingDetails');
				  foreach($invoice_details_arr as $exported_inv_detail)
				  {
					  $detail_price = number_format($exported_inv_detail->PRICE_CURR * $exported_inv_detail->DETAIL_QTY , 2, '.', '');
					  $xml->startBranch('AccountingDetail', array(
						'AccountNumber'=>'UNK', 
						'Direction'=>'Debit', 
						'VatTerm'=>'UNK', 
						'Amount'=>$detail_price, 
						'Description'=>$exported_inv_detail->DETAIL_TEXT)
						); $xml->endBranch();
				  }
				  $xml->endBranch();
				$xml->endBranch();
		}
		
		$xml->endBranch(); // END branch 'Accountings'
        
        // Pass the XML to the view
        $data = array();
        $data['xml'] = $xml->getXml(false);
		
		return $data;
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
	
	
	function show_table_post()
	{
		$data['page_title']	= 'Show table';
		$this->active_submenu = 'show_table_post';
		
		$this->load->library('form_validation');
		
		if(!$this->connected) {
			$data['error'] = lang('no_speditor_connection');
		}
		
		$data['table_name'] = 'INVOICES';
		$data['where_txt'] 	= '';
		$data['where_sign'] = '';
		$data['where_val'] 	= '';
		$data['order_by'] 	= '';
		$data['asc_desc'] 	= 'DESC';
		$data['limit'] 		= 1;
		
		$data['elements_exp']	= array();
		
		$this->form_validation->set_rules('table_name', 'table_name', 'required');
		$this->form_validation->set_rules('where_txt', 'where_txt', '');
		$this->form_validation->set_rules('where_sign', 'where_sign', '');
		$this->form_validation->set_rules('where_val', 'where_val', '');
		$this->form_validation->set_rules('order_by', 'order_by', '');
		$this->form_validation->set_rules('asc_desc', 'asc_desc', '');
		$this->form_validation->set_rules('limit', 'limit', 'required|numeric');
		
		if ($this->form_validation->run() == FALSE) {
			
		}else {
			$table_name = $this->input->post('table_name');
			$where_txt = $this->input->post('where_txt');
			$where_val = $this->input->post('where_val');
			$order_by = $this->input->post('order_by');
			$asc_desc = $this->input->post('asc_desc');
			$limit = $this->input->post('limit');
			$where_sign = $this->input->post('where_sign');
			$where_sign = ' ' . $where_sign;
			
			$data['table_name'] = $table_name;
			$data['where_txt'] 	= $where_txt;
			$data['where_sign'] = $this->input->post('where_sign');
			$data['where_val'] 	= $where_val;
			$data['order_by'] 	= $order_by;
			$data['asc_desc'] 	= $asc_desc;
			$data['limit'] 		= $limit;
		
			
			if($this->connected) {
				$data['elements_exp']	= $this->Speditor_model->get_elements_by($table_name, $where_txt . $where_sign , $where_val, $order_by, $asc_desc, $limit, 0);
				
				$data['elements_exp']	= $this->convert_array($data['elements_exp'], $direction = 2);
			}
		}
		
		$this->load->view($this->config->item('admin_folder').'/table_view', $data);
	}
	
	
	public function importFromSpeditor()
	{
		if($this->config->item('use_speditor4')) {
			// GET LAST NUMBER
			$last_invoice = 800;
			
			try {
				$last_inserted_invoice = $this->Rs_model->get_element_by_order('invoices', $where = 'system_data', $where2 = 1, 'original_id', 'DESC');
			}catch (Exception $e) {
				// this will not catch DB related errors. But it will include them, because this is more general. 
				//log_message('error: ',$e->getMessage());
				return;
			}
			
			if($last_inserted_invoice) {
				$last_invoice = $last_inserted_invoice->original_id;
				unset($last_inserted_invoice);
			}
			
			

			$where_array = array();
			$where_array['ID > '] = (int)$last_invoice;
			$where_array['IS_WAITING <>'] = 1;
			$order_array = array();
			$order_array['ID'] = 'ASC';
			$limit_offset_array[100] = 0;
			$elements_to_import	= $this->Speditor_model->get_elements_limit('INVOICES', $where_array, $order_array, $limit_offset_array);
			
			
			//insert invoices
			$this->insert_invoice_element($elements_to_import);
			
			unset($elements_to_import);
		}
	}
	
	
	
	function delete($id = false, $redirect = true)
	{
		//$this->auth->check_access('Admin', true, $this->config->item('admin_folder').'/invoicesspeditor');
		
		if($id)
			$data['elements'] = $this->Rs_model->get_elements_by('invoices', 'id', $id);
		
		if(count($data['elements']) > 0)
		{
			//$this->log_action->insert_log($type = 1, $id, 'delete');
			$this->Rs_model->delete_where('invoices', 'id', $id);
			$this->session->set_flashdata('message', lang('element_deleted_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', lang('error_element_not_found'));
		}
		
		unset($data);
		
		if($redirect)
			redirect($this->config->item('admin_folder').'/invoicesspeditor');
	}
	
	
	function sync($id = false)
	{
		$ancor = '';
		if($id) {
			//$this->delete($id, false);
			$orig_row = $this->Rs_model->get_element_by('invoices', $where = 'id', $id);
			
			$where_array = array();
			$where_array['ID'] = (int)$orig_row->original_id;
			$order_array = array();
			$order_array['ID'] = 'ASC';
			$limit_offset_array[1] = 0;
			$elements_to_import	= $this->Speditor_model->get_elements_limit('INVOICES', $where_array, $order_array, $limit_offset_array);

			//insert invoice
			$this->insert_invoice_element($elements_to_import, $id);
			
			$ancor = '#tableRow' . $id;
			$this->session->set_flashdata('last_insert_id', $id);
			
			$this->session->set_flashdata('message', lang('element_synced_successfully'));
			
		}
		
		redirect($this->config->item('admin_folder').'/invoicesspeditor' . $ancor);
	}
	
	
	function insert_invoice_element($elements_to_import, $id = null)
	{
		$elements_to_import	= $this->convert_array($elements_to_import, 2);
		
		foreach($elements_to_import as $element)
		{
			$save = array();
			if($id) {
				$save['id'] = $id;
			}
			$save['original_id'] = $element->ID;
			$save['NUMBER'] = $element->NUMBER;
			$save['IDATE'] = $element->IDATE;
			$save['PLACE'] = $element->PLACE;
			$save['INVOICE_CREATOR'] = $element->INVOICE_CREATOR;
			$save['COMPANY'] = $element->COMPANY;
			$save['BULSTAT'] = $element->BULSTAT;
			$save['BANK'] = $element->BANK;
			$save['NV_DETAILS'] = $element->NV_DETAILS;
			$save['CURRENCY'] = $element->CURRENCY;
			$save['CURRENCY_RATIO'] = $element->CURRENCY_RATIO;
			$save['DEAL_NO'] 		= $element->DEAL_NO;
			$save['CLIENT_REF']		= $element->CLIENT_REF;
			$save['TOTAL'] 			= $element->TOTAL;
			$save['DEAL_SUBJECT'] 	= $element->DEAL_SUBJECT;
			$save['TOTAL_VAT'] 		= $element->TOTAL_VAT;
			$save['PAYSUM'] 		= $element->PAYSUM;
			$save['CODE'] 			= $element->CODE;
			$save['ACCOUNT'] 			= $element->ACCOUNT;
			
			$last_inserted_id = $this->Rs_model->save_where($save, 'invoices', 'original_id');
			
			if($last_inserted_id) {
				$details_to_import	= $this->Speditor_model->get_elements_by('INVOICE_DETAILS', $where = 'INVOICE_ID', $save['original_id'], $order_by = 'ROWNUM', 'ASC');
				$this->Rs_model->delete('invoice_details', 'INVOICE_ID', $last_inserted_id);
				
				foreach($details_to_import as $element_details)
				{
					$save_details = array();
					//$save_details['id'] = $element_details->DETAIL_ID;
					$save_details['original_id'] = $element_details->DETAIL_ID;
					$save_details['ROWNUM'] = $element_details->ROWNUM;
					$save_details['DETAIL_TEXT'] = mb_convert_encoding($element_details->DETAIL_TEXT, "utf-8", "Windows-1251");
					$save_details['DETAIL_MEAS'] = mb_convert_encoding($element_details->DETAIL_MEAS, "utf-8", "Windows-1251");
					$save_details['DETAIL_QTY'] = $element_details->DETAIL_QTY;
					$save_details['DETAIL_PRICE'] = $element_details->DETAIL_PRICE;
					//$save_details['INVOICE_ID'] = $element_details->INVOICE_ID;
					$save_details['INVOICE_ID'] = $last_inserted_id;
					$save_details['PRICE_CURR'] = $element_details->PRICE_CURR;
					$save_details['CURRENCY'] = $element_details->CURRENCY;
					$save_details['CURR_RATIO'] = $element_details->CURR_RATIO;
					$save_details['DETAIL_TEXT_EN'] = $element_details->DETAIL_TEXT_EN;
					//$save_details['DETAIL_MEAS_EN'] = $element_details->DETAIL_MEAS_EN;
					//$save_details['DETAIL_TYPE'] = $element_details->DETAIL_TYPE;
					//$save_details['ITEM_CALC_TYPE'] = $element_details->ITEM_CALC_TYPE;
					//$save_details['ITEM_CALC_VALUE'] = $element_details->ITEM_CALC_VALUE;
				
					$last_inserted_detail_id = $this->Rs_model->save_where($save_details, 'invoice_details', 'original_id');
				}
				
				//var_dump($save_details);exit;
			}
		}
	}

	private function convert_array($incoming_array, $direction = 1)
	{
		if($direction == 1) {
			$from  	= "Windows-1251";
			$to  	= "utf-8";
		}
		
		if($direction == 2) {
			$from  	= "utf-8";
			$to  	= "Windows-1251";
		}
		
		if($direction > 0 && $direction < 3) {
			
			foreach($incoming_array as &$iarr)
			{
				foreach($iarr as $key=>$val)
				{
					$iarr->$key = mb_convert_encoding($iarr->$key, $from, $to);
				}
			}

			return $incoming_array;
		}
		
		return $incoming_array;
	}
	
	function updateFromSpeditor()
	{
		$where_array = array();
		$where_array['exported'] = 0;
		$where_array['system_data'] = 1;
		
		$data['elements']	= $this->Rs_model->get_m_by_n('invoices', $where_array, $order_array = false, $limit_offset_array = false, $group_array = false);
		foreach($data['elements'] as $element)
		{
			$where_array = array();
			$where_array['ID'] = $element->original_id;
			$limit_offset_array[1] = 0;
			$elements_to_import	= $this->Speditor_model->get_elements_limit('INVOICES', $where_array, $order_array = false, $limit_offset_array);
			
			//insert invoice
			$this->insert_invoice_element($elements_to_import);
		}
		
		return true;
	}
	
	
}