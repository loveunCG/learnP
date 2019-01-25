<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class Language_Model extends Base_Model  
{
		
	function __construct()
	{
		parent::__construct();
	}
		
	function getLanguageStatistics()
	{
		$query = 'SELECT (SELECT COUNT(*) FROM '.$this->db->dbprefix(TBL_LANGUAGEWORDS).') AS wordscount FROM '.$this->db->dbprefix(TBL_LANGUAGEWORDS);		
		$resultsetlimit = $this->db->query( $query );
		return $resultsetlimit->result();
	}
}
?>