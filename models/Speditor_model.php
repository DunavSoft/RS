<?php
Class Speditor_model extends CI_Model
{
	public $connected = false;
	function __construct()
	{
		//$this->speditor = $this->load->database('delta_pro', TRUE);
		//$this->speditor = $this->load->database('speditor', TRUE);
		$this->speditor = $this->load->database('speditorserver', true);
		
		if(!$this->speditor->conn_id == NULL) {
			$this->connected = true;
		}
	}
	
	function get_elements_by($table, $where = false, $where2 = false, $order_by = false, $ord_by = false, $limit = false, $offset = 0)
	{
		if($where && $where != '')
			$this->speditor->where($where, $where2);
		if($order_by && $order_by != '')
			$this->speditor->order_by($order_by, $ord_by);
		
		if($limit)
			$this->speditor->limit($limit)->offset($offset);
		
		$result = $this->speditor->get($table)->result();

		return $result;
	}
	
	function get_one_element_by($table, $where = false, $where2 = false)
	{
		$result = $this->speditor->where($where, $where2)->get($table)->row();
		
		$this->speditor->close();
		return $result;
	}
	
	function query($query_string)
	{
		$result = $this->speditor->query($query_string);
	
		return $result->result();
	}
	
	function disconnect()
	{
		$this->speditor->close();
	}

	function get_elements_2_tables($table, $table2, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($order_by)
			$this->speditor->order_by($order_by, $ord_by);
		
		if($group_by)
			$this->speditor->group_by($group_by);
		
		$result = $this->speditor->get($table)->result();
		
		return $result;
	}

	function get_all_elements($table, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($order_by)
			$this->speditor->order_by($order_by, $ord_by);
		
		if($group_by)
			$this->speditor->group_by($group_by);
		
		$result = $this->speditor->get($table)->result();
		
		return $result;
	}
	
	
	function get_elements_limit($table, $where_array = false, $order_array = false, $limit_offset_array = false)
	{

		if($where_array)
		foreach($where_array as $where_key=>$where_value)
		{
			$this->speditor->where($where_key, $where_value);
		}
		
		if($order_array)
		foreach($order_array as $order_key=>$order_value)
		{
			$this->speditor->order_by($order_key, $order_value);
		}
		
		if($limit_offset_array) {
			foreach($limit_offset_array as $limit_key=>$limit_value)
			{
				$this->speditor->limit($limit_key);
				$this->speditor->offset($limit_value);
				
			////	$this->speditor->select("*")->from($table);
				
			//	$result = $this->speditor->select(" FIRST $limit_key SKIP $limit_value * ")->get($table)->result();
				
			}
			//WORKS
			$result = $this->speditor->get($table)->result();
			
		}else {
			$result = $this->speditor->get($table)->result();
		}
		
		return $result;
	}
	
	function get_1_by_n($table, $where_array = false, $order_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->speditor->where($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->speditor->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->speditor->group_by($group_key);
			}
		
		$result = $this->speditor->get($table)->row();
		
		return $result;
	}
	
	
	function get_m_by_n($table, $where_array = false, $order_array = false, $limit_offset_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->speditor->where($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->speditor->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->speditor->group_by($group_key);
			}
		if($limit_offset_array)	
		foreach($limit_offset_array as $limit_key=>$limit_value)
		{
			$this->speditor->limit($limit_key);
			$this->speditor->offset($limit_value);
		}
		
		$result = $this->speditor->get($table)->result();
		
		return $result;
	}
	
	function get_m_by_n_V2($table, $where_array = false,  $where_in_array = false, $order_array = false, $limit_offset_array = false, $group_array = false)
	{

		foreach($where_array as $where_key=>$where_value)
		{
			$this->speditor->where($where_key, $where_value);
		}
		foreach($where_in_array as $where_key=>$where_value)
		{
			$this->speditor->where_in($where_key, $where_value);
		}
		foreach($order_array as $order_key=>$order_value)
		{
			$this->speditor->order_by($order_key, $order_value);
		}
		foreach($group_array as $group_key=>$g_value)
		{
			$this->speditor->group_by($group_key);
		}
		foreach($limit_offset_array as $limit_key=>$limit_value)
		{
			$this->speditor->limit($limit_key);
			$this->speditor->offset($limit_value);
		}
		
		$result = $this->speditor->get($table)->result();
		
		return $result;
	}
	
	
	function count_m_by_n($table, $where_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->speditor->where($where_key, $where_value);
			}
		
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->speditor->group_by($group_key);
			}
		
		$result = $this->speditor->select('id')->from($table)->count_all_results();
		
		return $result;
	}
	
	
	function get_rows_2_tables_by_n($table, $table2, $on_array, $where_array = false, $where_in_array = false, $order_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->speditor->where($where_key, $where_value);
			}
		if($where_in_array)
			foreach($where_in_array as $where_key=>$where_value)
			{
				$this->speditor->where_in($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->speditor->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->speditor->group_by($group_key);
			}
		

		$this->speditor->select('*')->from($table)->join($table2, $table . '.'.$on_array[0].' = '.$table2.'.'.$on_array[1]);
		
		$result = $this->speditor->get()->result();
		
		return $result;
	}
	
	
	function get_row_2_tables($table, $table2, $on_array, $where_array = false, $where_in_array = false, $order_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->speditor->where($where_key, $where_value);
			}
		if($where_in_array)
			foreach($where_in_array as $where_key=>$where_value)
			{
				$this->speditor->where_in($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->speditor->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->speditor->group_by($group_key);
			}
		

		$this->speditor->select('*, ' . $table.'.id AS first_id, ' . $table2.'.id AS second_id')->from($table)->join($table2, $table . '.'.$on_array[0].' = '.$table2.'.'.$on_array[1]);
		
		$result = $this->speditor->get()->row();
		
		return $result;
	}
	
	
	/*
	function get_option_values_template_form()
	{
		$this->speditor->group_start()->where('template_options.type', 'textfield')->or_where('template_options.type', 'textarea')->group_end();
		$this->speditor->select('template_options.*, option_values.id AS option_value_id')->from('template_options')->join('option_values', 'template_options.id = option_values.option_id');
		
		$result = $this->speditor->get()->result();
		
		return $result;
	}
	*/

}
?>