<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Institute_model extends Base_Model  
{
	var $numrows;

	function __construct()
	{
		parent::__construct();
	}
	
		
	/****** GET LOCATIONS	
	* Author @
	* Adi
	******/
	function get_locations()
	{
		
		$locations = array();
		
		$parentLocationDetails = $this->db->select('id AS parentLocation_id, location_name AS parentLocation_name')->get_where($this->db->dbprefix( 'locations' ), array('parent_location_id' => 0, 'status' => 'Active'))->result();
		
		foreach($parentLocationDetails as $p) {
		
			$query = "SELECT l . * , (

					SELECT count( * )
					FROM ".$this->db->dbprefix( 'inst_locations' )." tl,
					 ".$this->db->dbprefix( 'users' )." u,
					 ".$this->db->dbprefix( 'users_groups' )." ug
					WHERE (tl.location_id = l.id OR 
					u.location_id = l.id) 
					AND ug.group_id = 3
					AND ug.user_id = u.id
					AND u.id = tl.inst_id
					AND u.active = 1
					AND tl.status = '1'
					) AS no_of_institutes
					FROM ".$this->db->dbprefix( 'locations' )." l
					WHERE l.parent_location_id = ".$p->parentLocation_id."
					AND l.status = 'active'";
			
			$childLocations = $this->db->query($query)->result();
			
			$locations[$p->parentLocation_name] = $childLocations;		
		}

		return $locations;
	
	}
	
	/****** GET TUTOR LOCATION IDs
	* Author @
	*Raghu
	******/
	function get_institute_location_ids($inst_id = null)
	{
	
		$instituteLocationIds = array();
		
		if($inst_id != null && is_numeric($inst_id)) {
		
			$instituteLocationsRec = $this->db->select('location_id')->get_where($this->db->dbprefix( 'inst_locations' ), array('inst_id' => $inst_id, 'status' => '1'))->result();
				
			foreach($instituteLocationsRec as $l)
				array_push($instituteLocationIds, $l->location_id);
		}
		
		return $instituteLocationIds;
	
	}
	
	/****** GET TUTOR LOCATIONS	
	* Author @
	* Adi
	******/
	function get_tutor_locations($tutor_id = null)
	{		
		$tutorLocations = array();
		$tutorLocationsArr = array();
		
		if($tutor_id != null && is_numeric($tutor_id)) {
		
			$tutorLocationsRec = $this->db->select('location_id')->get_where($this->db->dbprefix( 'tutor_locations' ), array('tutor_id' => $tutor_id, 'status' => '1'))->result();
			
			foreach($tutorLocationsRec as $l)
				array_push($tutorLocationsArr, $l->location_id);
		
			$parentLocationDetails = $this->db->select('id AS parentLocation_id, location_name AS parentLocation_name')->get_where($this->db->dbprefix( 'locations' ), array('parent_location_id' => 0, 'status' => 'Active'))->result();
			
			foreach($parentLocationDetails as $p) {
			
				$childLocations = $this->db->query("SELECT * FROM ".$this->db->dbprefix( 'locations' )." WHERE parent_location_id = ".$p->parentLocation_id." AND id IN (".implode(',', $tutorLocationsArr).") AND status='Active'")->result();
				
				if(count($childLocations) > 0)
					$tutorLocations[$p->parentLocation_name] = $childLocations;		
			}
		}

		return $tutorLocations;
	
	}
	
	/****** GET INSTITUTE Teaching type IDs
	* Author @
	* Adi
	******/
	function get_institute_selected_teachingtype_ids($inst_id = null)
	{
	
		$instituteSelectedTypeIds = array();
		
		if($inst_id != null && is_numeric($inst_id)) {
		
			$instituteSelectedTypesRec = $this->db->select('teaching_type_id')->get_where($this->db->dbprefix( 'inst_teaching_types' ), array('inst_id' => $inst_id, 'status' => '1'))->result();
				
			foreach($instituteSelectedTypesRec as $t)
				array_push($instituteSelectedTypeIds, $t->teaching_type_id);
		}
		
		return $instituteSelectedTypeIds;
	
	}
	
	/**
	 * Get tutor types
	 *
	 * @access	public
	 * @param	void
	 * @return	mixed
	 */
	function get_institute_teachingtypes()
	{		
		$instituteTypes = $this->db->get_where($this->db->dbprefix( 'teaching_types' ), array('status'=>'1','teaching_type!='=>'home'))->result();		
		return $instituteTypes;	
	}

	/**
	 * Get Institute offered courses
	 *
	 * @access	public
	 * @param	void
	 * @return	mixed
	 */


	function get_institute_offered_course_ids($inst_id = null)
	{
	
		$instituteOfferedCourseIds = array();
		
		if($inst_id != null && is_numeric($inst_id)) {
		
			$instituteOfferedCourseRec = $this->db->select('course_id')->get_where($this->db->dbprefix( 'inst_offered_courses' ), array('inst_id' => $inst_id, 'status' => '1'))->result();
				
			foreach($instituteOfferedCourseRec as $l)
				array_push($instituteOfferedCourseIds, $l->course_id);
		}
		
		return $instituteOfferedCourseIds;
	
	}

	function get_institute_offered_course($inst_id = null)
	{

		$inst_id = (!empty($inst_id)) ? $inst_id : $this->ion_auth->get_user_id();

		if(empty($inst_id))
			return array();

		$inst_offered_course_opts = array('' => get_languageword('no_courses_available'));

		$query = "SELECT ic.course_id, c.name FROM ".$this->db->dbprefix('inst_offered_courses')." ic INNER JOIN ".$this->db->dbprefix('categories')." c ON c.id=ic.course_id WHERE ic.inst_id=".$inst_id." GROUP BY ic.course_id ORDER BY c.name";

		$inst_offered_courses = $this->db->query($query)->result();

		if(!empty($inst_offered_courses)) {

			$inst_offered_course_opts = array('' => get_languageword('select_course'));

			foreach ($inst_offered_courses as $key => $value)
				$inst_offered_course_opts[$value->course_id] = $value->name;
		}

		return $inst_offered_course_opts;
	}
		
	

	
	/**
	 * Get Institute Packages
	 *
	 * @access	public
	 * @param	void
	 * @return	mixed
	 */
	function list_institute_packages()
	{		
		$query = "select * from " . $this->db->dbprefix('packages') . " 
		where status = 'Active' AND (package_for='All' OR package_for='Institute')";
		$packages = $this->db->query($query)->result();
		return $packages;	
	}

	function get_tutors($inst_id)
	{

		$tutors = array();

		if($inst_id != null && is_numeric($inst_id)) {

			$where = array('active' => 1, 'parent_id' => $inst_id, 'availability_status' => '1');

			$tutors = $this->db->select('*')->get_where($this->db->dbprefix( 'users' ), $where)->result();
		}

		return $tutors;
	}

	
	function get_batches_by_course($course_id = "", $inst_id = "")
	{
		$inst_id = (!empty($inst_id)) ? $inst_id : $this->ion_auth->get_user_id();

		if(empty($course_id) || empty($inst_id))
			return array();

		$today = date('Y-m-d');

		$batches = $this->db->select('*')->get_where($this->db->dbprefix( 'inst_batches' ), array('course_id' => $course_id, 'inst_id' => $inst_id, 'batch_end_date >' => $today))->result();

		return $batches;
	}



	function get_inst_locations($inst_id = "")
	{
		if(empty($inst_id))
			return array();

		$query = "SELECT l.location_name FROM ".$this->db->dbprefix('inst_locations')." il INNER JOIN ".TBL_LOCATIONS." l ON l.id=il.location_id WHERE l.status='Active' AND il.status=1 AND il.inst_id=".$inst_id." ";
		$rs = $this->db->query($query);

		return ($rs->num_rows() > 0) ? $rs->result() : array();
	}

	function get_inst_dashboard_data($inst_id = "")
	{

		 $inst_dashboard_data = array();

		$query = "select count(*) num_of_tutors from ".$this->db->dbprefix('users')." where parent_id = ".$inst_id;
		$inst_dashboard_data['num_of_tutors'] = $this->db->query($query)->row()->num_of_tutors;

		$query1= "select count(*) batches from ".$this->db->dbprefix('inst_batches')." where inst_id = ".$inst_id; 
		$inst_dashboard_data['batches'] =  $this->db->query($query1)->row()->batches;
	
		$query2 = "SELECT count(Distinct course_id) courses FROM ". $this->db->dbprefix('inst_offered_courses') ." ic INNER JOIN ".$this->db->dbprefix('categories')." c ON c.id=ic.course_id WHERE ic.inst_id=".$inst_id;
		
		$inst_dashboard_data['courses'] =  $this->db->query($query2)->row()->courses;
	
		return $inst_dashboard_data;
	}

	function get_batch_enrolled_students_cnt($batch_id = '')
	{
		if(empty($batch_id))
			return 0;

		$enrolled_students = $this->base_model->fetch_records_from('inst_enrolled_students', array('batch_id' => $batch_id, 'status !=' => 'cancelled_before_course_started'));

		return count($enrolled_students);

	}


	function get_batch_status($batch_id = "")
	{
		if(empty($batch_id))
			return NULL;

		$is_pending_rec_exist = $this->db->select('status')
										 ->get_where(
										 				$this->db->dbprefix('inst_enrolled_students'), 
										 				array(
										 						'batch_id' => $batch_id, 
										 						'status' => 'pending'
										 					 )
										 			)
										 ->result();

		if(count($is_pending_rec_exist)) {

			return 'pending';

		} else {

			$query = "SELECT status , COUNT( 
				 status ) AS max_occurred
				 FROM  ".TBL_INST_ENROLLED_STUDENTS." 
				 WHERE batch_id =".$batch_id." 
				 AND status != 'cancelled_before_course_started' 
				 AND status != 'called_for_admin_intervention' 
				 AND status != 'pending'
				 GROUP BY status ORDER BY max_occurred DESC 
				 LIMIT 1";
			$batch_status = $this->db->query($query)->row();

			if(!empty($batch_status))
				return $batch_status->status;
			else
				return 'pending';
		}

	}


	function get_credits_of_batch_closed($batch_id = "")
	{
		if(empty($batch_id))
			return 0;

		$query = "SELECT (
					SUM( fee ) - SUM( admin_commission_val )
					) AS total_credits_of_batch_closed
					FROM  ".TBL_INST_ENROLLED_STUDENTS." 
					WHERE batch_id =".$batch_id."

					AND STATUS =  'closed'";

		$row = $this->db->query($query)->row();

		if(!empty($row))
			return $row->total_credits_of_batch_closed;
		else
			return 0;
	}



	function get_admin_commission_credits_of_batch_closed($batch_id = "")
	{
		if(empty($batch_id))
			return 0;

		$query = "SELECT (
					SUM( admin_commission_val )
					) AS total_admin_commission_credits_of_batch_closed
					FROM  ".TBL_INST_ENROLLED_STUDENTS." 
					WHERE batch_id =".$batch_id."

					AND STATUS =  'closed'";

		$row = $this->db->query($query)->row();

		if(!empty($row))
			return $row->total_admin_commission_credits_of_batch_closed;
		else
			return 0;
	}




}

?>