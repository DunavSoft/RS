<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->auth->check_access('Admin', true);
	}
	
	public function index()
	{

		//phpinfo();
		
	//	$data['accountings'] = $this->Rs_model->get_all_elements_pdo('Accountings', 'AccountingID', 'ASC');
		//$where_array = array();
		//$where_array['DocumentID'] = 15;
		
		/*
		$on_array[] = 'ReferenceID';
		$on_array[] = 'ReferenceID';
		
		$data['accountings'] = array();
		$data['accountings'] = $this->Rs_model->get_rows_2_tables_by_n_pdo('Accountings', 'References', $on_array, $where_array = false, $where_in_array = false, $order_array = false, $group_array = false);
		
		$data['speditor_invoices'] = $this->Rs_model->get_all_elements_speditor('CLIENTS', 'ID', 'ASC');
		$data['invoices'] = array();
		$data['invoices'] = $this->Rs_model->get_elements_by_speditor('INVOICES', 'ID', 295, 'ID', 'ASC', $group_by = false);

		$data['inv'] = array();
		$data['inv'] = $this->Rs_model->get_elements_by('INVOICE_ID', 'DETAIL_ID', 295, 'DETAIL_ID', 'ASC', $group_by = false);
		
		*/
		
		redirect('admin/dashboard');
	}
	
	public function smail()
	{
		$this->config->set_item('compress_output', false);
		echo '2';
	}
	
	public function smailsmtp()
	{
		///**************************** EMAIL
		$this->load->library('email');
		
		/*
		$config['protocol']    	= 'smtp';
		$config['smtp_host']    = 'smtp.sendgrid.net';
		$config['smtp_port']    = '587';
		$config['smtp_timeout'] = '60';
		$config['smtp_user']    = 'apikey';
		$config['smtp_pass']    = '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';
		$config['smtp_crypto']    = 'TLS';
		*/
		
		$config['protocol'] = 'sendmail';
		$config['crlf']    		= "\r\n";
		$config['newline']    	= "\r\n";
		$config['charset']    	= "utf-8";
		$config['mailtype'] 	= 'html';
		
		$config['mailtype'] = 'html';
		$this->email->clear(TRUE);
		$this->email->initialize($config);
		$this->email->from("sales@dunavpress.com", "Dunav press");
		$this->email->to('nechovski@abv.bg');

		$this->email->subject('Test sendgrid' );
		$this->email->message('Test sendgrid 222');
		
		$this->email->send();
		
		//mail('nechovski007@abv.bg' , 'subj test' , 'Test');
		
		
		
		$this->load->view('welcome_message');
		
		//	echo '***';
	}
	
}
?>