<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class Base_Model extends CI_Model  
{
	var $numrows;

	function __construct()
	{
		parent::__construct();
	}


	//General database operations	
	function run_query( $query )
	{
		$rs = $this->db->query( $query );
		return $rs or die ('Error:' . mysql_error());
	}

	function get_query_result( $query )
	{
		return $this->db->query( $query )->result();
	}

	function get_query_row( $query )
	{
		return $this->db->query( $query )->row();
	}
	
	function count_records( $table, $condition = '' )
	{
		if( !(empty($condition)) )
		$this->db->where( $condition );
		$this->db->from( $this->db->dbprefix( $table ) );
		$reocrds = $this->db->count_all_results();
		//echo $this->db->last_query();
		return $reocrds;
	}
	
	function insert_operation( $inputdata, $table)
	{
		$result  = $this->db->insert($this->db->dbprefix($table),$inputdata);
		return $this->db->insert_id();
	}
	function insert_operation_id($inputdata, $table, $email = '')
    {
        $result = $this->db->insert($this->db->dbprefix($table), $inputdata);
        return $this->db->insert_id();
    }
	function update_operation( $inputdata, $table, $where )
	{
		$result  = $this->db->update($this->db->dbprefix($table),$inputdata, $where);
		return $result;
	}

	function update_operation_in( $inputdata, $table, $column, $values )
	{
		$this->db->where_in($column, $values);
		$result  = $this->db->update($this->db->dbprefix($table),$inputdata);
		return $result;
	}
	
	function fetch_records_from( $table, $condition = '',$select = '*', $order_by = '', $like = '', $offset = '', $perpage = '' )
	{
		$this->db->start_cache();
			$this->db->select($select, FALSE);
			$this->db->from( $this->db->dbprefix( $table ) );
			if( !empty( $condition ) )
				$this->db->where( $condition );
			if( !empty( $like ) )
					$this->db->like( $like );
			if( !empty( $order_by ) )
				$this->db->order_by( $order_by );
		$this->db->stop_cache();
		$result = $this->db->get();
		$this->numrows = $this->db->affected_rows();
      //echo $this->numrows.'<br>';
		if( $perpage != '' )
		$this->db->limit($perpage, $offset);
		$result = $this->db->get();
		//print_r($result);die();
		$this->db->flush_cache();
		return $result->result();
	}
	
	function fetch_records_from_in($table, $column, $value, $select = '*', $order_by = '', $like = '')
	{
		$this->db->start_cache();
			$this->db->select($select, FALSE);
			$this->db->from( $this->db->dbprefix( $table ) );
			$this->db->where_in( $column, $value );
			if( !empty( $like ) )
					$this->db->like( $like );
			if( !empty( $order_by ) )
				$this->db->order_by( $order_by );
		$this->db->stop_cache();    
		$this->numrows = $this->db->count_all_results();
		
		$result = $this->db->get();
		$this->db->flush_cache();
		return $result->result();
	}
	
	function fetch_value($table, $column, $where)
	{
		$this->db->select($column, FALSE);
		$this->db->from( $this->db->dbprefix( $table ) );
		$this->db->where( $where );
		$this->db->limit(0, 1);
		$result = $this->db->get()->result();
		$str = '-';
		if(count($result))
		{
			foreach($result as $row)
			{
				$str = $row->$column;
			}
		}
		return $str;
	}
		
	function changestatus( $table, $inputdata, $where  )
	{
		$result = $this->db->update($this->db->dbprefix($table),$inputdata, $where);
		return $result;
	}
	
	function delete_record($table, $column, $ids)
	{	
		$this->db->where_in($column, $ids);
		$result = $this->db->delete( $table );
		return $result;
	}
	
	function delete_record_new($table, $condition)
	{
		$this->db->where($condition);
		$this->db->delete( $table );
		return TRUE;
	}
	
	function get_user_details($id)
	{
		$query = 'SELECT u.*,g.name group_name, g.id group_id FROM '.$this->db->dbprefix('users').' u INNER JOIN '.$this->db->dbprefix('users_groups').' ug ON u.id = ug.user_id INNER JOIN '.$this->db->dbprefix('groups').' g ON g.id = ug.group_id WHERE g.group_status = "Active" AND u.id = '.$id;
		return $this->db->query($query)->result();
	}
	////////////////////////////Data Tables//////////////////////////
	private function _get_datatables_query($table, $condition = array(), $columns = array(), $order = array())
	{		
		
		$this->db->start_cache();
		
		$this->db->select($columns);
		$this->db->from($table);
		$this->db->group_start();
		if(!empty($condition))
		{
			if(isset($condition['incondition']))
			{
				$this->db->where_in($condition['incondition']['name'], $condition['incondition']['hey_stack']);
				unset($condition['incondition']);
				$this->db->where( $condition );
			}
			else
			{
				$this->db->where( $condition );
			}
		}
		$this->db->group_end();
		
		if($_POST['search']['value'])
		$this->db->group_start();
			$i = 0;
			$column = array();			
			foreach ($columns as $item) 
			{
				if($_POST['search']['value'])
					($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
				$column[$i] = $item;
				$i++;
			}			
		if($_POST['search']['value'])
		$this->db->group_end();
	
		//Colums Searching Start
		$column_search = FALSE;
		$p = 0;
		foreach ($columns as $item) 
		{
			if(isset($_POST['columns'][$p]['search']['value']) && $_POST['columns'][$p]['search']['value'] != '') $column_search = TRUE;
			$p++;
		}
		if($column_search == TRUE)
		{
			$this->db->group_start();
			$p = 0;		
			foreach ($columns as $item) 
			{
			if(isset($_POST['columns'][$p]['search']['value']) && $_POST['columns'][$p]['search']['value'] != '')
			$this->db->where($item, $this->getStringBetween(urldecode($_POST['columns'][$p]['search']['value']), '^', '$'));
			$p++;
			}
			$this->db->group_end();
		}
		//Colums Searching End
	
		if(isset($_POST['order']))
		{
			if(isset($_POST['order'][0]))
			$this->db->order_by($column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
		} 
		else if(count($order) > 0)
		{
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}		
		$this->db->stop_cache();	
	}
	
	function getStringBetween($str,$from,$to)
	{
		$sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
		return substr($sub,0,strpos($sub,$to));
	}
	
	private function _get_datatables_customquery($query, $columns = array(), $order = array())
	{	
		$i = 0;
		$column = array();
		$str = '';
		foreach ($columns as $item) 
		{
			if($_POST['search']['value'])
			($i===0) ? $str .= ' AND ('.$item . ' LIKE "%' . $_POST['search']['value'] . '%"' : $str .= ' OR '.$item . ' LIKE "%'.$_POST['search']['value'].'%"';
			$column[$i] = $item;
			$i++;
		}
		if($str != '')
			$str .= ')';
		
		//Colums Searching Start
		$column_search = FALSE;
		$p = 0;
		foreach ($columns as $item) 
		{
			if(isset($_POST['columns'][$p]['search']['value']) && $_POST['columns'][$p]['search']['value'] != '') $column_search = TRUE;
			$p++;
		}
		if($column_search == TRUE)
		{
			$p = 0;		
			foreach ($columns as $item) 
			{
			if(isset($_POST['columns'][$p]['search']['value']) && $_POST['columns'][$p]['search']['value'] != '')
				$str .= ' AND '.$item . ' = ' . $this->getStringBetween(urldecode($_POST['columns'][$p]['search']['value']), '^', '$');	
			$p++;
			}
		}
		//Colums Searching End
		
		 
		if(count($order) > 0)
		{
			$order = $order;
			$str .= ' ORDER BY tds.' . key($order) . ' ' . $order[key($order)];
		}
		elseif(isset($_POST['order']))
		{
			$str .= ' ORDER BY tds.' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
		}
		return 	$query . $str;	
	}
	
	private function _get_datatables_customquery_new($query, $columns = array(), $order = array())
	{	
		$i = 0;
		$column = array();
		$str = '';
		foreach ($columns as $item) 
		{
			if($_POST['search']['value'])
			($i===0) ? $str .= ' AND ('.$item . ' LIKE "%' . $_POST['search']['value'] . '%"' : $str .= ' OR '.$item . ' LIKE "%'.$_POST['search']['value'].'%"';
			$column[$i] = $item;
			$i++;
		}
		if($str != '')
			$str .= ')';
		
		//Colums Searching Start
		$column_search = FALSE;
		$p = 0;
		foreach ($columns as $item) 
		{
			if(isset($_POST['columns'][$p]['search']['value']) && $_POST['columns'][$p]['search']['value'] != '') $column_search = TRUE;
			$p++;
		}
		if($column_search == TRUE)
		{
			$p = 0;		
			foreach ($columns as $item) 
			{
			if(isset($_POST['columns'][$p]['search']['value']) && $_POST['columns'][$p]['search']['value'] != '')
				$str .= ' AND '.$item . ' = ' . $this->getStringBetween(urldecode($_POST['columns'][$p]['search']['value']), '^', '$');	
			$p++;
			}
		}
		//Colums Searching End
		
		 
		if(count($order) > 0)
		{
			$order = $order;
			$str .= ' ORDER BY ' . key($order) . ' ' . $order[key($order)];
		}
		elseif(isset($_POST['order']))
		{
			$str .= ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
		}
		return 	$query . $str;	
	}
	
	function get_datatables($table, $type = 'auto', $condition = array(), $columns = array(), $order = array())
	{
		
		if($type == 'custom')
		{
			$query_str = $this->_get_datatables_customquery($table, $columns, $order);
			
			$queryall = $this->db->query($query_str);
			$this->numrows = $this->db->affected_rows();
			if($_POST['length'] != -1)
			$query_str = $query_str . ' LIMIT '.$_POST['start'] .','. $_POST['length'];
		
			$query = $this->db->query($query_str);
		}
		else if($type == 'customnew')
		{
			$query_str = $this->_get_datatables_customquery_new($table, $columns, $order);
			//echo $query_str;die();
			$queryall = $this->db->query($query_str);
			$this->numrows = $this->db->affected_rows();
			if($_POST['length'] != -1)
			$query_str = $query_str . ' LIMIT '.$_POST['start'] .','. $_POST['length'];
		
			$query = $this->db->query($query_str);
		}
		else if($type == 'complex')
		{
			$this->_get_datatables_query_complex($table, $condition, $columns, $order);
			$queryall = $this->db->get();
			$this->numrows = $this->db->affected_rows();
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			
		}
		else
		{
			$this->_get_datatables_query($table, $condition, $columns, $order);
			//neatPrint($columns);
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
		}		
		$this->db->flush_cache();
		
		return $query->result();
	}
	
	function count_filtered($table, $type = 'auto', $condition = array(), $columns = array(), $order = array())
	{
		if($type == 'custom')
		{
			$query_str = $this->_get_datatables_customquery($table, $condition, $columns, $order);
			$query = $this->db->query($query_str)->result();
		}
		elseif($type == 'complex')
		{
			$this->_get_datatables_query_complex($table, $condition, $columns, $order);
			$query = $this->db->get();
		}
		else
		{
			$this->_get_datatables_query($table, $condition, $columns, $order);
			$query = $this->db->get();
		}
		
		
		//echo $this->db->last_query();
		return $query->num_rows();
	}
	
	public function count_all($table, $condition = array(),$type='')
	{
		if($type=='complex') 
							return 0;
		else 
		{
		$this->db->from($table);
		if(!empty($condition))
			$this->db->where( $condition );
		return $this->db->count_all_results();
		}
	}
	////Data tables end	
	function fetch_records_from_query_object($query, $offset = '', $perpage = '')
	{
		$resultset = $this->db->query( $query );
		$this->numrows = $resultset->num_rows();
		if( $perpage != '' )
			$query = $query . ' limit ' . $offset . ',' . $perpage;
		$resultsetlimit = $this->db->query( $query );
		return $resultsetlimit->result();
	}
	
	/**
	 * Get Payment Gateways
	 *
	 * @access	public
	 * @param	void
	 * @return	mixed
	 */
	function get_payment_gateways($str = '', $status = '')
	{		
		
		$status_cond = "";

		if(!empty($status))
			$status_cond = " AND st2.status='".$status."'";

		$query = "select st2.* from " . $this->db->dbprefix('system_settings_types') . " st inner join ".$this->db->dbprefix('system_settings_types')." st2 on st.type_id = st2.parent_id 
		where st.type_slug = 'PAYMENT_SETTINGS' ".$status_cond." ".$str;
		$packages = $this->db->query($query)->result();
		return $packages;	
	}


	function get_page_about_us(){

		$pageAboutUs= $this->db->get_where($this->db->dbprefix('pages'), array('id' => '1'))->result();
		return $pageAboutUs;
	}	

	function get_page_how_it_works(){

		$pageHowItWorks = $this->db->get_where($this->db->dbprefix('pages'), array('id' => '2'))->result();
		return $pageHowItWorks;
	}	

	function get_page_terms_and_conditions(){

		$pageTermsAndCondtions = $this->db->get_where($this->db->dbprefix('pages'), array('id' => '3'))->result();
		return $pageTermsAndCondtions;
	}	
	
	function get_page_privacy_and_policy(){

		$pagePrivachAndPolicy = $this->db->get_where($this->db->dbprefix('pages'), array('id' => '4'))->result();
		return $pagePrivachAndPolicy;
	}	

	function get_page_by_title()
	{
		
		$pages = $this->db->get_where($this->db->dbprefix('pages'), array('id > '=> '4'))->result();

		return $pages;
	}

	function get_page_by_title_content($slug){

		$pageData = $this->db->get_where($this->db->dbprefix('pages'),array('slug'=>$slug))->result();
		return $pageData;
	}

	function get_usersCount(){

		$query ="SELECT COUNT( * ) AS total_users, COUNT( IF( ug.group_id =2, 1, NULL ) ) AS total_students, COUNT( IF( ug.group_id =3, 1, NULL ) ) AS total_tutors, COUNT( IF( ug.group_id =4, 1, NULL ) ) AS total_institutes FROM " .$this->db->dbprefix('users'). " u  INNER JOIN ". $this->db->dbprefix('users_groups') ." ug ON ug.user_id = u.id WHERE u.id !=1";

		$userCount = $this->db->query($query)->result();

		return $userCount;

	}


	function get_packages_subscriptions()
	{
		// $query = "SELECT p.id, p.package_name, p.package_cost, (

		// 		SELECT COUNT( * ) 
		// 		FROM ". $this->db->dbprefix('subscriptions'). "
		// 		WHERE package_id = p.id
		// 		) AS total_subscriptions, 
				
		// 		(SELECT SUM( package_cost ) FROM " . $this->db->dbprefix('subscriptions') . " WHERE package_id = p.id) AS total_payments
		// 			FROM " . $this->db->dbprefix('packages') . " p";

		$query = "SELECT
					p.id, p.package_name, p.package_cost ,
					(select count(*)  from pre_subscriptions where package_id =p.id ) as total_subscriptions,
					(select count(*) from pre_subscriptions where user_type = 'Student' AND package_id =p.id) as Students,
					(select count(*) from pre_subscriptions where user_type = 'Tutor' AND package_id =p.id) as Tutors,
					(select count(*) from pre_subscriptions where user_type = 'Institute' AND package_id =p.id) as Institutes,
					(select sum(package_cost) from pre_subscriptions where package_id = p.id) as total_payments

					FROM 
					pre_packages p ";

		$result = $this->db->query($query)->result();
		return $result;
	}
	

}
?>