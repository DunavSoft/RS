<?php
Class Expecta_model extends CI_Model
{
	public $connected = false;
	function __construct()
	{
		$this->expecta = $this->load->database('expectaserver', true);
		
		if(!$this->expecta->conn_id == NULL) {
			$this->connected = true;
		}
	}
	
	function get_elements_by($table, $where = false, $where2 = false, $order_by = false, $ord_by = false, $limit = 5, $offset = 0)
	{
		if($where)
			$this->expecta->where($where, $where2);
		if($order_by)
			$this->expecta->order_by($order_by, $ord_by);
		
		$this->expecta->limit($limit)->offset($offset);
		
		$result = $this->expecta->get($table)->result();

		return $result;
	}
	
	
	
	function get_one_element_by($table, $where = false, $where2 = false, $order = false, $order_by = false)
	{
		$where_str = '';
		if($where != false) {
			$where_str = " WHERE $where '$where2' ";
		}
		
		$order_by_str = '';
		if($order != false) {
			$order_by_str = " ORDER BY '$order' $order_by";
		}
		
		$result = $this->expecta->query("SELECT FIRST 1 SKIP 0 * FROM $table $where_str $order_by_str;");
		
		//$this->expecta->close();
		return $result->result();
	}
	
	function query($query_string)
	{
		$result = $this->expecta->query($query_string);
	
		return $result->result();
	}
	
	function disconnect()
	{
		$this->expecta->close();
	}

	function get_elements_2_tables($table, $table2, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($order_by)
			$this->expecta->order_by($order_by, $ord_by);
		
		if($group_by)
			$this->expecta->group_by($group_by);
		
		
		
		$result = $this->expecta->get($table)->result();
		
		return $result;
	}

	function get_all_elements($table, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($order_by)
			$this->expecta->order_by($order_by, $ord_by);
		
		if($group_by)
			$this->expecta->group_by($group_by);
		
		$result = $this->expecta->get($table)->result();
		
		return $result;
	}
	
	
	function get_elements_limit($table, $where_array = false, $order_array = false, $limit_offset_array = false)
	{

		if($where_array)
		foreach($where_array as $where_key=>$where_value)
		{
			$this->expecta->where($where_key, $where_value);
		}
		
		if($order_array)
		foreach($order_array as $order_key=>$order_value)
		{
			$this->expecta->order_by($order_key, $order_value);
		}
		
		if($limit_offset_array) {
			foreach($limit_offset_array as $limit_key=>$limit_value)
			{
				$this->expecta->limit($limit_key)->offset($limit_value);	
			}
		}
		
		$result = $this->expecta->get($table)->result();

		return $result;
	}
	
	function get_1_by_n($table, $where_array = false, $order_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->expecta->where($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->expecta->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->expecta->group_by($group_key);
			}
		
		$result = $this->expecta->get($table)->row();
		
		return $result;
	}
	
	
	function get_m_by_n($table, $where_array = false, $order_array = false, $limit_offset_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->expecta->where($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->expecta->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->expecta->group_by($group_key);
			}
		if($limit_offset_array)	
		foreach($limit_offset_array as $limit_key=>$limit_value)
		{
			$this->expecta->limit($limit_key);
			$this->expecta->offset($limit_value);
		}
		
		$result = $this->expecta->get($table)->result();
		
		return $result;
	}
	
	function get_m_by_n_V2($table, $where_array = false,  $where_in_array = false, $order_array = false, $limit_offset_array = false, $group_array = false)
	{

		foreach($where_array as $where_key=>$where_value)
		{
			$this->expecta->where($where_key, $where_value);
		}
		foreach($where_in_array as $where_key=>$where_value)
		{
			$this->expecta->where_in($where_key, $where_value);
		}
		foreach($order_array as $order_key=>$order_value)
		{
			$this->expecta->order_by($order_key, $order_value);
		}
		foreach($group_array as $group_key=>$g_value)
		{
			$this->expecta->group_by($group_key);
		}
		foreach($limit_offset_array as $limit_key=>$limit_value)
		{
			$this->expecta->limit($limit_key);
			$this->expecta->offset($limit_value);
		}
		
		$result = $this->expecta->get($table)->result();
		
		return $result;
	}
	
	
	function count_m_by_n($table, $where_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->expecta->where($where_key, $where_value);
			}
		
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->expecta->group_by($group_key);
			}
		
		$result = $this->expecta->select('id')->from($table)->count_all_results();
		
		return $result;
	}
	
	
	function get_rows_2_tables_by_n($table, $table2, $on_array, $where_array = false, $where_in_array = false, $order_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->expecta->where($where_key, $where_value);
			}
		if($where_in_array)
			foreach($where_in_array as $where_key=>$where_value)
			{
				$this->expecta->where_in($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->expecta->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->expecta->group_by($group_key);
			}
		

		$this->expecta->select('*')->from($table)->join($table2, $table . '.'.$on_array[0].' = '.$table2.'.'.$on_array[1]);
		
		$result = $this->expecta->get()->result();
		
		return $result;
	}
	
	
	function get_row_2_tables($table, $table2, $on_array, $where_array = false, $where_in_array = false, $order_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->expecta->where($where_key, $where_value);
			}
		if($where_in_array)
			foreach($where_in_array as $where_key=>$where_value)
			{
				$this->expecta->where_in($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->expecta->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->expecta->group_by($group_key);
			}
		

		$this->expecta->select('*, ' . $table.'.id AS first_id, ' . $table2.'.id AS second_id')->from($table)->join($table2, $table . '.'.$on_array[0].' = '.$table2.'.'.$on_array[1]);
		
		$result = $this->expecta->get()->row();
		
		return $result;
	}
	
	
	/*
	function get_option_values_template_form()
	{
		$this->expecta->group_start()->where('template_options.type', 'textfield')->or_where('template_options.type', 'textarea')->group_end();
		$this->expecta->select('template_options.*, option_values.id AS option_value_id')->from('template_options')->join('option_values', 'template_options.id = option_values.option_id');
		
		$result = $this->expecta->get()->result();
		
		return $result;
	}
	*/

}
?>