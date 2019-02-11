<?php
Class Aps_model extends CI_Model
{
	var $oracle;
	var $tm;
	
	function aps_model()
	{
		$this->oracle = $this->load->database('baan', TRUE);
		$this->aps = $this->load->database('aps', TRUE);
		
		parent::__construct();

	}
	
	function query($string)
	{
		$result = $this->aps->query( $string );
		
		return $result->result();
	}
	
	//NEW FUNCTION WITH CUSTOM TABLE
	// INPUT: table (string), order_by (string), ord_by (string)
	function get_all_elements($table, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($order_by)
			$this->aps->order_by($order_by, $ord_by);
		
		if($group_by)
			$this->aps->group_by($group_by);
		
		$result = $this->aps->get($table)->result();
		
		return $result;
	}
	
	function get_statistic_by_prizn_limit($table, $where = false, $where2 = false, $order_by = false, $ord_by = false, $limit = false)
	{
		$num_rows = $this->aps->select('id')->from($table)->count_all_results();
		//echo $num_rows;
		
		if($where)
			$this->aps->where($where, $where2);
		
		if($order_by)
			$this->aps->order_by($order_by, $ord_by);
		if($limit)
		{
			$this->aps->offset($num_rows - $limit);
			$this->aps->limit($limit);
		}
		$result = $this->aps->get($table)->result();
		
		return $result;
	}
	
	function get_elements_by_prizn($table, $where = false, $where2 = false, $order_by = false, $ord_by = false, $group_by = false)
	{
		if($where)
			$this->aps->where($where, $where2);
		if($order_by)
			$this->aps->order_by($order_by, $ord_by);
		if($group_by)
			$this->aps->group_by($group_by);
		$result = $this->aps->get($table)->result();
		
		return $result;
	}
	
	function get_elements_by_2_prizn($table, $where = false, $where2 = false, $where3 = false, $where4 = false, $order_by = false, $ord_by = false, $group_by = false)
	{
		$this->aps->where($where, $where2);
		$this->aps->where($where3, $where4);
		if($ord_by)
			$this->aps->order_by($order_by, $ord_by);
		if($group_by)
			$this->aps->group_by($group_by);
		
		$result = $this->aps->get($table)->result();
		
		return $result;
	}
	
	
	function get_elements_from_db($table, $where_array = false, $order_array = false, $group_array = false)
	{
		foreach($where_array as $where_key=>$where_value)
		{
			$this->aps->where($where_key, $where_value);
		}
		foreach($order_array as $order_key=>$order_value)
		{
			$this->aps->order_by($order_key, $order_value);
		}
		foreach($group_array as $group_key=>$g_value)
		{
			$this->aps->group_by($group_key);
		}
		
		/*
		if($ord_by)
			$this->aps->order_by($order_by, $ord_by);
		if($group_by)
			$this->aps->group_by($group_by);
		*/
		
		$result = $this->aps->get($table)->result();
		
		return $result;
	}
	
	
	function get_1_by_n($table, $where_array = false, $order_array = false, $group_array = false)
	{
		
		foreach($where_array as $where_key=>$where_value)
		{
			$this->aps->where($where_key, $where_value);
		}
		foreach($order_array as $order_key=>$order_value)
		{
			$this->aps->order_by($order_key, $order_value);
		}
		foreach($group_array as $group_key=>$g_value)
		{
			$this->aps->group_by($group_key);
		}
		
		$result = $this->aps->get($table)->row();
		
		return $result;
	}
	
	
	function get_m_by_n($table, $where_array = false, $order_array = false, $limit_offset_array = false, $group_array = false)
	{
		
		foreach($where_array as $where_key=>$where_value)
		{
			$this->aps->where($where_key, $where_value);
		}
		foreach($order_array as $order_key=>$order_value)
		{
			$this->aps->order_by($order_key, $order_value);
		}
		foreach($group_array as $group_key=>$g_value)
		{
			$this->aps->group_by($group_key);
		}
		foreach($limit_offset_array as $limit_key=>$limit_value)
		{
			$this->aps->limit($limit_key);
			$this->aps->offset($limit_value);
		}
		
		$result = $this->aps->get($table)->result();
		
		return $result;
	}
	
	
	function count_m_by_n($table, $where_array = false, $group_array = false)
	{
		
		foreach($where_array as $where_key=>$where_value)
		{
			$this->aps->where($where_key, $where_value);
		}
		
		foreach($group_array as $group_key=>$g_value)
		{
			$this->aps->group_by($group_key);
		}
		
		$result = $this->aps->select('id')->from($table)->count_all_results();
		//$result = $this->aps->count_all_results();
		
		return $result;
	}
	
	
	function get_rows_2_tables_by_n($table, $table2, $on_array, $where_array = false, $order_array = false, $group_array = false)
	{
		
		foreach($where_array as $where_key=>$where_value)
		{
			if($where_value != '')
				$this->aps->where($where_key, $where_value);
			else
			{
				$this->aps->where($where_key);
			}
		}
		foreach($order_array as $order_key=>$order_value)
		{
			$this->aps->order_by($order_key, $order_value);
		}
		foreach($group_array as $group_key=>$g_value)
		{
			$this->aps->group_by($group_key);
		}
		

		$this->aps->select('*')->from($table)->join($table2, $table . '.'.$on_array[0].' = '.$table2.'.'.$on_array[1]);
		
		$result = $this->aps->get()->result();
		
		return $result;
	}
	
	
	
	function get_1_element_by_2_prizn($table, $where = false, $where2 = false, $where3 = false, $where4 = false, $order_by = false, $ord_by = false)
	{
		if($where)
			$this->aps->where($where, $where2);
		if($where3)
			$this->aps->where($where3, $where4);
		if($ord_by)
			$this->aps->order_by($order_by, $ord_by);
		
		$result = $this->aps->get($table)->row();
		
		return $result;
	}
	
	function get_1_element_by_3_prizn($table, $where = false, $where2 = false, $where3 = false, $where4 = false, $where5 = false, $where6 = false, $order_by = false, $ord_by = false)
	{
		$this->aps->where($where, $where2);
		$this->aps->where($where3, $where4);
		$this->aps->where($where5, $where6);
		if($ord_by)
			$this->aps->order_by($order_by, $ord_by);
		
		$result = $this->aps->get($table)->row();
		
		return $result;
	}
	
	function get_1_element_by_4_prizn($table, $where = false, $where2 = false, $where3 = false, $where4 = false, $where5 = false, $where6 = false, $where7 = false, $where8 = false, $order_by = false, $ord_by = false)
	{
		$this->aps->where($where, $where2);
		$this->aps->where($where3, $where4);
		$this->aps->where($where5, $where6);
		$this->aps->where($where7, $where8);
		if($ord_by)
			$this->aps->order_by($order_by, $ord_by);
		
		$result = $this->aps->get($table)->row();
		
		return $result;
	}
	
	
	
	
	function get_1_element_by_n_prizn($table, $where , $order_by = false)
	{
		$this->aps->where(array($where));
		
		if($order_by)
			$this->aps->order_by( array($order_by) );
		
		$result = $this->aps->get($table)->row();
		
		return $result;
	}
	
	
	
	function get_elements_by_n_prizn($table, $where , $order_by = false, $ord_by = false)
	{
		$this->aps->where($where);
		
		//if($order_by)
			$this->aps->order_by($order_by, $ord_by);
		
		$result = $this->aps->get($table)->result();
		
		return $result;
	}
	
	function get_element_by_prizn($table, $where = false, $where2 = false)
	{
		$result = $this->aps->where($where, $where2)->get($table)->row();
		return $result;
	}
	
	function get_element_by_prizn_NEW($table, $where = false, $where2 = false, $order_by = false, $ord_by = false)
	{
		if($order_by && $ord_by)
			$this->aps->order_by($order_by, $ord_by);
		
		$result = $this->aps->where($where, $where2)->get($table)->row();
		return $result;
	}
	
	function get_element_by_prizn_array($table, $where = false, $where2 = false)
	{
		$result = $this->aps->where($where, $where2)->get($table)->result();
		return $result;
	}
	
	function get_operation_by_nomer($nomer)
	{
		
		$this->aps->where('nomer', $nomer);
		$result = $this->aps->get('operations')->row();
		
		return $result;
	}
	
	function get_operation_from_oracle($nomer)
	{
		$result = $this->oracle->query('SELECT * FROM baandb.ttirou003601 WHERE T$TANO = ' . $nomer);

		return $result->result_array;
	}
	
	function get_times_by_trz($trz, $time)
	{
		$this->aps->where('trz', $trz);
		$this->aps->where('nachalo_int >', strtotime($time));
		$this->aps->order_by('nachalo_int', "DESC");
		$result = $this->aps->get('vremena')->result();
		return $result;
	}
	
	function get_mashina_by_nomer($nomer)
	{
		$this->aps->where('nomer', $nomer);
		$result = $this->aps->get('mashini')->row();
		
		return $result;
	}
	
	function get_mashina_from_oracle($nomer)
	{
		if ($nomer != '')
			$result = $this->oracle->query(' SELECT T$DSCA FROM baandb.ttirou002601 where T$MCNO = '.$nomer );
		
		return $result->result_array;
	}
	
	function get_opno_from_oracle($nomer)
	{
		if (strlen($nomer) == 1) $nomer = "SFC00000" . $nomer;
		if (strlen($nomer) == 2) $nomer = "SFC0000" . $nomer;
		if (strlen($nomer) == 3) $nomer = "SFC000" . $nomer;
		if (strlen($nomer) == 4) $nomer = "SFC00" . $nomer;
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		$result = $this->oracle->query(' SELECT T$MCNO, T$TANO FROM baandb.tticst002601 where  T$PDNO LIKE \'%'.$nomer.'%\' ' );
		
		return $result->result_array;
	}
	
	function get_machine_performance($mashine, $operation, $grupe)
	{
		$this->aps->order_by('id', 'DESC');
		
		if($mashine != '')
			$this->aps->where('nomer', $mashine);
		if($operation != '')
			$this->aps->where('operation', $operation);
		if($grupe != '')
			$this->aps->where('kod_izd', $grupe);
		
		$result = $this->aps->get('machine_performance')->row();
		
		if(count($result) == 0)
		{
			$this->aps->where('kod_izd', '5555');
			
			if($mashine != '')
				$this->aps->where('nomer', $mashine);
			if($operation != '')
				$this->aps->where('operation', $operation);
			
			$result = $this->aps->get('machine_performance')->row();
		}
		
		return $result;
	}
	
	
	
	function count_by_1_prizn($table, $where = false, $where2 = false)
	{
		$result = $this->aps->select('id')->from($table)
		->where( $where, $where2)
		->count_all_results();
		
		return $result;
	}
	
	
	function count_by_1_prizn_NEW($table)
	{
		$result = $this->aps->select('id')->from($table)
		->count_all_results();
		
		return $result;
	}
	
	function count_by_2_prizn($table, $where = false, $where2 = false, $where3 = false, $where4 = false)
	{
		$result = $this->aps->select('id')->from($table)
		->where( $where, $where2)
		->where( $where3, $where4)
		->count_all_results();
		
		return $result;
	}
	
	
	
	//NEW FUNCTION WITH CUSTOM TABLE
	// INPUT: $data (array), table (string)
	function save($data, $table)
	{
		if($data['id'])
		{
			$this->aps->where('id', $data['id']);
			$this->aps->update($table, $data);
			return $data['id'];
		}
		else
		{
			$this->aps->insert($table, $data);
			return $this->aps->insert_id();
		}
	}
	
	//NEW FUNCTION WITH CUSTOM TABLE
	// INPUT: $data (array), table (string), $where (string)
	function save_where($data, $table, $where)
	{
		$whereis = $this->get_element_by_prizn($table, $where , $data[$where]);
		if( count($whereis) > 0 )
		{
			$this->aps->where( $where, $data[$where]);
			$this->aps->update($table, $data);
			return $data[$where];
		}
		else
		{
			$this->aps->insert($table, $data);
			return $this->aps->insert_id();
		}
	}
	
	function save_where2($data, $table, $where, $where2)
	{
		$whereis = $this->get_element_by_prizn($table, $where , $data[$where]);
		$whereis2 = $this->get_element_by_prizn($table, $where , $data[$where]);
		if( count($whereis) > 0 && count($whereis2) > 0 )
		{
			$this->aps->where( $where, $data[$where]);
			$this->aps->where( $where2, $data[$where2]);
			$this->aps->update($table, $data);
			return $data[$where];
		}
		else
		{
			$this->aps->insert($table, $data);
			return $this->aps->insert_id();
		}
	}
	
	function reset_kanbans($data)
	{
		//UPDATE `zd_planned` SET `mashina` = '1304', `kanban` = NULL, `tvardo_plan` = 0 WHERE `mashina` = '1304' AND `tvardo_plan` = 0
		$this->aps->where( 'mashina' , $data['mashina'] );
		$this->aps->where( 'tvardo_plan' , 0);
		$this->aps->update('planned', $data);
		
	}
	
	
	function update_all( $table, $data)
	{
		$this->aps->update($table, $data);
		
	}
	
	
	function update_planned($data, $table, $porachka, $mashina, $operation)
	{
		$this->aps->where( 'porachka', $porachka );
		$this->aps->where( 'operation', $operation );
		$this->aps->where( 'mashina', $mashina );
		$this->aps->update($table, $data);
	}
	
	
	
	
	function save_temp($data, $table)
	{
		$this->aps->insert($table, $data);
		return $this->aps->insert_id();
		
	}
	
	function delete($table, $where_pole, $here_value)
	{
		$this->aps->where( $where_pole, $here_value);
		$result = $this->aps->delete($table);
		
		return $result;
	}
	
	function delete_by_2_priz($table, $where_pole, $here_value, $where_pole2, $here_value2)
	{
		$this->aps->where( $where_pole, $here_value);
		$this->aps->where( $where_pole2, $here_value2);
		$result = $this->aps->delete($table);
		
		return $result;
	}
	

	function get_grupi_izdelia()
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttcmcs023601 WHERE T$CITG > \'5999\' AND T$CITG < \'7000\' ORDER BY T$CITG ASC ' );
		
		return $result->result_array;
	}
	
	
	function get_times_for_grupi($start_int, $end_int)
	{
		$this->aps->where('grupe', NULL);
		$this->aps->where('nachalo_int > ', $start_int);
		$this->aps->where('nachalo_int < ', $end_int);
		$this->aps->order_by('porachka', 'ASC');
		$result = $this->aps->get('vremena')->result();
		return $result;
	}
	
	function get_times_for_grupi_by_grupe($mashina, $grupe, $operacia, $start_int, $end_int)
	{
		$this->aps->where('mashina', $mashina);
		if($grupe != '5555')
			$this->aps->where('grupe', $grupe);
		$this->aps->where('operation', $operacia);
		$this->aps->where('nachalo_int > ', $start_int);
		$this->aps->where('nachalo_int < ', $end_int);
		$result = $this->aps->get('vremena')->result();
		return $result;
	}
	
	function get_vremena($limit, $offset)
	{
		$result = $this->aps->order_by('id', 'DESC')->get('vremena', $limit, $offset)->result();
		
		return $result;
	}

	function count_vremena()
	{
		$br = $this->aps->select('id')->from('vremena')->count_all_results();
		
		return $br;
	}
	
	
	function get_vremena_for_approval($limit, $offset, $approved_oper = 0)
	{
		$this->aps->where('approved_oper', $approved_oper);
		$result = $this->aps->order_by('nachalo_int', 'DESC')->get('vremena', $limit, $offset)->result();
		
		return $result;
	}
	
	function count_vremena_for_approval($approved_oper = 0)
	{
		$this->aps->where('approved_oper', $approved_oper);
		$br = $this->aps->select('id')->from('vremena')->count_all_results();
		
		return $br;
	}
	
	function get_mitm_from_oracle_by_por($nomer)
	{
		if (strlen($nomer) == 1) $nomer = "SFC00000" . $nomer;
		if (strlen($nomer) == 2) $nomer = "SFC0000" . $nomer;
		if (strlen($nomer) == 3) $nomer = "SFC000" . $nomer;
		if (strlen($nomer) == 4) $nomer = "SFC00" . $nomer;
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		$result = $this->oracle->query(' SELECT T$MITM FROM baandb.ttisfc001601 where  T$PDNO LIKE \'%'.$nomer.'%\' ' );
		
		return $result->result_array;
	}
	
	function get_newest_orders($nomer)
	{
		if (strlen($nomer) == 1) $nomer = "SFC00000" . $nomer;
		if (strlen($nomer) == 2) $nomer = "SFC0000" . $nomer;
		if (strlen($nomer) == 3) $nomer = "SFC000" . $nomer;
		
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		if (strlen($nomer) == 4) $nomer = "SFC00" . $nomer;
		
		$result = $this->oracle->query(' SELECT T$CPRJ, T$PDNO, T$OPRO FROM baandb.ttisfc001601 where  T$PDNO > \''.$nomer.'\' AND T$CPRJ <> \' \' AND T$OSTA > 3 ' );

		return $result->result_array;
	}
	
	function get_grupe_from_oracle_by_mitm($nomer)
	{
		$result = $this->oracle->query(' SELECT T$CITG FROM baandb.ttcibd001601 where T$ITEM = \''.$nomer.'\' ' );
		
		return $result->result_array;
	}
	
	function get_tcibd001_from_oracle($item_nr)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttcibd001601 where T$ITEM = \''.$item_nr.'\' ' );
		
		return $result->result_array;
	}
	
	
	function search_machine($text)
	{
		$this->aps->where('nomer', $text);
		$this->aps->limit(1);
		$result = $this->aps->get('mashini')->result();
		
		return $result;
	}
	
	
	function get_machine_by_nomer($text)
	{
		$this->aps->where('nomer', $text);
		$result = $this->aps->get('mashini')->row();
		
		return $result;
	}
	
	
	function get_porachki_from_APS()
	{
		$this->aps->where('active', 1);
		$result = $this->aps->get('porachki')->result();
		
		return $result;
	}
	
	function copy_table_to_another($table_source, $table_destination)
	{
		$this->aps->select('*');
		$q = $this->aps->get($table_source)->result(); // get result from table
		foreach ($q as $r) 
		{
			$this->aps->insert($table_destination, $r); // insert each row to country table
		}
	}
	
	function copy_table_to_another_where($table_source, $table_destination, $where_array = false)
	{
		foreach($where_array as $where_key=>$where_value)
		{
			$this->aps->where($where_key, $where_value);
		}
		foreach($order_array as $order_key=>$order_value)
		{
			$this->aps->order_by($order_key, $order_value);
		}
		
		$this->aps->select('*');
		$q = $this->aps->get($table_source)->result(); // get result from table
		foreach ($q as $r) 
		{
			$this->aps->insert($table_destination, $r); // insert each row to country table
		}
	}
	
	
	function get_porachki_from_APS_with_exec_operations()
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->get()->result();
		
		return $result;
	}
	
	function get_porachki_from_APS_with_exec_operations_group_by()
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->group_by('porachki.porachka')
		->get()->result();
		
		return $result;
	}
	
	function get_porachki_from_APS_with_exec_operations_group_by_date_end()
	{
		$now = time();
		$after_2_monts = $now + 60 * 86400;
		$prefix = $this->aps->dbprefix; 
		$result = $this->aps->select('porachki.data_krai_int AS dk , COUNT('.$prefix.'porachki.id) as c_br, SUM('.$prefix.'porachki.br_listi) as sum_listi, porachki.data_krai')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->where('porachki.data_krai_int < ', $after_2_monts)
		->where('porachki.data_krai_int > ', $now - 86400 )
		->group_by('porachki.data_krai_int')
		->get()->result();
		
		return $result;
	}
	
	function get_porachki_from_APS_with_exec_operations_group_by_date_end_V2()
	{
		$now = time();
		$after_2_monts = $now + 60 * 86400;
		$prefix = $this->aps->dbprefix; 
		$result = $this->aps->select('porachki.data_krai_int AS dk , porachki.data_krai, porachki.br_listi, porachki.porachka')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->where('porachki.data_krai_int < ', $after_2_monts)
		->where('porachki.data_krai_int > ', $now - 43200 )
		->order_by('porachki.data_krai_int', 'ASC')
		->get()->result();
		
		return $result;
	}
	
	
	function get_porachki_with_exec_operations_podg_osn_mater($marker = 1, $print)
	{
		if($marker == 1) $this->aps->where('porachki.marker1', 0);
		if($marker == 2) $this->aps->where('porachki.marker2', 0);
		if($marker == 3) $this->aps->where('porachki.marker3', 0);
		
		if($print)
			$this->aps->order_by('porachki.osn_mash', "ASC");
	
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('planned')->join('porachki', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		//FF ->order_by('porachki.osn_mash', "ASC")
		//->order_by('planned.mashina', "ASC")
		->order_by('planned.tvardo_plan', "DESC")
		->order_by('porachki.data_krai_int', "ASC")

		->group_by('planned.porachka')
		
		->get()->result();
		
		return $result;
	}
	
	function get_porachki_osn_mater_on_machines($marker = 1, $print, $osn_mash = false)
	{
		if($marker == 1) $this->aps->where('porachki.marker1', 0);
		if($marker == 2) $this->aps->where('porachki.marker2', 0);
		if($marker == 3) $this->aps->where('porachki.marker3', 0);
		if($marker == 4) $this->aps->where('porachki.marker4', 0);
		if($marker == 5) $this->aps->where('porachki.marker5', 0);
		
		if($print)
			$this->aps->order_by('porachki.osn_mash', "ASC");
		
		if($osn_mash != false)
			$this->aps->where('porachki.osn_mash', $osn_mash);
	
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.operation != ', 260)
		->where('planned.last_oper', 1)
		->order_by('porachki.osn_mash', "ASC")
		->order_by('planned.tvardo_plan', "DESC")
		->order_by('planned.kanban', "ASC")
		//FF ->order_by('porachki.marker1', "DESC")
		//FF ->order_by('porachki.marker2', "DESC")
		//FF ->order_by('porachki.marker3', "DESC")
		->order_by('porachki.data_krai_int', "ASC")
		//->order_by('planned.kanban', "ASC")
		
		//->order_by('planned.nachalo_pred', "ASC")
		
		->group_by('planned.porachka')
		
		->get()->result();
		
		return $result;
	}
	
	
	
	
	function get_stoped_orders()
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->where('planned.otkaz', 1)
		->count_all_results();
		
		return $result;
	}
	
	function count_stoped_orders()
	{
		$result = $this->aps->select('id')->from('otkazani')
		->where('otkazani.archive', 0)
		->group_by('porachka')
		
		->get()->result();
		
		return $result;
	}
	
	
	
	
	function get_porachki_from_APS_with_exec_operations_by_machine_Priority2($machine)
	{
		$result = $this->aps->select('planned.kanban, planned.id, planned.porachka ')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.tvardo_plan', 1)
		->where('planned.last_oper', 0)
		->where('planned.mashina', $machine)
		->where('planned.kanban >', 0)
		->get()->result();
		
		return $result;
	}
	
	
	function get_porachki_from_APS_with_exec_operations_by_machine($machine)
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $machine)
		->where('planned.otkaz', NULL)
//		->where('planned.kanban >', 0)
		->order_by('planned.immediately', "DESC")
		->order_by('planned.tvardo_plan', "DESC")
		
		->order_by('planned.kanban', "ASC")
		->get()->result();
		
		return $result;
	}
	
	function get_porachki_from_APS_with_exec_operations_by_machine_limit($machine, $limit)
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $machine)
		->where('planned.otkaz', NULL)
//		->where('planned.kanban >', 0)
		->order_by('planned.kanban', "ASC")
		->limit($limit)
		->get()->result();
		
		return $result;
	}
	
	function get_porachki_from_APS_with_exec_operations_by_machine_hand()
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->where('planned.mashina', '')
		->where('planned.otkaz', NULL)
//		->where('planned.kanban >', 0)
		->order_by('planned.kanban', "ASC")
		->get()->result();
		
		return $result;
	}
	
	function get_porachki_from_APS_with_exec_all_operations_by_machine($machine)
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('( zd_planned.tvardo_plan = 1 OR zd_planned.last_oper = 1 )', NULL, FALSE)
		->where('planned.mashina', $machine)
		->where('planned.otkaz', NULL)
		->where('planned.kanban >', 0)
		->order_by('planned.kanban', "ASC")
		->get()->result();
		
		return $result;
	}
	
	function get_next_tv_planir_operations_by_machine($machine)
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('( zd_planned.tvardo_plan = 1 OR zd_planned.last_oper = 1 )', NULL, FALSE)
		->where('planned.mashina', $machine)
		->where('planned.tvardo_plan', 1)
		->where('planned.otkaz', NULL)
		->where('planned.kanban >', 0)
		->order_by('planned.kanban', "ASC")
		->get()->result();
		
		return $result;
	}
	
	function get_porachki_operations_by_machine_for_organize($machine)
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('( zd_planned.tvardo_plan = 1 OR zd_planned.last_oper = 1 )', NULL, FALSE)
		->where('planned.mashina', $machine)
		
		//->where('planned.kanban >', 0)
		->order_by('planned.kanban', "ASC")
		->get()->result();
		
		return $result;
	}
	
	
	function get_orders_operations_by_oper_id_array($operation_id_array)
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		//->where('porachki.active', 1)
		//->where('planned.izpalneno', 0)
		->where('planned.id IN ', implode(',', array_map('intval', $operation_id_array)))
		//->order_by('planned.tvardo_plan', "DESC")
		//->order_by('planned.kanban', "ASC")
		//->order_by('planned.poreden_no', "ASC")
		->get()->result();
		
		
		
		return $result;
	}
	
	
	
	function get_order_operation_by_oper_id($operation_id)
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('planned.id',  $operation_id)
		->get()->row();

		return $result;
	}
	
	
	function get_orders_after($order_number)
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.porachka >=',  $order_number)
		->group_by('porachki.porachka')
		->get()->result();

		return $result;
	}
	
	
	function get_porachki_unactive($offset, $limit)
	{
		$this->tm = $this->load->database('tm', TRUE);
		$this->tm->where('active', 0);
		$this->tm->limit($limit);
		$this->tm->offset($offset);
		$this->tm->order_by('porachka', 'DESC');
		$result = $this->tm->get('porachki')->result();
		
		return $result;
	}
	
	function get_porachka_aps($nomer)
	{
		
		$this->aps->where('porachka', $nomer);
		$result = $this->aps->get('porachki')->row();
		
		return $result;
	}
	
	function get_porachka_aps_SIMULATION3($nomer)
	{
		
		$this->aps->where('porachka', $nomer);
		$result = $this->aps->get('porachki_simulation')->row();
		
		return $result;
	}
	
	function get_smeni()
	{
		$this->aps->order_by('id', 'ASC');
		$result = $this->aps->get('smeni')->result();
		
		return $result;
	}
	
	function get_machines_with_default_shifts()
	{
		$this->aps->where('def_sminana1 !=', '');
		$this->aps->order_by('ordering', 'ASC');
		$this->aps->order_by('ABS(nomer)', 'ASC');
		$result = $this->aps->get('mashini')->result();
		
		return $result;
	}
	
	
	function get_machines_by_zvena_with_image($zveno = '')
	{
		if($zveno != '')
			$this->aps->where('zveno', $zveno);
		$this->aps->where('image !=', '');
		$this->aps->order_by('zveno', 'ASC');
		$this->aps->order_by('ordering', 'ASC');
		$result = $this->aps->get('mashini')->result();
		
		return $result;
	}
	
	
	function get_zvena_from_machines()
	{
		$this->aps->where('zveno !=', '');
		$this->aps->group_by('zveno');
		$this->aps->order_by('ordering', 'ASC');
		$result = $this->aps->get('mashini')->result();
		
		return $result;
	}
	
	function insert_smeni_into_resources($data)
	{
		if($data['id'] != '')
		{
			$this->aps->where('id', $data['id']);
			$this->aps->update('resource_callendar', $data);
		}
		else
			$this->aps->insert('resource_callendar', $data);
		
		return $this->aps->insert_id();
	}
	
	function get_resource_callendar($mashine, $date)
	{
		$this->aps->where('machine', $mashine);
		$this->aps->where('date', $date);
		
		$result = $this->aps->get('resource_callendar')->row();
		
		return $result;
	}
	
	function get_resource_callendar_shift($mashine, $date, $shift)
	{
		$this->aps->where('machine', $mashine);
		$this->aps->where('shift_id', $shift);
		$this->aps->where('date', $date);
		
		$result = $this->aps->get('resource_callendar')->row();
		
		return $result;
	}
	
	function get_resource_callendar_shift2($mashine, $date, $shift)
	{
		$this->aps->where('b_planned', 1);
		$this->aps->where('machine', $mashine);
		$this->aps->where('shift_id', $shift);
		$this->aps->where('date', $date);
		
		$result = $this->aps->get('resource_callendar')->row();
		
		return $result;
	}
	
	function get_resource_callendar_day($mashine, $date, $active, $shift_id)
	{
		$this->aps->where('machine', $mashine);
		$this->aps->where('date', $date);
		if($active != false)
			$this->aps->where('active', $active);
		if($shift_id != false)
			$this->aps->where('shift_id', $shift_id);
		
		$this->aps->order_by('shift_id', 'ASC');
		
		$result = $this->aps->get('resource_callendar')->row();
		
		return $result;
	
	}
	
	function get_resource_callendar_day_ZERO()
	{
		$this->aps->where('active', 2);
		
		$result = $this->aps->get('resource_callendar')->row();
		
		return $result;
	
	}
	
	function select_orders_operations($machina)
	{
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('planned.izpalneno', 0)
		->where('planned.nachalo_pred', 0)
		->where('planned.krai_pred', 0)
		->where('planned.mashina', $machina)
		->order_by('planned.porachka', 'ASC')
		->order_by('poreden_no', 'ASC')
		
		->get()->result();
		
		return $result;
	}
	
	function select_active_orders_operations($order)
	{
		$this->aps->where('porachka', $order);
		$result = $this->aps->get('planned')->result();
		
		return $result;
	}
	
	
	function get_planned_and_orders_by_order($mashine, $ordering_array)
	{
		/*$this->aps->order_by('porachki.porachka', 'DESC');
		$result = $this->aps->select('porachki.*, planned.* , planned.id as planned_id')->from(array('porachki', 'planned'))
		->where('planned.mashina', $mashine)
		->where('planned.porachka', 'porachki.porachka')
		->get()->result();
		*/
		
		
		
		/*
		
		foreach($ordering_array['order_by_machine'] as $order_by_machine)
		{
			$this->aps->order_by('porachki.' . $order_by_machine->criteria , 'ASC');
		}*/
		//Ако не е избрана опцията " Групиране на поръчки винаги без оглед на срока" в машини
		$mas_info = $this->get_mashina_by_nomer($mashine);
		if($mas_info->group_always == 0)
		{
			if($ordering_array['method'] == 'CR')
				$this->aps->order_by('porachki.critical_ratio', 'ASC');
			
			if($ordering_array['method'] == 'RANDOM')
				$this->aps->order_by('porachki.id', 'RANDOM');
		}
		
		$this->aps->order_by('planned.poreden_no', 'ASC');	
		
		$result = $this->aps->select('porachki.*, planned.* , planned.id as planned_id')
		->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('planned.mashina', $mashine)
		->get()->result();
		
		return $result;
	}
	
	
	function get_tipcs210_by_MITM($mitm)
	{
	
		$result = $this->oracle->query(' SELECT * FROM baandb.ttipcs210601 where  T$FRMC = \''.$mitm.'\' ' );
	
		return $result->result_array;
	}
	
	function get_tipcs210($nomer)
	{
	
		$result = $this->oracle->query(' SELECT * FROM baandb.ttipcs210601 where  T$FRMC = \''.$nomer.'\' ' );
	
		return $result->result_array;
	}
	
	
	function get_tipcs211_by_MITM($mitm)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttipcs211601 where  T$ITEM = \''.$mitm.'\' ' );
	
		return $result->result_array;
	}
	
	function get_tipcs210_211_from_oracle($item)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttipcs210601, baandb.ttipcs211601 WHERE baandb.ttipcs210601.T$FRMC = \''.$item.'\' AND 
		baandb.ttipcs210601.T$FRMC = baandb.ttipcs211601.T$FRMC AND 
		baandb.ttipcs210601.T$MITM = baandb.ttipcs211601.T$MITM ' );
		$res = $result->result_array;
		
		return $res;
	}
	
	
	function search_planned_and_orders_by_kriteria($UID, $mashine, $ordering_array, $ordering_array_values)
	{
		$this->aps->where('UID', $UID);
		
		foreach($ordering_array['order_by_machine'] as $order_by_machine)
		{
			if($i == 0)
			{
				//ne znam zashto e bilo, no mai ne trqbva da e tuk!!!
				$this->aps->where($order_by_machine->criteria , $ordering_array_values[$order_by_machine->criteria]);
			}
			$i++;
		}
		
		
		
		foreach($ordering_array['order_by_machine'] as $order_by_machine)
		{
			//var_dump($order_by_machine);
			//$this->aps->order_by('porachki_temp.' . $order_by_machine->criteria , 'ASC');
			$this->aps->order_by('porachki_temp.' . $order_by_machine->criteria , $order_by_machine->ordering);
		}
		
		$this->aps->order_by('poreden_no', 'ASC');	
		
		$result = $this->aps->select('*')
		->from('porachki_temp')
		->where('porachki_temp.mashina', $mashine)
		->get()->result();
		
		return $result;
		
	}
	
	
	
	function search_planned_and_orders_by_kriteria_NEW($UID, $mashine, $ordering_array, $oa_value)
	{
		/*
		$i =0;
		if($last_executed_order)
		{
			foreach($ordering_array['order_by_machine'] as $order_by_machine)
			{
				if($i == 0)
				{
					$this->aps->where('porachka', $last_executed_order);
					$this->aps->where('mashina', $mashine);
					$this->aps->order_by('krai_int', 'DESC');
					$result = $this->aps->get('vremena')->row();
					
					$this->aps->select($order_by_machine->criteria);
					$this->aps->where('porachka', $last_executed_order);
					$result = $this->aps->get('porachki')->row();

					echo $oa_value = $result->$order_by_machine->criteria;
				}
				$i++;
			}
		}*/
		
		$this->aps->where('UID', $UID);

		$i = 0;
		foreach($ordering_array['order_by_machine'] as $order_by_machine)
		{
			if($i == 0)
			{
				$this->aps->where($order_by_machine->criteria , $oa_value);
			}
			$i++;
		}

		$this->aps->order_by('critical_ratio', 'ASC');	
		$this->aps->order_by('tvardo_plan', 'DESC');
		
		foreach($ordering_array['order_by_machine'] as $order_by_machine)
		{
			$this->aps->order_by('porachki_temp.' . $order_by_machine->criteria , $order_by_machine->ordering);
		}
		
		$this->aps->order_by('poreden_no', 'ASC');	
		
		$result = $this->aps->select('*')
		->from('porachki_temp')
		->where('porachki_temp.mashina', $mashine)
		->get()->result();
		
		//var_dump($result);
		
		return $result;
		
	}
	
	function search_planned_and_orders_by_kriteria_NEW_SIMULATION3($UID, $mashine, $ordering_array, $oa_value)
	{
		$this->aps->where('UID', $UID);

		$i = 0;
		foreach($ordering_array['order_by_machine'] as $order_by_machine)
		{
			if($i == 0)
			{
				$this->aps->where($order_by_machine->criteria , $oa_value);
			}
			$i++;
		}

		$this->aps->order_by('critical_ratio', 'ASC');	
		$this->aps->order_by('tvardo_plan', 'DESC');
		
		foreach($ordering_array['order_by_machine'] as $order_by_machine)
		{
			$this->aps->order_by('porachki_temp_simulation.' . $order_by_machine->criteria , $order_by_machine->ordering);
		}
		
		$this->aps->order_by('poreden_no', 'ASC');	
		
		$result = $this->aps->select('*')
		->from('porachki_temp_simulation')
		->where('porachki_temp_simulation.mashina', $mashine)
		->get()->result();
		
		return $result;
	}
	
	
	
	function search_planned_and_orders_by_kriteria_NEW_group_always($UID, $mashine, $ordering_array)
	{
		$this->aps->where('UID', $UID);

		foreach($ordering_array['order_by_machine'] as $order_by_machine)
		{
			$this->aps->order_by('porachki_temp.' . $order_by_machine->criteria , $order_by_machine->ordering);
		}
		
		$this->aps->order_by('poreden_no', 'ASC');	
		
		$result = $this->aps->select('*')
		->from('porachki_temp')
		->where('porachki_temp.mashina', $mashine)
		->get()->result();
		
		return $result;
	}
	
	function search_planned_and_orders_by_kriteria_NEW_group_alwaysSIMULATION3($UID, $mashine, $ordering_array)
	{
		$this->aps->where('UID', $UID);

		foreach($ordering_array['order_by_machine'] as $order_by_machine)
		{
			$this->aps->order_by('porachki_temp_simulation.' . $order_by_machine->criteria , $order_by_machine->ordering);
		}
		
		$this->aps->order_by('poreden_no', 'ASC');	
		
		$result = $this->aps->select('*')
		->from('porachki_temp_simulation')
		->where('porachki_temp_simulation.mashina', $mashine)
		->get()->result();
		
		return $result;
	}
	
	
	
	function get_first_for_execution_from_marshrut($porachka)
	{
		$this->aps = $this->load->database('aps', TRUE);	
		$this->aps->where('porachka', $porachka);
		$this->aps->order_by('poreden_no', 'DESC');
		$result = $this->aps->get('planned')->result();
		
		$i = 0;
		$nomer=0;
		$nomer2=0;
		$flag = 0;
		$hand_operations_waiting_for = array(390, 670, 680);
		foreach($result as $res)
		{
			if($res->izpalneno == 1 && $flag == 1)
			{
				$i=0;
				$nomer2 = 0;
				break;
			}
			
			if($res->izpalneno == 0 && $nomer2 == 0)
			{
				$nomer=$i;
			}
			
			if($res->izpalneno == 1)
			{
				if($flag == 1)
				{
					$i=0;
					$nomer2 = 0;
				}else
					$nomer2=$nomer;
				
				break;
			}
			
			if($res->izpalneno == 1 && $res->next_oper == 0)
			{
				$i=0;
				$nomer2 = 0;
				break;
			}
			
			if($res->mashina != ' ' && $res->izpalneno == 0)
			{
				$flag = 2;
			}
			
			if(in_array($res->operation, $hand_operations_waiting_for))
			{
				$flag = 2;
			}
			
			if($res->mashina == ' ' && $flag != 2)
			{
				$flag = 1;
			}
		
			$i++;
		}
		
		if($nomer2 == 0)
		{
			$nomer2=$i-1;
		}

		return $result[$nomer2];
	}
	
	function get_planned_and_orders_by_order2($UID, $mashine, $ordering_array)
	{
		$this->aps->where('UID', $UID);
		//$this->aps->where_in('temp_id',  $id_arr);
		foreach($ordering_array['order_by_machine'] as $order_by_machine)
		{
			$this->aps->order_by('porachki_temp.' . $order_by_machine->criteria , $order_by_machine->ordering);
		}
		
		// GLOBAL KOEFICIENT K
		//$this->aps->where('critical_ratio < ', 10);
		
		$this->aps->order_by('critical_ratio' , 'ASC');
		$this->aps->order_by('poreden_no', 'ASC');	
		
		$result = $this->aps->select('*')
		->from('porachki_temp')
		->where('porachki_temp.mashina', $mashine)
		->get()->result();
		
		return $result;
	}

	function get_planned_and_orders_by_order3($UID, $mashine, $ordering_array)
	{
		$this->aps->where('UID', $UID);
		
		$this->aps->order_by('critical_ratio' , 'ASC');
		$this->aps->order_by('poreden_no', 'ASC');	
		
		$result = $this->aps->select('*')
		->from('porachki_temp')
		->where('porachki_temp.mashina', $mashine)
		->get()->result();
		
		return $result;
	}
	
	function calculate_mashine_zaetost($mashina)
	{
		$result = $this->aps->select('SUM(vreme_rab) AS sum_of_time')->from('planned')->join('porachki', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.mashina', $mashina)
		->get()->row();
		
		return $result;
	}

	function calculate_mashine_zaetost_queue($mashina)
	{
		$result = $this->aps->select('planned.porachka')->from('planned')->join('porachki', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $mashina)
		->get()->result();
		$total_time =0;
		foreach($result as $r)
		{
			$result2 = $this->aps->select('SUM(vreme_rab) AS sum_of_time')->from('planned')->join('porachki', 'porachki.porachka=planned.porachka')
			->where('porachki.active', 1)
			->where('planned.porachka', $r->porachka)
			->where('planned.mashina', $mashina)
			->get()->row();
			
			$total_time  = $total_time + $result2->sum_of_time . '<br>';
		}
		
		return $total_time;
	}

	function future_operations_by_order($order)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('izpalneno', 0);
		$result = $this->aps->get('planned')->result();
		
		return $result;
	}
	
	function future_operations_by_order_SIMULATION3($order)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('izpalneno', 0);
		$result = $this->aps->get('planned_simulation')->result();
		
		return $result;
	}
	
	
	
	function next_operation_by_order($order)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('izpalneno', 0);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->get('planned')->row();
		
		return $result;
	}
	
	function next_operation_by_order_new($order)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('izpalneno', 0);
		$this->aps->where('last_oper', 1);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->get('planned')->row();
		
		return $result;
	}
	
	//при успоредни операции
	function next_operation_by_order_array($order)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('izpalneno', 0);
		$this->aps->where('last_oper', 1);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->get('planned')->result();
		
		return $result;
	}
	
	
	function next_print_operation_by_order($order)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('mashina != ', '111');
		$this->aps->where('izpalneno', 0);
		$this->aps->where('last_oper', 1);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->get('planned')->row();
		
		return $result;
	}
	function next_print_operation_by_order_T2($order)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('mashina != ', '111');
		$this->aps->where('izpalneno', 0);
		$this->aps->where('last_oper', 0);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->get('planned')->row();
		
		return $result;
	}
	
	
	function get_first_print_operation_by_order($order)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('mashina != ', '111');
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->get('planned')->row();
		
		return $result;
	}
	
	function get_orders_in_plan_by_shift_machine($mashina, $smiana_id)
	{
		$this->aps->where('mashina', $mashina);
		$this->aps->where('smiana_id', $smiana_id);
		$this->aps->where('izpalneno', 0);
		$this->aps->order_by('nachalo_pred', 'ASC');
		$result = $this->aps->get('planned')->result();
		
		return $result;
	}
	
	function get_orders_in_plan_new($mashina, $begin = 0 , $end = 0)
	{
		$this->aps->where('mashina', $mashina);
		$this->aps->where('nachalo_pred > ', $begin);
		$this->aps->where('nachalo_pred < ', $end);
		$this->aps->where('krai_pred != ', 0);
		$this->aps->where('izpalneno', 0);
		$this->aps->order_by('nachalo_pred', 'ASC');
		$result = $this->aps->get('planned')->result();
		
		return $result;
	}
	
	function get_prev_planned_operation($order, $machine, $poreden_no)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('mashina != ', $machine);
		$this->aps->where('poreden_no < ', $poreden_no);
		//$this->aps->where('izpalneno', 0);
		$result = $this->aps->get('planned')->result();
		
		return $result;
	}
	
	function get_one_prev_planned_operation($order, $machine, $poreden_no)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('mashina != ', $machine);
		$this->aps->where('poreden_no < ', $poreden_no);
		$this->aps->order_by('poreden_no', 'DESC');
		//$this->aps->where('izpalneno', 0);
		$result = $this->aps->get('planned')->row();
		
		return $result;
	}
	
	function get_one_prev_unfinished_planned_operation($order, $machine, $poreden_no)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('mashina != ', $machine);
		$this->aps->where('poreden_no < ', $poreden_no);
		$this->aps->order_by('poreden_no', 'DESC');
		$this->aps->where('izpalneno', 0);
		//$this->aps->where('tvardo_plan', 1);
		//$this->aps->where('last_oper', 1);
		$result = $this->aps->get('planned')->row();
		
		return $result;
	}
	
	
	
	function get_prev_planned_operation_simulation($order, $machine, $poreden_no)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('mashina != ', $machine);
		$this->aps->where('poreden_no < ', $poreden_no);
		//$this->aps->where('izpalneno', 0);
		$result = $this->aps->get('planned_simulation')->result();
		
		return $result;
	}
	
	function count_temp($UID)
	{
		$this->aps->where('UID', $UID);
		$br = $this->aps->select('temp_id')->from('porachki_temp')->count_all_results();
		
		return $br;
	}
	
	function count_temp_SIMULATION3($UID)
	{
		$this->aps->where('UID', $UID);
		$br = $this->aps->select('temp_id')->from('porachki_temp_simulation')->count_all_results();
		
		return $br;
	}
	
	function select_orders_operations_by_order($order, $order_by = 'ASC')
	{
		$this->aps->where('porachka', $order);
		$this->aps->order_by('poreden_no', $order_by);
		$result = $this->aps->get('planned')->result();
		
		return $result;
	}
	function select_orders_operations_by_order2($order)
	{
		$this->aps->where('porachka', $order);
		$this->aps->order_by('kanban', 'ASC');
		$result = $this->aps->get('planned')->result();
		
		return $result;
	}
	function select_orders_operations_by_order3($order, $poreden_no)
	{
		$this->aps->where('porachka', $order);
		$this->aps->where('poreden_no >= ', $poreden_no);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->get('planned')->result();
		
		return $result;
	}
	
	function get_resource_callendar_by_machine($mashine)
	{
		$this->aps->where('machine', $mashine);
		$this->aps->where('active', 1);
		$this->aps->order_by('date', 'ASC');
		$this->aps->order_by('begin', 'ASC');
		
		$result = $this->aps->get('resource_callendar')->result();
		
		return $result;
	
	}
	
	function get_resource_callendar_by_machine_limit($mashine, $begin_time, $limit)
	{
		$this->aps->where('machine', $mashine);
		$this->aps->where('begin > ', $begin_time );
		$this->aps->where('active', 1);
		$this->aps->order_by('date', 'ASC');
		$this->aps->order_by('begin', 'ASC');
		$this->aps->limit($limit);
		
		$result = $this->aps->get('resource_callendar')->result();
		
		return $result;
	
	}
	
	function get_resource_callendar_by_machine_limit3($mashine, $begin_time, $limit)
	{
		$this->aps->where('machine', $mashine);
		$this->aps->where( 'b_planned <= ', $begin_time );
		$this->aps->where('end > ', $begin_time );
		$this->aps->where('begin < ', $begin_time );
		$this->aps->where('active', 1);
		$this->aps->order_by('begin', 'ASC');
		$this->aps->limit($limit);
		
		$result = $this->aps->get('resource_callendar')->result();
		/*
		$this->aps->query( "SELECT * FROM (`zd_resource_callendar`) 
		WHERE `machine` = '$mashine' AND ( ( `b_planned` <= $begin_time AND `end` > $begin_time ) OR `b_planned` >= $begin_time  ) AND `active` = 1 
		ORDER BY `begin` ASC LIMIT $limit ")->result();
		*/
		return $result;
	
	}
	
	function get_resource_callendar_by_machine_limit3a($mashine, $begin_time, $limit)
	{
		$this->aps->where('machine', $mashine);
		$this->aps->where( 'b_planned >= ', $begin_time );
		$this->aps->where('active', 1);
		$this->aps->order_by('begin', 'ASC');
		$this->aps->limit($limit);
		
		$result = $this->aps->get('resource_callendar')->result();
		
		return $result;
	
	}
	
	function get_resource_callendar_by_machine_limit2($mashine, $begin_time)
	{
		$this->aps->where('machine', $mashine);
		$this->aps->where('b_planned <= ', $begin_time );
		$this->aps->where('end > ', $begin_time );
		$this->aps->where('active', 1);
		$this->aps->order_by('date', 'ASC');
		$this->aps->order_by('begin', 'ASC');
		
		$result = $this->aps->get('resource_callendar')->row();
		
		return $result;
	
	}
	
	
	function get_shift_by_machine($mashine, $time)
	{
		$this->aps->where('machine', $mashine);
		$this->aps->where('begin <= ', $time );
		$this->aps->where('end > ', $time );
		$this->aps->where('active', 1);
		$this->aps->order_by('date', 'ASC');
		$this->aps->order_by('begin', 'ASC');
		
		$result = $this->aps->get('resource_callendar')->row();
		
		return $result;
	
	}
	
	function get_shifts_by_machine_limit($mashine, $begin_time, $limit)
	{
		$this->aps->where('machine', $mashine);
		$this->aps->where( 'begin >= ', $begin_time );
		$this->aps->where( 'begin >', 0 );
		$this->aps->where('active', 1);
		$this->aps->order_by('date', 'ASC');
		$this->aps->order_by('begin', 'ASC');
		$this->aps->limit($limit);
		
		$result = $this->aps->get('resource_callendar')->result();
		
		return $result;
	
	}
	
	
	
	
	
	
	
	/********************************************************************
	BAAN functions
	********************************************************************/
	
	//
	function get_name_by_trz($nomer)
	{
		$this->aps->where('nomer', $nomer);
		$result = $this->aps->get('trz')->row();
		
		return $result;
	}
	
	function get_name_by_trz_from_oracle($nomer)
	{
		$result = $this->oracle->query('SELECT T$NAMA FROM baandb.ttccom001601 WHERE T$EMNO = ' . $nomer);

		return $result->result_array;
	}
	
	function get_dsca_from_oracle($nomer)
	{
		if (strlen($nomer) == 1) $nomer = "SFC00000" . $nomer;
		if (strlen($nomer) == 2) $nomer = "SFC0000" . $nomer;
		if (strlen($nomer) == 3) $nomer = "SFC000" . $nomer;
		if (strlen($nomer) == 4) $nomer = "SFC00" . $nomer;
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		$result = $this->oracle->query(' SELECT T$CPRJ, T$MITM FROM baandb.ttisfc001601 where T$PDNO = \''.$nomer.'\' ' );
		
		return $result->result_array;
	}
	
	function get_name_from_oracle($nomer)
	{
		//if ($nomer != '')
		{
			$result = $this->oracle->query(' SELECT T$DSCA, T$DSCB, T$DSCC FROM baandb.ttcibd001601 where  T$ITEM = \''.$nomer.'\' ' );
		
			return $result->result_array;
		}
	}
	
	function get_ttisfc001_from_oracle($nomer)
	{
		if (strlen($nomer) == 1) $nomer = "SFC00000" . $nomer;
		if (strlen($nomer) == 2) $nomer = "SFC0000" . $nomer;
		if (strlen($nomer) == 3) $nomer = "SFC000" . $nomer;
		if (strlen($nomer) == 4) $nomer = "SFC00" . $nomer;
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		$result = $this->oracle->query(' SELECT * FROM baandb.ttisfc001601 where T$PDNO LIKE \'%'.$nomer.'%\' ' );
		
		return $result->result_array;
	}
	
	function get_ttisfc001_from_oracle_NEW($nomer)
	{
		if (strlen($nomer) == 1) $nomer = "SFC00000" . $nomer;
		if (strlen($nomer) == 2) $nomer = "SFC0000" . $nomer;
		if (strlen($nomer) == 3) $nomer = "SFC000" . $nomer;
		if (strlen($nomer) == 4) $nomer = "SFC00" . $nomer;
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		$result = $this->oracle->query(' SELECT * FROM baandb.ttisfc001601 where T$PDNO = \''.$nomer.'\' ' );
		
		return $result->result_array;
	}
	
	function get_ttisfc001_by_item($item)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttisfc001601 where T$MITM = \''.$item.'\' ' );
		
		return $result->result_array;
	}
	
	
	function ex2()
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttcibd001601 WHERE T$ITEM LIKE \'PCS%\' AND T$ITEM NOT IN (SELECT T$MITM FROM baandb.ttisfc001601) AND rownum <= 300 ORDER BY T$LMDT DESC ' );
		
		return $result->result_array;
	}
	
	function get_ttipcs020_from_oracle($cprj)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttipcs020601 where T$CPRJ = \''.$cprj.'\' ' );
		
		return $result->result_array;
	}
	
	function get_tcibd001_all_v2()
	{
		$result = $this->oracle->query(' SELECT T$ITEM FROM baandb.ttcibd001601 WHERE T$ITEM LIKE \'PCS%\' AND rownum <= 500 ORDER BY T$LMDT DESC ' );
		
		return $result->result_array;
	}
	
	function get_ttccom100_from_oracle($bpid)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttccom100601 where T$BPID = \''.$bpid.'\' ' );
		
		return $result->result_array;
	}
	
	function get_ttisfc010_from_oracle($nomer)
	{
		if (strlen($nomer) == 1) $nomer = "SFC00000" . $nomer;
		if (strlen($nomer) == 2) $nomer = "SFC0000" . $nomer;
		if (strlen($nomer) == 3) $nomer = "SFC000" . $nomer;
		if (strlen($nomer) == 4) $nomer = "SFC00" . $nomer;
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		//FF$result = $this->oracle->query(' SELECT * FROM baandb.ttisfc010601 where T$PDNO LIKE \'%'.$nomer.'%\' ' );
		
		$result = $this->oracle->query(' SELECT * FROM baandb.ttisfc010601 where T$PDNO = \''.$nomer.'\' ORDER BY T$OPNO ASC ' );
		
		return $result->result_array;
	}
	
	function get_ttisfc010_from_oracle_fast($nomer)
	{
		if (strlen($nomer) == 1) $nomer = "SFC00000" . $nomer;
		if (strlen($nomer) == 2) $nomer = "SFC0000" . $nomer;
		if (strlen($nomer) == 3) $nomer = "SFC000" . $nomer;
		if (strlen($nomer) == 4) $nomer = "SFC00" . $nomer;
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		$result = $this->oracle->query(' SELECT T$TANO, T$MCNO, T$OPNO FROM baandb.ttisfc010601 where T$PDNO = \''.$nomer.'\' ' );
		
		return $result->result_array;
	}
	
	function fuzzy_update_marshrut($arr_1_m, $arr_2_m, $arr_1_o, $arr_2_o, $porachka )
	{
		//$this->tm = $this->load->database('tm', TRUE);
		$i = 0;
		foreach($arr_1_o as $arr)
		{	
			$this->tm->where('operation', $arr_1_o[$i]);
			$this->tm->where('mashina', $arr_1_m[$i]);
			$this->tm->where('porachka', $porachka);
			$this->tm->where('izpalneno', 0);
			$this->tm->order_by('id', 'DESC');
			$result = $this->tm->get('marshrut')->row();

			$a = $this->get_element_from_vremena($porachka, $arr_2_m[$i], $arr_2_o[$i]);
			
			if (count($a) == 1 && count($result) == 1)
			{
				$save2 = array();
				$save2['izpalneno'] = 1;
				$this->tm->where('id', $result->id);
				$this->tm->update('marshrut', $save2);
			}
			
			///////////////////////////////// 2 PASSE
			
			$this->tm->where('operation', $arr_2_o[$i]);
			$this->tm->where('mashina', $arr_2_m[$i]);
			$this->tm->where('porachka', $porachka);
			$this->tm->where('izpalneno', 0);
			$this->tm->order_by('id', 'ASC');
			$result = $this->tm->get('marshrut')->row();

			$a = $this->get_element_from_vremena($porachka, $arr_1_m[$i], $arr_1_o[$i]);
			
			if (count($a) == 1 && count($result) == 1)
			{
				$save2 = array();
				$save2['izpalneno'] = 1;
				$this->tm->where('id', $result->id);
				$this->tm->update('marshrut', $save2);
			}
			
			$i++;
		}
	}
	
	function get_max_porachka()
	{
		$this->aps->select_max('porachka',  'max_porachka');
		$result = $this->aps->get('porachki');
		
		return $result->result();
	}
	
	function get_ticst001_Karton($porachka)
	{
		
		
		//AND rownum <= 300
		$result = $this->oracle->query(' SELECT T$PDNO, T$SITM , T$QUNE  FROM baandb.tticst001601 where ( 
		T$SITM LIKE \'         F402____\' OR 
		T$SITM LIKE \'         F115____\' OR 
		T$SITM LIKE \'         01______\' OR 
		T$SITM LIKE \'         91______\' ) 
		AND T$PDNO LIKE \'%'.$porachka.'\' ' );
		
		return $result->result_array;
	}
	
	function get_ticst001_Karton_NEW($porachka)
	{
		
		
		//AND rownum <= 300
		$result = $this->oracle->query(' SELECT T$PDNO, T$SITM , T$QUNE, T$QUCS, T$ISSU, baandb.ttcibd001601.*  FROM baandb.tticst001601
		INNER JOIN baandb.ttcibd001601 ON baandb.ttcibd001601.T$ITEM = baandb.tticst001601.T$SITM
		where ( 
		T$SITM LIKE \'         F402____\' OR 
		T$SITM LIKE \'         F115____\' OR 
		T$SITM LIKE \'         01______\' OR 
		T$SITM LIKE \'         91______\' ) 
		AND T$PDNO LIKE \'%'.$porachka.'\' ' );
		
		return $result->result_array;
	}
	
	
	
	
	function get_ticst001($porachka, $order_by, $asc_desc = 'ASC')
	{
		//get_name_from_oracle($nomer)   //ORDER BY ' . $order_by . ' ' . $asc_desc );
		// SELECT T$DSCA, T$DSCB, T$DSCC FROM baandb.ttcibd001601 where  T$ITEM  
		//AND rownum <= 300
		$result = $this->oracle->query(' SELECT * FROM baandb.tticst001601 INNER JOIN baandb.ttcibd001601 ON baandb.ttcibd001601.T$ITEM = baandb.tticst001601.T$SITM WHERE baandb.tticst001601.T$PDNO LIKE \'%'.$porachka.'\' ORDER BY ' . $order_by . ' ' . $asc_desc );
		
		
		
		return $result->result_array;
	}
	
	
	function get_ticst001_V2($item)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.tticst001601 WHERE baandb.tticst001601.T$SITM LIKE \'%'.$item.'\' ' );

		return $result->result_array;
	}
	
	function get_ticst001_mix_station($porachka)
	{
		
		$result = $this->oracle->query(' SELECT * FROM baandb.tticst001601 INNER JOIN baandb.ttcibd001601 ON baandb.ttcibd001601.T$ITEM = baandb.tticst001601.T$SITM WHERE ( 
		T$SITM LIKE \'         F402____\' OR 
		T$SITM LIKE \'         F115____\' OR 
		T$SITM LIKE \'         0207____\' OR 
		T$SITM LIKE \'         0299____\' ) 
		AND baandb.tticst001601.T$PDNO LIKE \'%'.$porachka.'\' ');

		return $result->result_array;
		
	}
	
	
	function get_whinp100( $object_infor, $KOOR, $KOTR)
	{
		//AND rownum <= 300
		$result = $this->oracle->query(' SELECT TO_CHAR(baandb.twhinp100601.T$DATE, \'DD-MON-YYYY HH24:MI:SS\') as DATE_PLAN, baandb.twhinp100601.* FROM baandb.twhinp100601 WHERE 
		T$ITEM LIKE \'%'.$object_infor.'\' 
		AND T$KOOR = \''.$KOOR.'\' 
		AND T$KOTR = \''.$KOTR.'\' ORDER BY T$ORNO ASC  ');
		
		return $result->result_array;
	}
	
	function get_whinp100_C( $object_infor, $KOOR, $KOTR, $order_no)
	{
		if (strlen($order_no) == 1) $order_no = "SFC00000" . $order_no;
		if (strlen($order_no) == 2) $order_no = "SFC0000" . $order_no;
		if (strlen($order_no) == 3) $order_no = "SFC000" . $order_no;
		if (strlen($order_no) == 4) $order_no = "SFC00" . $order_no;
		if (strlen($order_no) == 5) $order_no = "SFC0" . $order_no;
		if (strlen($order_no) == 6) $order_no = "SFC" . $order_no;
		
		//AND rownum <= 300
		$result = $this->oracle->query(' SELECT baandb.twhinp100601.* FROM baandb.twhinp100601 WHERE 
		T$ITEM LIKE \'%'.$object_infor.'\' 
		AND T$KOOR = \''.$KOOR.'\' 
		AND T$ORNO = \''.$order_no.'\' 
		AND T$KOTR = \''.$KOTR.'\' ');
		
		return $result->result_array;
	}
	
	
	function get_tccom960($trz, $begin_string, $end_string)
	{
		//AND rownum <= 300
		/*
		select baandb.ttccom960601.*, TO_CHAR(baandb.ttccom960601.T$HRDTS, 'DD-MON-YYYY HH24:MI:SS') as DBEG,
		TO_CHAR(baandb.ttccom960601.T$HRDTE, 'DD-MON-YYYY HH24:MI:SS') as DEND 
		FROM baandb.ttccom960601 
		WHERE T$EMNO = '977' AND ( T$HRDTS <= '31-JAN-18' AND T$HRDTE >= '01-JAN-18')
		ORDER BY baandb.ttccom960601.T$HRDTS DESC
		*/
		
		$result = $this->oracle->query('SELECT baandb.ttccom960601.*, TO_CHAR(baandb.ttccom960601.T$HRDTS, \'DD-MON-YYYY HH24:MI:SS\') as DBEG,
		TO_CHAR(baandb.ttccom960601.T$HRDTE, \'DD-MON-YYYY HH24:MI:SS\') as DEND
		FROM baandb.ttccom960601 
		WHERE 
		T$EMNO = \''.$trz.'\' 
		AND ( T$HRDTS <= \''.$end_string.'\' AND T$HRDTE >= \''.$begin_string.'\' )
		');
		
		return $result->result_array;
	}
	
	function get_tccom960_V2($begin_string, $end_string)
	{
		$result = $this->oracle->query('SELECT baandb.ttccom960601.*, TO_CHAR(baandb.ttccom960601.T$HRDTS, \'DD-MON-YYYY HH24:MI:SS\') as DBEG,
		TO_CHAR(baandb.ttccom960601.T$HRDTE, \'DD-MON-YYYY HH24:MI:SS\') as DEND
		FROM baandb.ttccom960601 
		WHERE 
		T$HRDTS <= \''.$end_string.'\' AND T$HRDTE >= \''.$begin_string.'\'
		');
		
		return $result->result_array;
	}
	
	
	
	
	function get_whwmd216($object_infor)
	{
		//AND rownum <= 300
		$result = $this->oracle->query(' SELECT * FROM baandb.twhwmd216601 WHERE  
		T$ITEM LIKE \'%'.$object_infor.'\' ');
		
		return $result->result_array;
	}
	
	function get_whinp100_B($object_infor)
	{
		//AND rownum <= 300
		$result = $this->oracle->query(' SELECT * FROM baandb.twhinp100601 WHERE  
		T$ITEM LIKE \'%'.$object_infor.'\' ORDER BY T$DATE ASC ');
		
		return $result->result_array;
	}
	
	
	function select_planned_zaetost_smeni($smiana_id, $planned_id)
	{
		$result = $this->aps->select('zaetost.*, planned.*, resource_callendar')->from('zaetost')
		->join('planned', 'zaetost.planned_id=planned.id')->join('resource_callendar', 'zaetost.smiana_id=resource_callendar.id')
		->where('resource_callendar.id', 0)
		->where('planned.nachalo_pred', 0)
	
		->order_by('planned.porachka', 'ASC')
		->order_by('poreden_no', 'ASC')
		
		->get()->result();
		
		return $result;
	}
	
	
	function select_planned_zaetost_smeni_1($smiana_id, $planned_id)
	{
		$result = $this->aps->select('zaetost.*, planned.*, resource_callendar')->from('zaetost')
		->join('planned', 'zaetost.planned_id=planned.id')->join('resource_callendar', 'zaetost.smiana_id=resource_callendar.id')
		->where(array('zaetost.smiana_id' => $smiana_id))
		
		
		->get()->result();
		
		return $result;
	}
	
	
	function select_orders_operations_kanban($machina)
	{
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('planned.izpalneno', 0)
		->where('planned.mashina', $machina)
		->where( 'planned.date_plan <= ', time() )
		->order_by('planned.immediately', 'DESC')
		->order_by('porachki.critical_ratio', 'ASC')
		->order_by('planned.porachka', 'ASC')
		->order_by('poreden_no', 'ASC')
		->get()->row();
		
		return $result;
	}
	
	
	
	function select_orders_operations2($machina, $limit, $table = 'planned', $tekushto_vreme)
	{
		$result = $this->aps->select($table.'.*, porachki.*')->from($table)->join('porachki', 'porachki.porachka='.$table.'.porachka')
		->where('porachki.active', 1)
		->where($table.'.izpalneno', 0)
		->where($table.'.mashina', $machina)
		->where($table.'.otkaz', NULL)
		->where($table.'.last_oper', 1)
		->where( $table.'.date_plan <= ', $tekushto_vreme )
		->where( $table.'.wait_until <= ', $tekushto_vreme )
		->order_by($table.'.immediately', 'DESC')
		->order_by('porachki.critical_ratio', 'ASC')
		->order_by($table.'.porachka', 'ASC')
		->order_by('poreden_no', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	function select_orders_operations2_Priority2($machina, $limit, $table = 'planned', $tekushto_vreme)
	{
		$m_A = $this->get_mashina_by_nomer($machina);
		if($m_A->ins_between_tvp == 1)
			$this->aps->order_by($table.'.tvardo_plan', 'DESC');
		$result = $this->aps->select($table.'.*, porachki.*')->from($table)->join('porachki', 'porachki.porachka='.$table.'.porachka')
		->where('porachki.active', 1)
		->where($table.'.izpalneno', 0)
		->where($table.'.mashina', $machina)
		->where($table.'.otkaz', NULL)
		->where($table.'.last_oper', 1)
		->where( $table.'.date_plan <= ', $tekushto_vreme )
		->where( $table.'.wait_until <= ', $tekushto_vreme )
		->order_by($table.'.immediately', 'DESC')
		->order_by('porachki.critical_ratio', 'ASC')
		->order_by($table.'.porachka', 'ASC')
		->order_by('poreden_no', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	function count_select_orders_operations2($machina, $table = 'planned', $tekushto_vreme)
	{
		$result = $this->aps->select('id')->from($table)
		->where( $table.'.izpalneno', 0)
		->where( $table.'.mashina', $machina)
		->where( $table.'.otkaz', NULL)
		->where( $table.'.last_oper', 1)
		->where( $table.'.date_plan <= ', $tekushto_vreme )
		->where( $table.'.wait_until <= ', $tekushto_vreme )
		->count_all_results();
		
		return $result;
	}
	
	function select_orders_operations2_3_markers($machina, $limit, $table = 'planned', $tekushto_vreme)
	{
		$result = $this->aps->select($table.'.*, porachki.*')->from($table)->join('porachki', 'porachki.porachka='.$table.'.porachka')
		->where($table.'.izpalneno', 0)
		->where($table.'.mashina', $machina)
		->where($table.'.otkaz', NULL)
		->where($table.'.last_oper', 1)
		->where('porachki.marker1', 1)
		->where('porachki.marker2', 1)
		->where('porachki.marker3', 1)
		->where('porachki.active', 1)
		->where( $table.'.wait_until <= ', $tekushto_vreme )
		->order_by($table.'.immediately', 'DESC')
		->order_by('porachki.critical_ratio', 'ASC')
		->order_by($table.'.porachka', 'ASC')
		->order_by('poreden_no', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	
	
	function select_orders_operations_fast($machina, $limit)
	{
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.otkaz', NULL)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $machina)
		//->where('planned.kanban > ', 0)
		->where('planned.wait_until <= ', time() )
		//->or_where('planned.immediately', 1 )
		->order_by('planned.immediately', 'DESC')
		->order_by('planned.kanban', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	function select_orders_operations_fast_SIMULATION3($machina, $limit, $tekushto_vreme)
	{
		$result = $this->aps->select('porachki_simulation.*, planned_simulation.*')->from('porachki_simulation')->join('planned_simulation', 'porachki_simulation.porachka=planned_simulation.porachka')
		->where('porachki_simulation.active', 1)
		->where('planned_simulation.izpalneno', 0)
		->where('planned_simulation.otkaz', NULL)
		->where('planned_simulation.last_oper', 1)
		->where('planned_simulation.mashina', $machina)
		->where('planned_simulation.wait_until <= ', $tekushto_vreme )
		->order_by('planned_simulation.immediately', 'DESC')
		->order_by('planned_simulation.kanban', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	
	function select_orders_operations_fast_3_markers_SIMULATION3($machina, $limit, $tekushto_vreme)
	{
		$result = $this->aps->select('porachki_simulation.*, planned_simulation.*')->from('porachki_simulation')->join('planned_simulation', 'porachki_simulation.porachka=planned_simulation.porachka')
		->where('planned_simulation.izpalneno', 0)
		->where('planned_simulation.otkaz', NULL)
		->where('planned_simulation.last_oper', 1)
		->where('planned_simulation.mashina', $machina)
		//FF ->where('porachki.marker1', 1)
		//FF ->where('porachki.marker2', 1)
		//FF ->where('porachki.marker3', 1)
		->where('porachki_simulation.active', 1)
		->where('planned_simulation.wait_until <= ', $tekushto_vreme )
		->order_by('planned_simulation.immediately', 'DESC')
		->order_by('planned_simulation.kanban', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	function select_orders_operations_fast_3_markers($machina, $limit)
	{
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('planned.izpalneno', 0)
		->where('planned.otkaz', NULL)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $machina)
		->where('porachki.marker1', 1)
		->where('porachki.marker2', 1)
		->where('porachki.marker3', 1)
		->where('porachki.active', 1)
		->where('planned.wait_until <= ', time() )
		->order_by('planned.immediately', 'DESC')
		->order_by('planned.kanban', 'ASC')
		//->order_by('porachki.critical_ratio', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	function select_orders_operations_fast_PRESS($machina, $limit)
	{
		$this->aps->where('mashina', $machina);
		$this->aps->where('immediately != ', '');
		$br = $this->aps->get('planned')->row();
		
		if(count($br) > 0)
			$this->aps->where('planned.immediately != ', '');
		
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.otkaz', NULL)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $machina)
		->where('planned.kanban > ', 0)
		->where('planned.wait_until <= ', time() )
		->order_by('planned.immediately', 'DESC')
		->order_by('porachki.cv_array_front_order', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	function select_orders_operations_fast_PRESS_NEW($machina, $limit)
	{
		$this->aps->where('mashina', $machina);
		$this->aps->where('immediately != ', '');
		$br = $this->aps->get('planned')->row();
		
		if(count($br) > 0)
			$this->aps->where('planned.immediately != ', '');
		
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.otkaz', NULL)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $machina)
		//->where('planned.kanban > ', 0)
		->where('planned.wait_until <= ', time() )
		->order_by('planned.immediately', 'DESC')
		->order_by('planned.kanban', 'ASC')
		->order_by('porachki.cv_array_front_order', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	function select_orders_operations_fast_PRESS_3_markers($machina, $limit)
	{
		$this->aps->where('mashina', $machina);
		$this->aps->where('immediately != ', '');
		$br = $this->aps->get('planned')->row();
		
		if(count($br) > 0)
			$this->aps->where('planned.immediately != ', '');
		
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('planned.izpalneno', 0)
		->where('planned.otkaz', NULL)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $machina)
		->where('porachki.marker1 ', 1)
		->where('porachki.marker2 ', 1)
		->where('porachki.marker3 ', 1)
		->where('porachki.active', 1)
		->where('planned.wait_until <= ', time() )
		->order_by('planned.immediately', 'DESC')
		->order_by('porachki.cv_array_front_order', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	function select_orders_operations_fast_PRESS_NEW_SIMULATION3($machina, $limit, $tekushto_vreme)
	{
		$this->aps->where('mashina', $machina);
		$this->aps->where('immediately != ', '');
		$br = $this->aps->get('planned_simulation')->row();
		
		if(count($br) > 0)
			$this->aps->where('planned_simulation.immediately != ', '');
		
		$result = $this->aps->select('porachki_simulation.*, planned_simulation.*')->from('porachki_simulation')->join('planned_simulation', 'porachki_simulation.porachka=planned_simulation.porachka')
		->where('porachki_simulation.active', 1)
		->where('planned_simulation.izpalneno', 0)
		->where('planned_simulation.otkaz', NULL)
		->where('planned_simulation.last_oper', 1)
		->where('planned_simulation.mashina', $machina)
		->where('planned_simulation.wait_until <= ', $tekushto_vreme )
		->order_by('planned_simulation.immediately', 'DESC')
		->order_by('planned_simulation.kanban', 'ASC')
		->order_by('porachki_simulation.cv_array_front_order', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	function select_orders_operations_fast_PRESS_SIMULATION3($machina, $limit, $tekushto_vreme)
	{
		$this->aps->where('mashina', $machina);
		$this->aps->where('immediately != ', '');
		$br = $this->aps->get('planned_simulation')->row();
		
		if(count($br) > 0)
			$this->aps->where('planned_simulation.immediately != ', '');
		
		$result = $this->aps->select('porachki_simulation.*, planned_simulation.*')->from('porachki_simulation')->join('planned_simulation', 'porachki_simulation.porachka=planned_simulation.porachka')
		->where('planned_simulation.izpalneno', 0)
		->where('planned_simulation.last_oper', 1)
		->where('planned_simulation.mashina', $machina)
		->where('porachki_simulation.active', 1)
		->where('planned_simulation.wait_until <= ', $tekushto_vreme )
		->order_by('planned_simulation.immediately', 'DESC')
		->order_by('porachki_simulation.cv_array_front_order', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	function select_orders_operations_fast_PRESS_2($machina, $limit)
	{
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('planned.izpalneno', 0)
		->where('planned.otkaz', NULL)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $machina)
		->where('planned.kanban > ', 0)
		->order_by('planned.immediately', 'DESC')
		->order_by('porachki.cv_array_front_order', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	function select_orders_operations_fast_PRESS_2_NEW($machina, $limit)
	{
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.otkaz', NULL)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $machina)
		->where('planned.kanban > ', 0)
		->order_by('planned.immediately', 'DESC')
		->order_by('planned.kanban', 'ASC')
		//->order_by('porachki.cv_array_front_order', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	function select_orders_operations_fast_PRESS_2_3_markers($machina, $limit)
	{
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('planned.izpalneno', 0)
		->where('planned.otkaz', NULL)
		->where('planned.last_oper', 1)
		->where('planned.mashina', $machina)
		->where('porachki.marker1 ', 1)
		->where('porachki.marker2 ', 1)
		->where('porachki.marker3 ', 1)
		->where('planned.wait_until <= ', time() )
		->where('porachki.active', 1)
		->order_by('planned.immediately', 'DESC')
		->order_by('porachki.cv_array_front_order', 'ASC')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	
	function select_orders_3($machina, $limit)
	{
		$result = $this->aps->select('porachki.*, planned.*')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('planned.izpalneno', 0)
		->where('planned.mashina', $machina)
		->where( 'planned.date_plan <= ', time() )
		->order_by('porachki.critical_ratio', 'ASC')
		->order_by('planned.porachka', 'ASC')
		->order_by('poreden_no', 'ASC')
		->group_by('planned.porachka')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	function get_trz_from_vremena($nomer, $mashina =false)
	{
		$this->aps->where('trz', $nomer);
		if($mashina)
			$this->aps->where('mashina', $mashina);
		$this->aps->where('kolichestvo', '');
		$this->aps->where('krai_int', 0);
		$result = $this->aps->get('vremena')->row();
		
		return $result;
	}
	
	
	function get_trz_from_vremena_work($nomer)
	{		
		$this->aps->where('trz', $nomer);
		$this->aps->where('kolichestvo', '');
		$result = $this->aps->get('vremena')->row();
		
		return $result;
	}
	
	function get_marshrut_by_por_oper_new($porachka, $operation, $mashina)
	{
		$this->aps->where('izpalneno', 0);
		$this->aps->where('porachka', $porachka);
		$this->aps->where('operation', $operation);
		$this->aps->where('mashina', $mashina);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->limit(1)->offset(0)->get('planned')->result();
		
		return $result;
	}
	
	function get_marshrut_by_por_oper_new_simulation($porachka, $operation, $mashina)
	{
		$this->aps->where('izpalneno', 0);
		$this->aps->where('porachka', $porachka);
		$this->aps->where('operation', $operation);
		$this->aps->where('mashina', $mashina);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->limit(1)->offset(0)->get('planned_simulation')->result();
		
		return $result;
	}
	
	
	function get_marshrut_by_por_oper_new_planned($porachka, $mash, $poreden_no = 0)
	{
		$this->aps->where('izpalneno', 0);
		$this->aps->where('porachka', $porachka);
		$this->aps->where('mashina', $mash);
		$this->aps->where('poreden_no > ', $poreden_no);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->limit(1)->offset(0)->get('planned')->result();
		
		return $result;
	}
	
	function get_planned_by_orderLimit2($porachka)
	{
		$this->aps->where('izpalneno', 0);
		$this->aps->where('porachka', $porachka);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->limit(2)->offset(0)->get('planned')->result();
		
		return $result;
	}
	
	function get_marshrut_by_por_oper_new_planned_simulation($porachka)
	{
		$this->aps->where('izpalneno', 0);
		$this->aps->where('porachka', $porachka);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->limit(1)->offset(0)->get('planned_simulation')->result();
		
		return $result;
	}
	
	
	// За APS
	//вема списък с операциите
	function get_list_from_marshrut_for_aps_plan($porachka, $izpalneno = 0)
	{
		$this->aps->where('porachka', $porachka);
		if($izpalneno == 0)
			$this->aps->where('izpalneno', $izpalneno);
		$this->aps->order_by('poreden_no', 'ASC');
		$result = $this->aps->get('planned')->result();

		return $result;
	}
	
	
	
	function get_element_from_vremena_for_APS($porachka, $order)
	{
		$this->aps->where('porachka', $porachka);
		$this->aps->where('krai_int !=', 0);
		$this->aps->order_by('nachalo_int', $order);
		$result = $this->aps->get('vremena')->row();
		return $result;
	}
	
	function get_element_from_vremena($porachka, $mashina, $operation)
	{
		$this->aps->where('porachka', $porachka);
		//if($mashina != '' && $mashina != ' ')
		$this->aps->where('mashina', $mashina);
		$this->aps->where('operation', $operation);
		$this->aps->where('krai_int !=', 0);
		$result = $this->aps->get('vremena')->row();
		return $result;
	}
	
	function get_elements_from_vremena_for_APS($porachka)
	{
		$this->aps->where('porachka', $porachka);
		$this->aps->where('krai_int !=', 0);
		$this->aps->order_by('nachalo_int', $order);
		$result = $this->aps->get('vremena')->result();
		return $result;
	}
	
	
	function get_times_by_order($order)
	{
		$this->aps->where('porachka', $order);
		$this->aps->order_by('nachalo_int', $order);
		$result = $this->aps->get('vremena')->result();

		return $result;
	}
	
	
	function get_text_from_oracle($txt)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttttxt010601 WHERE T$CTXT = \''.$txt.'\' ' );
		$res2 = $result->result_array;

		foreach($res2 as $record)
		{
			$TEXT .= $record['T$TEXT'];
		}
		
		///echo $TEXT;
		
		return $TEXT;
	}
	
	
	
	function get_times_with_null_grupes($limit)
	{
		$this->aps->where('grupe', null);
		$result = $this->aps->limit($limit)->get('vremena')->result();
		return $result;
	}
	
	
	function search_vremena($term)
	{
		if ($term['trz'] != '')
			$this->aps->where('trz', $term['trz']);
		if ($term['porachka'] != '')
			$this->aps->where('porachka', $term['porachka']);
		if ($term['mashina'] != '')
			$this->aps->where('mashina', $term['mashina']);
		if ($term['operation'] != '')
			$this->aps->where('operation', $term['operation']);
		if ($term['date'] != '')
		{
			$date_int = strtotime($term['date']);
			$this->aps->where('nachalo_int >= ', $date_int);
		}
		
		if ($term['date2'] != '')
		{
			$date_int = strtotime($term['date2']);
			$this->aps->where('krai_int < ', $date_int);
		}
		
		if ($term['group_by'] != '')
			$this->aps->group_by($term['group_by']);
		
		if ($term['nozero'] != '')
			$this->aps->where('krai_int >', 0);
				
		$result = $this->aps->get('vremena')->result();
		
		return $result;
	}
	
	function search_porachka($term)
	{
		/*$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
			->where('planned.last_oper', 1)
			->where('planned.izpalneno', 0)
			->where('porachki.porachka LIKE ', '%'.$term.'%')
			->or_where('proekt LIKE ', '%'.$term.'%')
			->or_where('description LIKE ', '%'.$term.'%')
			->order_by('planned.poreden_no', 'ASC')
			->group_by('planned.porachka')
			->limit(100)
			->get()->result();
		*/
		
		$result1 = $this->aps->select('porachki.*, porachki.id AS order_id')->from('porachki')
			->where('porachki.porachka LIKE ', '%'.$term.'%')
			->order_by('porachki.porachka', 'DESC')
			->limit(100)
			->get()->result();		

		$result2 = $this->aps->select('porachki.*, porachki.id AS order_id')->from('porachki')
			->where('porachki.proekt LIKE ', '%'.$term.'%')
			->or_where('porachki.description LIKE ', '%'.$term.'%')
			->order_by('porachki.porachka', 'DESC')
			->limit(100)
			->get()->result();


		$result = array_merge($result1, $result2);
			//var_dump($result);
		
		/*
		if(count($result) == 0)
		{
			$result = $this->aps->select('porachki.*, porachki.id AS order_id')->from('porachki')
			->where('porachki.porachka LIKE ', '%'.$term.'%')
			->get()->result();
		}
		*/
		return $result;
	}
	
	function retrieve_information_schema($table)
	{
		$this->information_schema = $this->load->database('information_schema', TRUE);
		
		$this->information_schema->where('TABLE_SCHEMA', 'aps');
		$this->information_schema->where('TABLE_NAME', $table);
		
		$result = $this->information_schema->get('information_schema.COLUMNS')->result();
		
		return $result;
	}
	
	
	
	function search_like($table, $where, $text)
	{
		$this->aps->where($table . ' LIKE ', '%'.$text.'%');
		$result = $this->aps->get($table)->result();
		
		return $result;
	}
	
	
	function get_orders_with_exported_substrate($limit=100)
	{
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, vremena.*, vremena.id AS vremena_id')->from('porachki')->join('vremena', 'porachki.porachka=vremena.porachka')
		->where('porachki.active', 1)
		->where('porachki.marker3', 1)
		->where('vremena.operation', 260)
		->order_by('vremena.nachalo_int', 'DESC')
		->group_by('vremena.porachka')
		->limit($limit)
		->offset(0)
		->get()->result();
		
		return $result;
	}
	
	
	
	
	function get_label_id_by_customer_and_kod_izd($cus_id, $kod, $is_box)
	{
		if($kod != '5555')
			$this->aps->where('kod_izd', $kod);
		
		$this->aps->where('customer', $cus_id);
		$this->aps->where('is_box', $is_box);
		
		$result = $this->aps->get('label_customer')->row();
		
		if( count($result) == 0)
		{
			$this->aps->where('kod_izd', '5555');
			$this->aps->where('customer', $cus_id);
			$this->aps->where('is_box', $is_box);
			$result = $this->aps->get('label_customer')->row();
		}
		
		if( count($result) > 0)
		{
			$this->aps->where('id', $result->template_id);
			
			$result = $this->aps->get('label_templates')->row();
		}

		return $result;
	}
	
	
	function organize_operations($order_operations)
	{
		//now loop through the operations and update them
		$kanban = 1;
		foreach ($order_operations as $operation_id)
		{
			$operation = $this->get_element_by_prizn('planned', 'id', $operation_id);
			
			if($operation->tvardo_plan == 1)
			{
				$this->aps->where('id', $operation_id);
				$this->aps->update('planned', array('kanban'=>$kanban));
			
				$kanban = $kanban + 1;
			}else
			{
				//$this->aps->where('id', $operation_id);
				//$this->aps->update('planned', array('kanban'=>1000));
			}
		}
	}
	
	function get_unfinished_vremena()
	{	
		$this->aps->where('krai_int', 0);
		$result = $this->aps->get('vremena')->result();
		
		return $result;
	}
	
	
	function fuzzy_change_planned($arr_1_m, $arr_2_m, $arr_1_o, $arr_2_o, $porachka)
	{
		$array_machines = array();
		$i = 0;
		foreach($arr_1_o as $arr)
		{	
			$this->aps->where('operation', $arr_1_o[$i]);
			$this->aps->where('mashina', $arr_1_m[$i]);
			$this->aps->where('porachka', $porachka);
			
			//$this->aps->where('izpalneno', 0);
			$planned_row = $this->aps->get('planned')->row();
			
			
			
			if (count($planned_row) == 1)
			{
				$save2 = array();
				$save2['operation'] 	= $arr_2_o[$i];
				$save2['mashina'] 		= $arr_2_m[$i];
				
				$mas_info = $this->get_mashina_by_nomer($save2['mashina']);
				$save2['mashina_name'] 		= $mas_info->name;
				
				$oper_info = $this->get_operation_by_nomer( $save2['operation'] );
				$save2['operation_name'] 		= $oper_info->name;
				
				$this->aps->where('id', $planned_row->id);
				$this->aps->update('planned', $save2);
				
				
				//if machine is 441, add operation 318, 1306
				if($planned_row->mashina != '')
				{
					$array_machines[] = $planned_row->mashina;
					$last_machine = count($array_machines);
				}
				$nom_m = $last_machine-2;
				if($nom_m < 0) $nom_m = 0;

				if($arr_1_m[$i] == 441 && $array_machines[$nom_m] == 403)
				{
					$save2 = array();
					$save2['porachka'] 		= $porachka;
					$save2['is_prepare'] 	= 1;
					$save2['poreden_no'] 	= $planned_row->poreden_no - 1;
					$save2['operation'] 	= 318;
					$save2['mashina'] 		= 1306;
					$save2['last_oper'] 	= 1;
					
					$mas_info = $this->get_mashina_by_nomer($save2['mashina']);
					$save2['mashina_name'] 		= $mas_info->name;
					
					$oper_info = $this->get_operation_by_nomer( $save2['operation'] );
					$save2['operation_name'] 		= $oper_info->name;
					
					$this->aps->insert('planned', $save2);
					
				}
				
			}

			$i++;
		}
		
		//exit;
	}
	
	
	function get_ttibom010_by_item($mitm)
	{
		//$result = $this->oracle->query(' SELECT * FROM baandb.ttibom010601 WHERE T$MITM = \''.$mitm.'\' AND ( T$SITM LIKE \'%   0127%\' OR T$SITM LIKE \'%F402%\' ) ' );
		$result = $this->oracle->query(' SELECT * FROM baandb.ttibom010601 WHERE T$MITM = \''.$mitm.'\' AND T$SITM LIKE \'%   0127%\' ' );
		$res = $result->result_array;
		
		return $res;
	}
	
	function get_sls_401_by_item($mitm)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttdsls401601 WHERE T$ITEM = \''.$mitm.'\' ' );
		$res = $result->result_array;
		
		return $res;
	}
	
	
	function get_sls_401_by_item_v2($mitm)
	{
		$result = $this->oracle->query(' SELECT TO_CHAR(baandb.ttdsls401601.T$DDTA, \'DD-MON-YYYY HH24:MI:SS\') as T$DDTA2 FROM baandb.ttdsls401601 WHERE T$ITEM = \''.$mitm.'\' ' );
		$res = $result->result_array;
		
		return $res;
	}
	
	function get_sls_401_by_cprj($cprj)
	{
		$result = $this->oracle->query(' SELECT TO_CHAR(baandb.ttdsls401601.T$DDTA, \'DD-MON-YYYY HH24:MI:SS\') as T$DDTA2 FROM baandb.ttdsls401601 WHERE T$CPRJ = \''.$cprj.'\' AND T$CWAR = 105 ' );
		$res = $result->result_array;
		
		return $res;
	}
	
	function get_sls_401_by_cprj_V2($cprj)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttdsls401601 WHERE T$CPRJ = \''.$cprj.'\' AND T$CWAR = 105 ' );
		$res = $result->result_array;
		
		return $res;
	}
	
	
	function get_sum_by_zveno($machina_array)
	{
		$result = $this->aps->select('porachki.br_listi as br_sheets, porachki.tiraj ')->from('planned')->join('porachki', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->where_in('planned.mashina', $machina_array )
		->group_by('porachki.porachka')
		->get()->result();
		
		return $result;
	}
	
	
	function get_CNF_items_not_in_SFC001()
	{
		/*
			SELECT name
			FROM table2
			WHERE name NOT IN
				(SELECT name 
				 FROM table1)
		 */
		$result = $this->oracle->query('SELECT baandb.ttipcs020601.T$REFE
			FROM baandb.ttipcs020601
			WHERE T$CPRJ NOT IN
			(SELECT T$CPRJ FROM baandb.ttisfc001601) AND T$REFE LIKE \'CNF%\' AND T$REFE > \'CNF003000\' AND T$CPRJ LIKE \'PCS%\' ORDER BY T$REFE DESC' );
			$res = $result->result_array;
		
		return $res;
	}
	
	//GET THE OFFER ROW
	function get_cnf_row_from_oracle($cnf)
	{
		$result = $this->oracle->query(' SELECT baandb.ttdsls101601.*
			FROM baandb.ttdsls101601 where ttdsls101601.T$QONO = \''.$cnf.'\' AND T$ITEM LIKE \'BCS%\'
			ORDER BY T$PONO ASC ' );
		
		return $result->result_array;
	}
	
	function get_sls_402_from_oracle($qono, $pono, $srnb)
	{
		$result = $this->oracle->query(' SELECT baandb.ttdsls402601.*
			FROM baandb.ttdsls402601 where ttdsls402601.T$QONO = \''.$qono.'\' AND ttdsls402601.T$PONO = \''.$pono.'\' AND ttdsls402601.T$SRNB = \''.$srnb.'\'  ' );
		
		return $result->result_array;
	}
	
	
	function get_ttipcs020_by_refe($refe)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttipcs020601 where T$REFE = \''.$refe.'\' AND T$CPRJ LIKE \'PCS%\' ' );
		
		return $result->result_array;
	}
	
	function get_ttisfc021_by_sfc($nomer, $opno, $ostp )
	{
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		
		$result = $this->oracle->query(' SELECT T$DSCA FROM baandb.ttisfc021601 where T$PDNO = \''.$nomer.'\' ORDER BY T$OPNO ASC ' );
		
		return $result->result_array;
	}
	
	function get_all_ttisfc021_by_sfc($nomer)
	{
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		
		$result = $this->oracle->query(' SELECT * FROM baandb.ttisfc021601 where T$PDNO = \''.$nomer.'\' ORDER BY T$OPNO ASC ' );
		
		return $result->result_array;
	}
	

	function get_vremena_after_time($begin)
	{
		$this->aps->where('krai_int > ', $begin);
		//$this->aps->where('krai_int > ', 0);
		$this->aps->where('porachka > ', 0);
		$this->aps->group_by('porachka');
		$result = $this->aps->get('vremena')->result();
		
		return $result;
	}
	
	function get_work_times_between_dates($begin, $end)
	{
		$this->aps->where('krai_int > ', $begin);
		$this->aps->where('krai_int < ', $end);
		$this->aps->where('krai_int > ', 0);
		$this->aps->where('porachka > ', 0);
		$this->aps->group_by('porachka');
		$result = $this->aps->order_by('krai_int', 'DESC')->get('vremena')->result();
		
		return $result;
	}
	
	function get_finished_orders_between_dates($begin, $end)
	{
		$this->aps->where('date > ', $begin);
		$this->aps->where('date < ', $end);
		$this->aps->where('finished', 1);
		$result = $this->aps->order_by('order_no', 'ASC')->get('finished_orders')->result();
		
		return $result;
	}
	
	
	
	function get_vremena_after_time_by_machine($begin, $end, $machina_array)
	{
		$this->aps->where('nachalo_int > ', $begin);
		$this->aps->where('krai_int < ', $end);
		$this->aps->where('krai_int > ', 0);
		$this->aps->where('porachka > ', 0);
		$this->aps->order_by('mashina', 'ASC');
		//$this->aps->group_by('porachka');
		$this->aps->where_in('mashina', $machina_array );
		$this->aps->order_by('nachalo_int', 'ASC');
		$result = $this->aps->get('vremena')->result();
		
		return $result;
	}
	
	
	
	function get_vremena_by_machine($machina_array, $limit = 100, $porachka = 0)
	{
		$this->aps->where('krai_int > ', 0);
		$this->aps->where('porachka > ', $porachka);
		$this->aps->group_by('porachka');
		$this->aps->where_in('mashina', $machina_array );
		$this->aps->order_by('krai_int', 'DESC');
		$this->aps->limit($limit);
		$result = $this->aps->get('vremena')->result();
		
		return $result;
	}
	
	
	function get_orders_wait_for($reason)
	{
		$result = $this->aps->select('wait_for.*, wait_for.id AS wait_for_id, porachki.*, porachki.id AS order_id')->from('wait_for')->join('porachki', 'porachki.porachka=wait_for.porachka')
		->where('wait_for.reason', $reason)
		->where('wait_for.active', 1)
		->where('porachki.active', 1)

		->order_by('wait_for.porachka', "ASC")
		->group_by('wait_for.porachka')
		->get()->result();
		
		return $result;
	}
	
	
	
	function get_last_operation_by_order($porachka)
	{
		$result = $this->aps->select('planned.*')->from('planned')
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->where('planned.porachka', $porachka)
		->order_by('planned.immediately', 'DESC')
		->order_by('poreden_no', 'ASC')
		->get()->row();
		
		return $result;
	}
	
	function get_last_operation_by_order_finished($porachka)
	{
		$result = $this->aps->select('planned.*')->from('planned')
		->where('planned.porachka', $porachka)
		->order_by('poreden_no', 'DESC')
		->get()->row();
		
		return $result;
	}
	
	function get_last_time_vremena_prev_machine($mashina = false, $porachka = false)
	{
		$this->aps->where('krai_int != ', 0);
		$this->aps->where('kolichestvo != ', 0);
		
		$this->aps->where('operation != ', 262);
		
		$this->aps->where('porachka', $porachka);
		
		if($mashina)
			$this->aps->where('mashina != ', $mashina);
		
		$this->aps->order_by( 'krai_int', 'DESC');

		if($mashina && $porachka)
			$result = $this->aps->get('vremena')->row();
		
		return $result;
	}
	
	function get_last_time_vremena_prev_machine2($mashina = false, $porachka = false)
	{
		$this->aps->where('krai_int != ', 0);
		$this->aps->where('kolichestvo != ', 0);
		$this->aps->where('porachka', $porachka);
		
		$this->aps->where('mashina != ', 1309);
		$this->aps->where('mashina != ', 1320);
		
		$this->aps->where('operation != ', 262);
		
		if($mashina)
			$this->aps->where('mashina != ', $mashina);
		
		$this->aps->order_by( 'krai_int', 'DESC');

		if($mashina && $porachka)
			$result = $this->aps->get('vremena')->row();
		
		return $result;
	}
	
	
	
	function select_future_operations_by_mashina($mashina)
	{
		$result = $this->aps->select('porachki.*, planned.*, planned.id as operation_id')->from('porachki')->join('planned', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		
		->where('planned.mashina', $mashina)
		->order_by('kanban', 'ASC')
		
		->get()->result();
		
		return $result;
	}
	
	
	function get_cisli245_by_item($item)
	{
		$result = $this->oracle->query('SELECT * FROM baandb.tcisli245601 
		WHERE 
		tcisli245601.T$ITEM = \''.$item.'\'
		' );
		
		return $result->result_array;
	}
	
	
	function get_elements_oracle($table)
	{
		$result = $this->oracle->query('SELECT * FROM baandb.t'.$table.'601 ' );
		
		return $result->result_array;
	}
	
	function check_login($access, $email, $password)
	{
		$this->database = $this->load->database('default', TRUE);
		
		$this->database->where('access', $access);
		$this->database->where('password', $password);
		$this->database->where('email', $email);

		$result = $this->database->get('admin')->row();
		
		return $result;
	}
	
	
	function get_cisli245_from_oracle($item)
	{
		$result = $this->oracle->query('SELECT T$SLSO FROM baandb.tcisli245601 WHERE T$ITEM = \''.$item.'\' ' );
		
		return $result->result_array;
	}
	
	
	
	function get_orders_with_exec_operations_main_machines($array_machines)
	{
		//$this->aps->where('porachki.marker3', 0);
		$this->aps->where_in('planned.mashina', $array_machines);
	
		$result = $this->aps->select('porachki.*, porachki.id AS order_id, planned.*, planned.id AS operation_id')->from('planned')->join('porachki', 'porachki.porachka=planned.porachka')
		->where('porachki.active', 1)
		->where('planned.izpalneno', 0)
		->where('planned.last_oper', 1)
		->order_by('planned.tvardo_plan', "DESC")
		->order_by('planned.kanban', "ASC")
		->order_by('porachki.data_krai_int', "ASC")
		->group_by('planned.porachka')
		->get()->result();
		
		return $result;
	}
	
	function get_whinp100_from_oracle_by_por($nomer)
	{
		if (strlen($nomer) == 1) $nomer = "SFC00000" . $nomer;
		if (strlen($nomer) == 2) $nomer = "SFC0000" . $nomer;
		if (strlen($nomer) == 3) $nomer = "SFC000" . $nomer;
		if (strlen($nomer) == 4) $nomer = "SFC00" . $nomer;
		if (strlen($nomer) == 5) $nomer = "SFC0" . $nomer;
		if (strlen($nomer) == 6) $nomer = "SFC" . $nomer;
		$result = $this->oracle->query(' SELECT T$MITM FROM baandb.ttisfc001601 where  T$PDNO LIKE \'%'.$nomer.'%\' ' );
		
		return $result->result_array;
	}
	
	
	function get_instruments_from_oracle_by_grupe($grupe, $item)
	{
		$result = $this->oracle->query(' SELECT * FROM baandb.ttcibd001601 where T$CITG = \''.$grupe.'\' AND T$ITEM > \'         '.$item.'\' ' );
		
		return $result->result_array;
	}
	
	function get_po_instr_from_oracle_by_grupe($rcno)
	{
		$result = $this->oracle->query(' SELECT TO_CHAR(baandb.twhinh312601.T$TRDT, \'DD-MON-YYYY HH24:MI:SS\') as T$TRDT2, T$OORG, T$RCNO, T$ITEM FROM baandb.twhinh312601 where T$RCNO >= \''.$rcno.'\' ' );
		
		return $result->result_array;
	}
	
	function get_po_instr_from_oracle_by_rcno($rcno)
	{
		$result = $this->oracle->query(' SELECT TO_CHAR(baandb.twhinh312601.T$TRDT, \'DD-MON-YYYY HH24:MI:SS\') as T$TRDT2, T$OORG, T$RCNO FROM baandb.twhinh312601 where T$RCNO >= \''.$rcno.'\' ' );
		
		return $result->result_array;
	}
	
	function get_m_by_n_select_2_tables($table, $table2, $on_array, $select = false, $where_array = false, $order_array = false, $limit_offset_array = false, $group_array = false)
	{
		
		if($select)
		{
			$this->aps->select($select);
		}
		
		foreach($where_array as $where_key=>$where_value)
		{
			if($where_value != '')
				$this->aps->where($where_key, $where_value);
			else
			{
				 $this->aps->where($where_key);
			}
		}
		foreach($order_array as $order_key=>$order_value)
		{
			$this->aps->order_by($order_key, $order_value);
		}
		foreach($group_array as $group_key=>$g_value)
		{
			$this->aps->group_by($group_key);
		}
		foreach($limit_offset_array as $limit_key=>$limit_value)
		{
			$this->aps->limit($limit_key);
			$this->aps->offset($limit_value);
		}
		
		///$result = $this->aps->get($table)->result();
		
		$this->aps->from($table)->join($table2, $table . '.'.$on_array[0].' = '.$table2.'.'.$on_array[1]);
		
		$result = $this->aps->get()->result();
		
		return $result;
	}
	
}