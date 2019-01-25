<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class Packages_Model extends Base_Model  
{	
	function __construct()
	{
		parent::__construct();
	}
		
	function getPackageStatistics()
	{
		$query = 'SELECT (SELECT COUNT(*) FROM '.$this->db->dbprefix(TBL_PACKAGES).') AS packagescount FROM '.$this->db->dbprefix(TBL_PACKAGES);		
		$resultsetlimit = $this->db->query( $query );
		return $resultsetlimit->result();
	}
	
	function getPackagesData()
	{
		$query = "SELECT * FROM ".$this->db->dbprefix(TBL_PACKAGES)." WHERE package_status='Active' ";
		return $this->db->query( $query )->result();
	}
}
?>