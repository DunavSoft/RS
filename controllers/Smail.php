<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Smail extends CI_Controller {

	public function index()
	{
		///**************************** EMAIL
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from("grafik@dunavpress.com", "test");
		//$this->email->to("gnechovski@dunavpress.com");
		//$this->email->to("gnechovski@dunavpress.com");
		$this->email->to("nechovski@abv.bg");
		
		$mess = 'test 22';

		$this->email->subject("Нов тест");
		$this->email->message($mess);
		$this->email->send();
		///***********************************************************************
		
		echo $this->email->print_debugger();
	}
	
}