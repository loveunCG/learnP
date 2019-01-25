<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class Pages_Model extends Base_Model  
{	
	function __construct()
	{
		parent::__construct();
	}
	function getPageStatistics()
	{
		$query = 'SELECT (SELECT COUNT(*) FROM '.$this->db->dbprefix(TBL_PAGES).') AS pagescount FROM '.$this->db->dbprefix(TBL_PAGES);		
		$resultsetlimit = $this->db->query( $query );
		return $resultsetlimit->result();
	}
	function getPagesData()
	{
		$query = "SELECT * FROM ".$this->db->dbprefix(TBL_PAGES)." ";
		return $this->db->query( $query )->result();
	}
}
?>