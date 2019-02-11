<?php
Class Microinvest_model extends CI_Model
{
	function __construct()
	{
		$this->delta_pro = $this->load->database('delta_pro', TRUE);
		//	$this->speditor = $this->load->database('speditor_server', TRUE);
		//	$this->load->database('default');
	}
	
	
	function get_all_elements_by($table, $order_by = false, $ord_by = false)
	{
		$query_string = " SELECT * FROM $table ";
		if($order_by)
			$query_string .= "ORDER BY $order_by $ord_by";
		
		
		$result = $this->delta_pro->query($query_string);

		return $result->result();
	}
	
	function get_whinp100_from_oracle_by_por()
	{
		/*$nomer = '55138';
		if (strlen($nomer) == 1) $nomer = "SFC00000" . $nomer;
		if (strlen($nomer) == 2) $nomer = "SFC0000" . $nomer;
		if (strlen($nomer) == 3) $nomer = "SFC000" . $nomer;
		if (strlen($nomer) == 4) $nomer = "SFC00" . $nomer;
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		$result = $this->oracle->query(' SELECT * FROM baandb.ttisfc001601 where  T$PDNO LIKE \'%'.$nomer.'%\' ' );
		*/
		
		$result = $this->delta_pro->query(' SELECT * FROM [DeltaPro_default].[dbo].[Accountings] ' );
		
		
		var_dump($result->result());
		
		return $result->result();
	}
	
	function get_elements_by_speditor($table, $where = false, $where2 = false, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($where)
			$this->db->where($where, $where2);
		if($order_by)
			$this->db->order_by($order_by, $ord_by);
		if($group_by)
			$this->db->group_by($group_by);
		$result = $this->db->get($table)->result();
		
		return $result;
	}
	
	function get_all_elements_pdo($table, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($order_by)
			$this->delta_pro->order_by($order_by, $ord_by);
		
		if($group_by)
			$this->delta_pro->group_by($group_by);
		
		
		
		$result = $this->delta_pro->get('[DeltaPro_default].[dbo].[' . $table . ']')->result();
		
		return $result;
	}
	
	/*
	function get_all_elements_speditor($table, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($order_by)
			$this->speditor->order_by($order_by, $ord_by);
		
		if($group_by)
			$this->speditor->group_by($group_by);
		
		//$this->speditor->limit(10);
		
		$result = $this->speditor->get($table)->result();
		
		return $result;
	}
	
	
	function get_element_by_speditor($table, $where = false, $where2 = false)
	{
		$result = $this->speditor->where($where, $where2)->get($table)->row();
		return $result;
	}
	
	*/
	
	function get_rows_2_tables_by_n_pdo($table, $table2, $on_array, $where_array = false, $where_in_array = false, $order_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->delta_pro->where($where_key, $where_value);
			}
		if($where_in_array)
			foreach($where_in_array as $where_key=>$where_value)
			{
				$this->delta_pro->where_in($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->delta_pro->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->delta_pro->group_by($group_key);
			}
		

		$this->delta_pro->select('*')->from('[DeltaPro_default].[dbo].[' . $table . ']')->join('[DeltaPro_default].[dbo].[' . $table2 . ']', '[DeltaPro_default].[dbo].[' . $table . ']' . '.'.$on_array[0].' = '.'[DeltaPro_default].[dbo].[' . $table2 . ']'.'.'.$on_array[1]);
		
		$result = $this->delta_pro->get()->result();
		
		return $result;
	}

	function get_elements_2_tables($table, $table2, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($order_by)
			$this->delta_pro->order_by($order_by, $ord_by);
		
		if($group_by)
			$this->delta_pro->group_by($group_by);
		
		
		
		$result = $this->delta_pro->get('[DeltaPro_default].[dbo].[' . $table . ']')->result();
		
		return $result;
	}

	function get_element_by($table, $where = false, $where2 = false)
	{
		$result = $this->db->where($where, $where2)->get($table)->row();
		return $result;
	}
	
	function get_element_by_order($table, $where = false, $where2 = false, $order_by = false, $ord_by = false)
	{
		if($order_by)
			$this->db->order_by($order_by, $ord_by);
		
		if($where)
			$this->db->where($where, $where2);
		
		$result = $this->db->get($table)->row();
		return $result;
	}
	
	

	function get_all_elements($table, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($order_by)
			$this->db->order_by($order_by, $ord_by);
		
		if($group_by)
			$this->db->group_by($group_by);
		
		$result = $this->db->get($table)->result();
		
		return $result;
	}

	function save($data, $table)
	{
		if($data['id'])
		{
			$this->db->where('id', $data['id']);
			$this->db->update($table, $data);
			return $data['id'];
		}
		else
		{
			$this->db->insert($table, $data);
			return $this->db->insert_id();
		}
	}
	
	function save_only($data, $table)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}
	
	function save_where($data, $table, $where)
	{
		if($data[$where])
		{
			$this->db->where($where, $data[$where]);
			$this->db->update($table, $data);
			return $data['id'];
		}
		else
		{
			$this->db->insert($table, $data);
			return $this->db->insert_id();
		}
	}

	function delete($table, $where_pole, $where_value)
	{
		$this->db->where( $where_pole, $where_value);
		$result = $this->db->delete($table);
		
		return $result;
	}
	
	function delete_where($table, $where_txt, $where_value)
	{
		$this->db->where($where_txt, $where_value);
		$this->db->delete($table);
	}

	function get_elements_by($table, $where = false, $where2 = false, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($where)
			$this->db->where($where, $where2);
		if($order_by)
			$this->db->order_by($order_by, $ord_by);
		if($group_by)
			$this->db->group_by($group_by);
		$result = $this->db->get($table)->result();
		
		return $result;
	}
	
	function get_1_by_n($table, $where_array = false, $order_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->db->where($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->db->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->db->group_by($group_key);
			}
		
		$result = $this->db->get($table)->row();
		
		return $result;
	}
	
	
	function get_m_by_n($table, $where_array = false, $order_array = false, $limit_offset_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->db->where($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->db->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->db->group_by($group_key);
			}
		if($limit_offset_array)	
		foreach($limit_offset_array as $limit_key=>$limit_value)
		{
			$this->db->limit($limit_key);
			$this->db->offset($limit_value);
		}
		
		$result = $this->db->get($table)->result();
		
		return $result;
	}
	
	function get_m_by_n_V2($table, $where_array = false,  $where_in_array = false, $order_array = false, $limit_offset_array = false, $group_array = false)
	{

		foreach($where_array as $where_key=>$where_value)
		{
			$this->db->where($where_key, $where_value);
		}
		foreach($where_in_array as $where_key=>$where_value)
		{
			$this->db->where_in($where_key, $where_value);
		}
		foreach($order_array as $order_key=>$order_value)
		{
			$this->db->order_by($order_key, $order_value);
		}
		foreach($group_array as $group_key=>$g_value)
		{
			$this->db->group_by($group_key);
		}
		foreach($limit_offset_array as $limit_key=>$limit_value)
		{
			$this->db->limit($limit_key);
			$this->db->offset($limit_value);
		}
		
		$result = $this->db->get($table)->result();
		
		return $result;
	}
	
	
	function count_m_by_n($table, $where_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->db->where($where_key, $where_value);
			}
		
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->db->group_by($group_key);
			}
		
		$result = $this->db->select('id')->from($table)->count_all_results();
		
		return $result;
	}
	
	
	function get_rows_2_tables_by_n($table, $table2, $on_array, $where_array = false, $where_in_array = false, $order_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->db->where($where_key, $where_value);
			}
		if($where_in_array)
			foreach($where_in_array as $where_key=>$where_value)
			{
				$this->db->where_in($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->db->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->db->group_by($group_key);
			}
		

		$this->db->select('*')->from($table)->join($table2, $table . '.'.$on_array[0].' = '.$table2.'.'.$on_array[1]);
		
		$result = $this->db->get()->result();
		
		return $result;
	}
	
	
	function get_row_2_tables($table, $table2, $on_array, $where_array = false, $where_in_array = false, $order_array = false, $group_array = false)
	{
		if($where_array)
			foreach($where_array as $where_key=>$where_value)
			{
				$this->db->where($where_key, $where_value);
			}
		if($where_in_array)
			foreach($where_in_array as $where_key=>$where_value)
			{
				$this->db->where_in($where_key, $where_value);
			}
		if($order_array)
			foreach($order_array as $order_key=>$order_value)
			{
				$this->db->order_by($order_key, $order_value);
			}
		if($group_array)
			foreach($group_array as $group_key=>$g_value)
			{
				$this->db->group_by($group_key);
			}
		

		$this->db->select('*, ' . $table.'.id AS first_id, ' . $table2.'.id AS second_id')->from($table)->join($table2, $table . '.'.$on_array[0].' = '.$table2.'.'.$on_array[1]);
		
		$result = $this->db->get()->row();
		
		return $result;
	}
	
	
	function check_attr($table, $field_name = 'attribute', $attribute, $id = false)
	{
		if($id)
		{
			$this->db->where('id !=', $id);
		}
		
		//$this->db->where($field, $c_id);
		$this->db->where($field_name, $attribute);
		
		return (bool) $this->db->count_all_results($table);
	}
	
	function validate_attribute($table, $field_name, $slug, $id = false, $count=false)
	{
		if($this->check_attr($table, $field_name, $slug.$count, $id))
		{
			if(!$count)
			{
				$count	= 1;
			}
			else
			{
				$count++;
			}
			return $this->validate_attribute($table, $field_name, $slug, $id, $count);
		}
		else
		{
			return $slug.$count;
		}
	}
	
	
	function get_option_values_template_form()
	{
		$this->db->group_start()->where('template_options.type', 'textfield')->or_where('template_options.type', 'textarea')->group_end();
		$this->db->select('template_options.*, option_values.id AS option_value_id')->from('template_options')->join('option_values', 'template_options.id = option_values.option_id');
		
		$result = $this->db->get()->result();
		
		return $result;
	}

}
?>