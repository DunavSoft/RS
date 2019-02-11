<?php
class Accounting_settings extends CI_Controller
{
	var $active_menu = "";
	function __construct()
	{
		parent::__construct();
		//	$this->auth->check_access('Admin', true);
	
		$this->load->model('Speditor_model');

		$this->active_menu = 'accounting_settings';
	}

	function index()
	{
		$data['page_title']	= lang('accounting_settings');
		$this->load->model('Microinvest_model');
		
		$data['systems_array'] = $this->config->item('systems');
		
		$order_array = array();
		$order_array['system_id'] = 'ASC';
		$order_array['vat_no'] = 'ASC';
		$order_array['document_type'] = 'ASC';
		$data['elements']	= $this->Rs_model->get_m_by_n('accounting_settings', $where_array = false, $order_array, $limit_offset_array = false, $group_array = false);
		
		$data['system_id']  = 0;
		
		$select_array = array();
		$vies_array = $this->Microinvest_model->get_all_elements_by('Vieses', 'ViesID', 'ASC');
		foreach ($vies_array as $element) 
		{
			$select_array[$element->ViesID] = $element->Description; /// mb_convert_encoding($element->Description, "utf-8", "Windows-1251");
		}
		$data['vies_array'] = $select_array;
		
		
		$select_array = array();
		$documents_types_array = $this->Microinvest_model->get_all_elements_by('DocumentTypes', 'DocumentTypeID', 'ASC');
		foreach ($documents_types_array as $element) 
		{
			$select_array[$element->DocumentTypeID] = $element->Name; // mb_convert_encoding($element->Name, "utf-8", "Windows-1251");
		}
		$data['documents_types_array'] = $select_array;
			
		$select_array = array();
		$vat_term_array = $this->Microinvest_model->get_all_elements_by('VatTerms', 'VatTermID', 'ASC');
		foreach ($vat_term_array as $element) 
		{
			$select_array[$element->VatTermID] = $element->Description; /// mb_convert_encoding($element->Description, "utf-8", "Windows-1251");
		}
		$data['vat_term_array'] = $select_array;
		
			
		unset($order_array);
		
		$this->load->view($this->config->item('admin_folder').'/accounting_settings_view', $data);
	}
	
	
	public function form($system_id, $id = false, $duplicate = false)
	{
		
		$this->load->model('Microinvest_model');
		
		if (!in_array($system_id, array_keys($this->config->item('systems'))))
		{
			redirect($this->config->item('admin_folder') . '/accounting_settings');
		}
		
		$this->load->library('form_validation');
		//$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$data['page_title'] = lang('form') . ' '. lang('accounting_settings');
		
		//SET DEFAULT VALUES
		$data['id']				= '';
		$data['system_id']		= $system_id;
		$data['company_name']	= '';
		$data['vat_no']			= '';
		$data['document_type']	= '';
		$data['conditions']		= '';
		$data['company_name']	= '';
		$data['vat_term']		= '';
		$data['debit']			= '';
		$data['credit']			= '';
		$data['vies_id']		= '';
		
		$data['systems_array'] = $this->config->item('systems');
		$data['vies_array'] = $this->Microinvest_model->get_all_elements_by('Vieses', 'ViesID', 'ASC');

		$data['documents_types_array'] = $this->Microinvest_model->get_all_elements_by('DocumentTypes', 'DocumentTypeID', 'ASC');
		$data['vat_term_array'] = $this->Microinvest_model->get_all_elements_by('VatTerms', 'VatTermID', 'ASC');
		
		
		
		
		if($id)
		{
			$element = $this->Rs_model->get_element_by('accounting_settings', 'id', $id);
			
			if($element) {
				$data['id']				= $element->id;
				$data['system_id']		= $element->system_id;
				$data['company_name']	= $element->company_name;
				$data['vat_no']			= $element->vat_no;
				$data['document_type']	= $element->document_type;
				$data['conditions']		= $element->conditions;
				$data['company_name']	= $element->company_name;
				$data['vat_term']		= $element->vat_term;
				$data['debit']			= $element->debit;
				$data['credit']			= $element->credit;
				$data['vies_id']		= $element->vies_id;
			}
		}
		
		if($duplicate)
		{
			$data['id']	= false;
			$data['duplicate']	= 1;
		}
		
		
		//$data['groups_array'] 	= $this->Dc_model->get_all_elements('groups', false, false, 'sequence', 'ASC', false);
		
		//$data['objects_array'] 	= $this->Dc_model->get_all_elements('objects', false, false, 'name', 'ASC', false);
		//$data['objects_array'] 	= $this->Dc_model->get_all_elements('objects', false, false, 'name', 'ASC', false);
		
		/////// FORM VALIDATION
		$this->form_validation->set_rules('vat_no', 'lang:vat_no', 'trim|required|max_length[32]');
		
		
		if ($this->form_validation->run() == FALSE) {

			$this->load->view($this->config->item('admin_folder').'/accounting_settings_form', $data);
		
		} else {
			
			// SAVE ALL OPTIONS
			$save['id']			= $id;
			$save['system_id']		= $this->input->post('system_id');
			$save['vat_no']			= $this->input->post('vat_no');
			$save['document_type']	= $this->input->post('document_type');
			$save['conditions']		= $this->input->post('conditions');
			$save['company_name']	= $this->input->post('company_name');
			$save['vat_term']		= $this->input->post('vat_term');
			$save['debit']			= $this->input->post('debit');
			$save['credit']			= $this->input->post('credit');
			$save['vies_id']		= $this->input->post('vies_id');
			
			
			// save data 
			$last_insert_id	= $this->Rs_model->save_where($save, 'accounting_settings', 'id');
			$this->session->set_flashdata('last_insert_id', $last_insert_id);

			$this->session->set_flashdata('message', lang('message_saved'));

			if($this->input->post('submit_save_and_return') == '')
				redirect($this->config->item('admin_folder') . '/accounting_settings/');
			else
				redirect($this->config->item('admin_folder') . '/accounting_settings/form/' . $system_id . '/' . $last_insert_id);
		}
	}
	
	
	public function importFromSpeditor()
	{
		
		// GET LAST NUMBER
		$last_invoice = 600;
		
		try {
			$last_inserted_invoice = $this->Rs_model->get_element_by_order('invoices', $where = false, $where2 = false, 'id', 'DESC');
		}catch (Exception $e) {
			// this will not catch DB related errors. But it will include them, because this is more general. 
			//log_message('error: ',$e->getMessage());
			return;
		}
		
		if($last_inserted_invoice) {
			$last_invoice = $last_inserted_invoice->id;
			unset($last_inserted_invoice);
		}

		$where_array = array();
		$where_array['ID > '] = (int)$last_invoice;
		$order_array = array();
		$order_array['ID'] = 'ASC';
		$limit_offset_array[5] = 0;
		$elements_to_import	= $this->Speditor_model->get_elements_limit('INVOICES', $where_array, $order_array, $limit_offset_array);
		
		//var_dump($elements_to_import);
		
		foreach($elements_to_import as $element)
		{
			$save = array();
			$save['id'] = $element->ID;
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
			$save['DEAL_NO'] = $element->DEAL_NO;
			$save['CLIENT_REF'] = $element->CLIENT_REF;
			$save['TOTAL'] = $element->TOTAL;
			$save['DEAL_SUBJECT'] = $element->DEAL_SUBJECT;
			
			$last_inserted_id = $this->Rs_model->save_only($save, 'invoices');
			
			if($last_inserted_id) {
				$details_to_import	= $this->Speditor_model->get_elements_by('INVOICE_DETAILS', $where = 'INVOICE_ID', $last_inserted_id, $order_by = 'ROWNUM', 'ASC');
				
				foreach($details_to_import as $element_details)
				{
					$save_details = array();
					$save_details['id'] = $element_details->DETAIL_ID;
					$save_details['ROWNUM'] = $element_details->ROWNUM;
					$save_details['DETAIL_TEXT'] = $element_details->DETAIL_TEXT;
					$save_details['DETAIL_MEAS'] = $element_details->DETAIL_MEAS;
					$save_details['DETAIL_QTY'] = $element_details->DETAIL_QTY;
					$save_details['DETAIL_PRICE'] = $element_details->DETAIL_PRICE;
					$save_details['INVOICE_ID'] = $element_details->INVOICE_ID;
					$save_details['PRICE_CURR'] = $element_details->PRICE_CURR;
					$save_details['CURRENCY'] = $element_details->CURRENCY;
					$save_details['CURR_RATIO'] = $element_details->CURR_RATIO;
					$save_details['DETAIL_TEXT_EN'] = $element_details->DETAIL_TEXT_EN;
					//$save_details['DETAIL_MEAS_EN'] = $element_details->DETAIL_MEAS_EN;
					//$save_details['DETAIL_TYPE'] = $element_details->DETAIL_TYPE;
					//$save_details['ITEM_CALC_TYPE'] = $element_details->ITEM_CALC_TYPE;
					//$save_details['ITEM_CALC_VALUE'] = $element_details->ITEM_CALC_VALUE;
				
					$last_inserted_id = $this->Rs_model->save_only($save_details, 'invoice_details');
				}
				
				//var_dump($details_to_import);exit;
			}
		}
		
		unset($elements_to_import);
	}
	
	
	
	function delete($id = false)
	{
		//$this->auth->check_access('Admin', true, $this->config->item('admin_folder').'/accounting_settings');
		
		if($id)
			$data['elements'] = $this->Rs_model->get_elements_by('accounting_settings', 'id', $id);
		
		if(count($data['elements']) > 0)
		{
			//$this->log_action->insert_log($type = 1, $id, 'delete');
			$this->Rs_model->delete_where('accounting_settings', 'id', $id);
			$this->session->set_flashdata('message', lang('element_deleted_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', lang('error_element_not_found'));
		}
		
		unset($data);
		
		redirect($this->config->item('admin_folder').'/accounting_settings');
	}

	
	
}