<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_action
{
	var $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('session');
		$this->CI->load->model('Rs_model');
		$this->administrator = $this->CI->session->userdata('admin');
	}
	
	function insert_log($type, $record_id, $action)
	{
		$save = array();
		
		$save['id'] 		= null;
		$save['type'] 		= $type;
		$save['record_id'] 	= $record_id;
		$save['action'] 	= $action;
		
		$save['date'] = time();
		$save['user_id'] = $this->administrator['id'];

		$insert_id	= $this->CI->Rs_model->save($save, 'log');

		if($insert_id)
			return true;
		else
			return false;
	}
	
	function get_log($type, $record_id, $order_by = 'DESC')
	{
		$where_array = array();
		$where_array['type'] 		= $type;
		$where_array['record_id'] 	= $record_id;
		
		$order_array = array();
		$order_array['date'] = $order_by;
		
		$on_array = array();
		$on_array[] = 'user_id';
		$on_array[] = 'id';
		
		$log_data	= $this->CI->Rs_model->get_rows_2_tables_by_n('log', 'admin', $on_array, $where_array, $where_in_array = false, $order_array , $group_array = false);
		
		unset($where_array);
		unset($order_array);
		unset($on_array);
		
		return $log_data;
	}

}
?>
