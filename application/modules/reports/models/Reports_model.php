<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_model extends CI_Model  
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Get tutor types
	 *
	 * @access	public
	 * @param	void
	 * @return	mixed
	 */
	function get_teachingtypes()
	{		
		$TeachingTypes = $this->db->get_where($this->db->dbprefix( 'teaching_types' ), array('status' => '1'))->result();		
		return $TeachingTypes;	
	}
	
	/**
	 * Get tutor types
	 *
	 * @access	public
	 * @param	void
	 * @return	mixed
	 */
	
	function list_student_packages()
	{		
		$query = "select * from " . $this->db->dbprefix('packages') . " 
		where status = 'Active' AND (package_for='All' OR package_for='student')";
		$packages = $this->db->query($query)->result();
		return $packages;	
	}

	function get_packages()
	{
		$query = "SELECT p.package_name,p.package_cost, count(*) as total_subscribe,  count(*) * p.package_cost as total_cost FROM `pre_packages` p INNER JOIN `pre_subscriptions` c  GROUP BY package_name";
		$package_subscribe_details = $this->db->query($query)->result();
			
		return $package_subscribe_details;
	}


	function get_package_subscribe_number($package_id = "", $user_type = "")
	{

		if(!($package_id > 0))
			return 0;

		$this->db->where('package_id', $package_id);

		if(!empty($user_type))
			$this->db->where('user_type', $user_type);

		return $this->db->count_all_results($this->db->dbprefix('subscriptions'));

	}


	function get_package_subscribe_total_amount($package_id = null){

		if(!($package_id > 0))
			return 0;

		$query = "select amount_paid  From " . $this->db->dbprefix('subscriptions'). " where package_id = " . $package_id;
		$package_subscribed_amount = $this->db->query($query);
			
		return ($package_subscribed_amount->num_rows() > 0) ? ($package_subscribed_amount->row()->amount_paid) : '0'; 
		
	}


	
	
}

?>