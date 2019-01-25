<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class Settings_Model extends Base_Model  
{
		
	function __construct()
	{
		parent::__construct();
	}
	
	function getSettingsStatistics()
	{
		$query = 'SELECT (SELECT COUNT(term_id) FROM '.$this->db->dbprefix(TBL_TERMS_DATA).' WHERE parent_id=0 AND term_type="Categories") AS catcount,(SELECT COUNT(term_id) FROM '.$this->db->dbprefix(TBL_TERMS_DATA).' WHERE term_type="Categories" AND parent_id!=0) AS subcatcount FROM '.$this->db->dbprefix(TBL_TERMS_DATA).' WHERE term_type="Categories"';	
		$resultsetlimit = $this->db->query( $query );
		return $resultsetlimit->result();
	}
	
	/**
	* return types and sub types
	* @param	Int $id
	* @return	void
	*/
	function get_types($parent_id = '')
	{
		$where = '';
		if(!empty($parent_id))
			$where = ' WHERE t1.parent_id = '. $parent_id;
		$query = 'SELECT t1.type_title child, (SELECT type_title FROM '.$this->db->dbprefix(TBL_SETTINGS_TYPES).' t2 WHERE t2.type_id = t1.parent_id) AS parent, t1.type_id, t1.parent_id FROM '.$this->db->dbprefix(TBL_SETTINGS_TYPES).' t1 '.$where.' ORDER BY parent_id ASC,t1.type_title ASC,child ASC';
		return $this->db->query($query)->result();
	}



	function get_system_settings($field_key = "")
	{
		if(empty($field_key))
			return NULL;

		$result_set = $this->db->select('field_output_value')
							   ->get_where(
											$this->db->dbprefix('system_settings_fields'), 
											array(
													'field_key' => $field_key
												  )
											);

		return ($result_set->num_rows() > 0) ? $result_set->row()->field_output_value : NULL;
	}


}
?>