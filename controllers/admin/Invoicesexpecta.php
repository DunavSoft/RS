<?php
class Invoicesexpecta extends CI_Controller
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
	
		if($this->config->item('use_expecta')) {
			$this->load->model('Expecta_model');
		}
		
		$this->load->model('Expecta_model');
		
		if($this->Expecta_model->connected) {
			$this->connected = true;
		}
		
		$admin_data = $this->session->userdata('admin');

		$this->active_menu = 'invoicesexpecta';
	}

	function index()
	{
		$data['page_title']	= lang('invoices');
		$this->active_submenu = 'invoicesexpecta';
		
		if(!$this->connected) {
			$data['error'] = lang('no_expecta_connection');
		}
		
		if($this->connected) {
			$this->importFromExpecta();
		}
		
		$where_array['exported'] = 0;
		$where_array['system_data'] = 2;
		$data['elements']	= $this->Rs_model->get_m_by_n('invoices', $where_array, $order_array = false, $limit_offset_array = false, $group_array = false);
		foreach($data['elements'] as &$element)
		{
			$element->invoice_details = $this->Rs_model->get_elements_by('invoice_details', $where = 'INVOICE_ID', $element->id, $order_by = 'ROWNUM', 'ASC');
		}
		
		$data['is_export'] = false;

		$this->load->view($this->config->item('admin_folder').'/invoices_view', $data);		
		
	//$nr_elements_to_import	= $this->Expecta_model->count_m_by_n('D_PROTOCOL');
	//var_dump($data['elements_exp']);

	///$save = array();
	//	$save['ID'] = 82;
	//	$save['MOL'] = mb_convert_encoding('Иван Иванов - 1982', "Windows-1251", "utf-8");
	//	$s	= $this->Expecta_model->query($save);
		
	//	$this->Expecta_model->disconnect();
	//	$elements_to_import	= $this->Expecta_model->get_one_element_by('INVOICES', $where = 'ID', $where2 = 82);
		//$elements_to_import	= $this->Expecta_model->disconnect();
		//$elements_to_import	= $this->Expecta_model->get_elements_by('INVOICES');
	//	echo mb_convert_encoding($elements_to_import->MOL, "utf-8", "Windows-1251");
	//	var_dump($elements_to_import);
		//echo $elements_to_import->ID; echo mb_convert_encoding($elements_to_import->MOL, "utf-8", "Windows-1251");
		
		
	/*	$this->load->model('Microinvest_model');
		$vies_array = $this->Microinvest_model->get_all_elements_by('vieses', false, false);*/
	//	var_dump($vies_array);
	//	exit;
	
	
		
	//	$elements			= $this->convert_array($elements, 1);
		
	//	$elements = $this->Expecta_model->query('SELECT a.RDB$CHARACTER_SET_NAME FROM RDB$DATABASE a');
	//	$elements = $this->Expecta_model->query("ALTER RDB$DATABASE SET RDB$CHARACTER_SET_NAME = 'UTF8' SET DEFAULT COLLATION UNICODE_CI_AI;");
		
	//	$elements			= $this->Expecta_model->get_elements_by('N_COMPANY');
		
	//	$elements = $this->Expecta_model->get_one_element_by('D_PROTOCOL', false, false, 'ID', 'DESC');
	//	$elements = $this->Expecta_model->get_one_element_by('D_PROTOCOL', 'ID <', 'SYS0001D01282120469925', 'ID', 'DESC');
		
	
//		$elements = $this->Expecta_model->get_one_element_by('N_DOCUMENTTYPE', false, false, 'ID', 'DESC');
/*		
		$elements = $this->Expecta_model->get_elements_by('D_PROTOCOL', false, false, 'ID', 'ASC', 250);
		$elements			= $this->convert_array($elements, 2);
		foreach($elements as $element)
		{
			echo $element['ID'] . '- ' . $element['ABSOLUTENO'] . '- ' . $element['DOCUMENTNO'] . '<br/>';	
		}
*/
/*	
		$elements = $this->Expecta_model->get_elements_by('D_PROTOCOLDETAIL', 'ID', 'SYS0001D02292300075680', 'ID', 'DESC', 2);
		$elements			= $this->convert_array($elements, 2);
		var_dump($elements);exit;
*/
/*
		$elements = $this->Expecta_model->get_elements_by('N_VATRANG', false, false, 'ID', 'DESC', 5);
		$elements			= $this->convert_array($elements, 2);
		foreach($elements as $el)
		{
			echo $el->NAME;
		}
		exit;
*/

	}
	
	
	function show_tables()
	{
		$data['page_title']	= 'Show tables';
		
		//TO DO: LIST WITH ALL TABLES

		$this->load->view($this->config->item('admin_folder').'/table_view', $data);
	}
	
	function show_table($table_name = 'D_PROTOCOL')
	{
		$data['page_title']	= 'Show table: ' . $table_name;
		
		
		if(!$this->connected) {
			$data['error'] = lang('no_expecta_connection');
		}
		
		if($this->connected) {
			$data['elements_exp']	= $this->Expecta_model->get_elements_by($table_name, $where = 'ABSOLUTENO LIKE ', $where2 = '00000901%', 'DOCUMENTNO', 'DESC', 30, 0);
		}

		$this->load->view($this->config->item('admin_folder').'/table_view', $data);
	}
	
	
	function show_table_post()
	{
		$data['page_title']	= 'Show table';
		$this->active_submenu = 'show_table_post';
		
		$this->load->library('form_validation');
		
		if(!$this->connected) {
			$data['error'] = lang('no_expecta_connection');
		}
		
		$data['table_name'] = 'D_PROTOCOL';
		$data['where_txt'] 	= '';
		$data['where_sign'] = '';
		$data['where_val'] 	= '';
		$data['order_by'] 	= '';
		$data['asc_desc'] 	= 'DESC';
		$data['limit'] 		= 1;
		
		$data['elements_exp']	= array();
		
		$this->form_validation->set_rules('table_name', 'table_name', 'required');
		$this->form_validation->set_rules('where_txt', 'where_txt', 'required');
		$this->form_validation->set_rules('where_sign', 'where_sign', 'required');
		$this->form_validation->set_rules('where_val', 'where_val', 'required');
		$this->form_validation->set_rules('order_by', 'order_by', 'required');
		$this->form_validation->set_rules('asc_desc', 'asc_desc', 'required');
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
				$data['elements_exp']	= $this->Expecta_model->get_elements_by($table_name, $where_txt . $where_sign , $where_val, $order_by, $asc_desc, $limit, 0);
			}
		}
		
		$this->load->view($this->config->item('admin_folder').'/table_view', $data);
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
	
	
	private function convert_string($incoming_string, $direction = 1)
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
			$incoming_string = mb_convert_encoding($incoming_string, $from, $to);
			return $incoming_string;
		}
		
		return $incoming_string;
	}
	
	
	function export()
	{
		$this->active_submenu = 'export_expecta';
		$this->load->library('form_validation');
		
		$data['page_title']	= lang('invoices') . ' ' .lang('export');
		
		$this->updateFromExpecta();
		
		$where_array['exported'] = 0;
		$where_array['system_data'] = 2;
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
			
			//redirect($this->config->item('admin_folder').'/invoicesexpecta/xml');
			//redirect($this->config->item('admin_folder').'/invoicesexpecta/export');
		}
	}
	
	
	
	function xml()
	{
		// Load XML writer library
        $this->load->library('xml_writer');
        
        // Initiate class
        $xml = new Xml_writer();
        $xml->setRootName('TransferData');
		
		$xml->initiate();
		// Start branch 'Accountings'
        $xml->startBranch('Accountings');
		  $xml->startBranch('Accounting', array('AccountingDate' => 'usa', 'Number'=>'', 'Reference'=>'', 'OptionalReference'=>'' , 'Term'=>'', 'Vies'=>'', 'ViesMonth'=>'' ));
            $xml->startBranch('Document', array('Date'=>'', 'Number'=>'', 'DocumentType'=>'')); $xml->endBranch();
		    $xml->startBranch('Company', array('Name'=>'', 'Bulstat'=>'', 'VatNumber'=>'')); $xml->endBranch();
			  $xml->startBranch('AccountingDetails'); 
				$xml->startBranch('AccountingDetail', array('AccountNumber'=>'', 'Direction'=>'Debit', 'VatTerm'=>'', 'Amount'=>'')); $xml->endBranch();
				$xml->startBranch('AccountingDetail', array('AccountNumber'=>'', 'Direction'=>'Debit', 'VatTerm'=>'', 'Amount'=>'')); $xml->endBranch();
				$xml->startBranch('AccountingDetail', array('AccountNumber'=>'', 'Direction'=>'Debit', 'VatTerm'=>'', 'Amount'=>'')); $xml->endBranch();
			  $xml->endBranch();
			$xml->endBranch();
        $xml->endBranch(); // END branch 'Accountings'
        
        // Pass the XML to the view
        $data = array();
        $data['xml'] = $xml->getXml(FALSE);
    //  $this->load->view('xml_view', $data);
		
		return $data;
	}
	
	
	function formNOT_USED($id = false)
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
	
	
	
	public function importFromExpecta()
	{
		if($this->config->item('use_expecta')) {
			// GET LAST NUMBER
			$last_invoice_date = '2019-02-01';
			
			try {
				$last_inserted_invoice = $this->Rs_model->get_element_by_order('invoices', $where = 'system_data', $where2 = 2, 'IDATE', 'DESC');
			}catch (Exception $e) {
				// this will not catch DB related errors. But it will include them, because this is more general. 
				
				//return;
			}
			
			if($last_inserted_invoice) {
				$last_invoice_date = $last_inserted_invoice->IDATE;
				unset($last_inserted_invoice);
			}

			//$last_invoice = $this->next_absoluteno($last_invoice, 1);
			
			$where_array = array();
			//$where_array['ABSOLUTENO LIKE '] = $last_invoice . '%';
			//$where_array['ABSOLUTENO >'] = (int)$last_invoice;
			$where_array['DOCUMENTDATE >= '] = $last_invoice_date;
			$where_array['DOCUMENTNO <>'] = '';
			$where_array['ISINVOICE'] = 1;
			$order_array = array();
			$order_array['ABSOLUTENO'] = 'ASC';
			$limit_offset_array[25] = 0;
			$elements_to_import	= $this->Expecta_model->get_elements_limit('D_PROTOCOL', $where_array, $order_array, $limit_offset_array);
			
			//insert invoices
			$inserted_rows = $this->insert_invoice_element($elements_to_import);
			
			if($inserted_rows == 0) {
				$last_invoice = $this->next_absoluteno($last_invoice, 10);
				$where_array['ABSOLUTENO LIKE '] = $last_invoice . '%';
				$elements_to_import	= $this->Expecta_model->get_elements_limit('D_PROTOCOL', $where_array, $order_array, $limit_offset_array);
				
				//insert invoices
				$inserted_rows = $this->insert_invoice_element($elements_to_import);	
			}
			
			unset($elements_to_import);
		}
	}
	
	private function next_absoluteno($last_invoice = false, $increment = 1)
	{
		if(!$last_invoice) {
			$last_invoice = '0000090109';
		}
		
		$next = $last_invoice;
		
		$li_len = strlen($last_invoice);
		$li_int = (int)$last_invoice + $increment;
		
		$next = substr('0000000000' . $li_int, -10, 9);
		
		return $next;
	}
	
	
	
	function delete($id = false, $redirect = true)
	{
		//$this->auth->check_access('Admin', true, $this->config->item('admin_folder').'/invoicesexpecta');
		
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
			redirect($this->config->item('admin_folder').'/invoicesexpecta');
	}
	
	
	function sync($id = false)
	{
		$ancor = '';
		if($id) {
			
			$orig_row = $this->Rs_model->get_element_by('invoices', $where = 'id', $where2 = $id);
			
			//$this->delete($id, false);
			
			$where_array = array();
			$where_array['ABSOLUTENO'] = $orig_row->original_id;
			$order_array = array();
			$order_array['ID'] = 'ASC';
			$limit_offset_array[1] = 0;
			$elements_to_import	= $this->Expecta_model->get_elements_limit('D_PROTOCOL', $where_array, $order_array, $limit_offset_array);
			
			//insert invoice
			$this->insert_invoice_element($elements_to_import, $id);
			
			$ancor = '#tableRow' . $id;
			$this->session->set_flashdata('last_insert_id', $id);
			
			$this->session->set_flashdata('message', lang('element_synced_successfully'));
		}
		
		redirect($this->config->item('admin_folder').'/invoicesexpecta' . $ancor);
	}
	
	
	function insert_invoice_element($elements_to_import, $id = null)
	{
		// number of new inserted rows
		$inserted_rows = 0;
		
		$currency_array = array();
		$ca = $this->Expecta_model->get_elements_by('N_CURRENCY', false, false, 'ID', 'DESC', 6);
		//$ca			= $this->convert_array($ca, 2);
		foreach($ca as $element)
		{
			$currency_array[$element->ID] = $element->ABBR;
		}
		
		foreach($elements_to_import as $element)
		{
			//CNTRCLIENT_ID
			//N_CONTRAGENT
			$contragents = $this->Expecta_model->get_one_element_by('N_CONTRAGENT', 'ID =', $element->CNTRCLIENT_ID, 'ID', 'DESC');
		//	$contragents			= $this->convert_array($contragents, 2);
			foreach($contragents as $ctrg)
			{
				$COMPANY = $ctrg->NAME;
				$TAXNO = $ctrg->TAXNO;
			}
			//var_dump($element);
			
			$save = array();
			if($id) {
				$save['id'] 		= $id;
			}
			$save['original_id'] = $element->ABSOLUTENO;
			$save['NUMBER'] 	= $element->DOCUMENTNO;
			$save['IDATE'] 		= substr($element->DOCUMENTDATE, 0, 10);
//				$save['PLACE'] = $element->PLACE;
//				$save['INVOICE_CREATOR'] = $element->INVOICE_CREATOR;
			$save['COMPANY'] = $COMPANY; // N_CONTRAGENT.NAME
			$save['BULSTAT'] = $TAXNO; //N_CONTRAGENT.TAXNO
//				$save['BANK'] = $element->BANK;
			$save['NV_DETAILS'] = $this->convert_string($element->SRCDOCSDESCRIPTION, 2);
			$save['NV_DETAILS'] = $element->SRCDOCSDESCRIPTION;
			$currency = $save['CURRENCY'] = $currency_array[$element->SCNCURRENCY_ID];
			$save['CURRENCY_RATIO'] = $element->CURRENCYRATE;
//				$save['DEAL_NO'] 		= $element->DEAL_NO;
//				$save['CLIENT_REF']		= $element->CLIENT_REF;
			$save['TOTAL'] 			= $element->TOTALVALUE;
//				$save['DEAL_SUBJECT'] 	= $element->DEAL_SUBJECT;
			$save['TOTAL_VAT'] 		= $element->VAT_VALUE;
			$save['PAYSUM'] 		= $element->TOTALVALUE;
//				$save['CODE'] 			= $element->CODE; //BIC CODE
//				$save['ACCOUNT'] 		= $element->ACCOUNT; //IBAN
			$save['system_data'] 		= 2;
			
			// check if the element exists
			$get_id_by_original_id = $this->Rs_model->select_element_by('invoices', 'original_id', $save['original_id'], 'id');
			if($get_id_by_original_id) {
				$inserted_rows++;
			}
			
			//$last_inserted_id = $this->Rs_model->save_only($save, 'invoices');
			$last_inserted_id = $this->Rs_model->save_where($save, 'invoices', 'original_id');
			
			if($last_inserted_id) {
				$details_to_import	= $this->Expecta_model->get_elements_by('D_PROTOCOLDETAIL', $where = 'MASTER_ID', $element->ID, 'POSNO', 'ASC');
				
				$this->Rs_model->delete('invoice_details', 'INVOICE_ID', $last_inserted_id);

				foreach($details_to_import as $element_details)
				{
					//get detail
					$article_details	= $this->Expecta_model->get_one_element_by('N_ARTICLE', 'ID = ', $element_details->ARTICLE_ID, $order = false, $order_by = false);
					
					//var_dump($element_details);exit;
					
					$save_details = array();
					//$save_details['id'] 			= null;
					$save_details['original_id'] 	= $element_details->ID;
					$save_details['ROWNUM'] 		= $element_details->POSNO;
					//$save_details['DETAIL_TEXT'] 	=  $this->convert_string($article_details[0]->NAME, 2) . $this->convert_string($element_details->STYLEDTEXT, 2);
					$save_details['DETAIL_TEXT'] 	=  $article_details[0]->NAME . $element_details->STYLEDTEXT;
					$save_details['DETAIL_MEAS'] 	= 'бр.';
					$save_details['DETAIL_QTY'] 	= $element_details->QUANTITY;
					$save_details['DETAIL_PRICE'] 	= $element_details->ARTCRN_VALUE;
					$save_details['INVOICE_ID']		= $last_inserted_id;
					$save_details['PRICE_CURR'] 	= $element_details->ARTCRN_VALUE;
					$save_details['CURRENCY'] 		= $currency;
					$save_details['CURR_RATIO'] 	= $element_details->ARTCURRENCYRATE;
					$save_details['DETAIL_TEXT_EN'] = $article_details[0]->NAME_LATIN;
					//$save_details['DETAIL_MEAS_EN'] = $element_details->DETAIL_MEAS_EN;
					//$save_details['DETAIL_TYPE'] = $element_details->DETAIL_TYPE;
					//$save_details['ITEM_CALC_TYPE'] = $element_details->ITEM_CALC_TYPE;
					//$save_details['ITEM_CALC_VALUE'] = $element_details->ITEM_CALC_VALUE;
				
					$last_inserted_detail = $this->Rs_model->save_where($save_details, 'invoice_details', 'original_id');
				}
			}
		}
		
		return $inserted_rows;
	}
	
	
	public function check_database()
	{
		//  Load the database config file.
		if(file_exists($file_path = APPPATH.'config/database.php'))
		{
			include($file_path);
		}
		
		$config = $db['expectaserver'];
		
		//  Check database connection if using mysqli driver
		if( $config['dbdriver'] === 'ibase' )
		{
			try {
				$ibase = ibase_connect($config['hostname'] . ':' . $config['database'], $config['username'], $config['password'], $config['char_set']);
				if( !$ibase ) {
					throw new Exception();
				} else {
					ibase_close($ibase);
					return true;
				}
			} catch(Exception $e) {
				
			}
			
			
			//ibase_close($ibase);
		}
		
		return false;
	}
	
	
	function updateFromExpecta()
	{
		$where_array = array();
		$where_array['exported'] = 0;
		$where_array['system_data'] = 2;
		
		$data['elements']	= $this->Rs_model->get_m_by_n('invoices', $where_array, $order_array = false, $limit_offset_array = false, $group_array = false);
		foreach($data['elements'] as $element)
		{
			$where_array = array();
			$where_array['ABSOLUTENO'] = $element->original_id;
			$limit_offset_array[1] = 0;
			$elements_to_import	= $this->Expecta_model->get_elements_limit('D_PROTOCOL', $where_array, $order_array = false, $limit_offset_array);
			
			//insert invoice
			$this->insert_invoice_element($elements_to_import, $element->id);
		}
		
		return true;
	}

	
	
}