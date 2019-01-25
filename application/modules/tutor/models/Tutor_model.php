<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Tutor_model extends Base_Model  
{
	var $numrows;
	function __construct()
	{
		parent::__construct();
	}
	
	/****** GET SUBJECTS	
	* Author @
	* Adi
	******/
	function get_subjects()
	{		
		$subjects = array();		
		$parentSubjectDetails = $this->db->select('id AS parentSubject_id, subject_name AS parentSubject_name')->get_where($this->db->dbprefix( 'subjects' ), array('subject_parent_id' => 0, 'status' => 'Active'))->result();
		
		foreach($parentSubjectDetails as $p) {		
			$query = "SELECT s.*, (SELECT count(*) FROM ".$this->db->dbprefix( 'tutor_subjects' )." ts WHERE ts.subject_id = s.id AND ts.status = 'Active') AS no_of_tutors FROM ".$this->db->dbprefix( 'subjects' )." s 	WHERE s.subject_parent_id = ".$p->parentSubject_id." AND s.status = 'active'";			
			$childSubjects = $this->db->query($query)->result();			
			$subjects[$p->parentSubject_name] = $childSubjects;		
		}
		return $subjects;	
	}
	
	/****** GET TUTOR SUBJECT IDs
	* Author @
	* Adi
	******/
	function get_tutor_subject_ids($tutor_id = null)
	{
	
		$tutorSubjectIds = array();
		
		if($tutor_id != null && is_numeric($tutor_id)) {
		
			$tutorSubjectsRec = $this->db->select('subject_id')->get_where($this->db->dbprefix( 'tutor_subjects' ), array('user_id' => $tutor_id, 'status' => 'Active'))->result();
				
			foreach($tutorSubjectsRec as $t)
				array_push($tutorSubjectIds, $t->subject_id);
		}
		
		return $tutorSubjectIds;
	
	}
	
	/****** GET TUTOR SUBJECTS	
	* Author @
	* Adi
	******/
	function getTutorSubjects($tutor_id = null)
	{		
		$tutorSubjects = array();
		$tutorSubjectsArr = array();
		
		if($tutor_id != null && is_numeric($tutor_id)) {
		
			$tutorSubjectsRec = $this->db->select('subject_id')->get_where($this->db->dbprefix( 'tutor_subjects' ), array('user_id' => $tutor_id, 'status' => 'Active'))->result();
			
			foreach($tutorSubjectsRec as $t)
				array_push($tutorSubjectsArr, $t->subject_id);
		
			$parentSubjectDetails = $this->db->select('id AS parentSubject_id, subject_name AS parentSubject_name')->get_where($this->db->dbprefix( 'subjects' ), array('subject_parent_id' => 0, 'status' => 'Active'))->result();
			
			foreach($parentSubjectDetails as $p) {
			
				$childSubjects = $this->db->query("SELECT * FROM ".$this->db->dbprefix( 'subjects' )." WHERE subject_parent_id = ".$p->parentSubject_id." AND id IN (".implode(',', $tutorSubjectsArr).") AND status='Active'")->result();
				
				if(count($childSubjects) > 0)
					$tutorSubjects[$p->parentSubject_name] = $childSubjects;		
			}
		}

		return $tutorSubjects;
	
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
					FROM ".$this->db->dbprefix( 'tutor_locations' )." tl,
					 ".$this->db->dbprefix( 'users' )." u,
					 ".$this->db->dbprefix( 'users_groups' )." ug
					WHERE (tl.location_id = l.id OR 
					u.location_id = l.id) 
					AND ug.group_id = 3
					AND ug.user_id = u.id
					AND u.id = tl.tutor_id
					AND u.active = 1
					AND tl.status = '1'
					) AS no_of_tutors
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
	function get_tutor_location_ids($tutor_id = null)
	{
	
		$tutorLocationIds = array();
		
		if($tutor_id != null && is_numeric($tutor_id)) {
		
			$tutorLocationsRec = $this->db->select('location_id')->get_where($this->db->dbprefix( 'tutor_locations' ), array('tutor_id' => $tutor_id, 'status' => '1'))->result();
				
			foreach($tutorLocationsRec as $l)
				array_push($tutorLocationIds, $l->location_id);
		}
		
		return $tutorLocationIds;
	
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
	
	/****** GET TUTOR Teaching type IDs
	* Author @
	* Adi
	******/
	function get_tutor_selected_teachingtype_ids($tutor_id = null)
	{
	
		$tutorSelectedTypeIds = array();
		
		if($tutor_id != null && is_numeric($tutor_id)) {
		
			$tutorSelectedTypesRec = $this->db->select('teaching_type_id')->get_where($this->db->dbprefix( 'tutor_teaching_types' ), array('tutor_id' => $tutor_id, 'status' => '1'))->result();
				
			foreach($tutorSelectedTypesRec as $t)
				array_push($tutorSelectedTypeIds, $t->teaching_type_id);
		}
		
		return $tutorSelectedTypeIds;
	
	}
	
	/**
	 * Get tutor types
	 *
	 * @access	public
	 * @param	void
	 * @return	mixed
	 */
	function get_tutor_teachingtypes()
	{		
		$tutorTypes = $this->db->get_where($this->db->dbprefix( 'teaching_types' ), array('status' => '1'))->result();		
		return $tutorTypes;	
	}
	
	/**
	 * Get tutor types
	 *
	 * @access	public
	 * @param	void
	 * @return	mixed
	 */
	function list_tutor_packages()
	{		
		$query = "select * from " . $this->db->dbprefix('packages') . " 
		where status = 'Active' AND (package_for='All' OR package_for='Tutor')";
		$packages = $this->db->query($query)->result();
		return $packages;	
	}

	

	function get_tutor_assigned_course($user_id = null, $inst_id=null)
	{
		
		$user_id = (!empty($user_id)) ? $user_id : $this->ion_auth->get_user_id();
		$inst_id = (!empty($inst_id)) ? $inst_id : is_inst_tutor();

		if(empty($user_id) || empty($inst_id))
			return array();

		$tutor_assigned_courses_opts = array('' => get_languageword('no_courses_available'));

		$query = "SELECT ib.course_id, c.name FROM ".$this->db->dbprefix('inst_batches')." ib INNER JOIN ".$this->db->dbprefix('categories')." c ON c.id=ib.course_id WHERE ib.inst_id=".$inst_id." AND ib.tutor_id=".$user_id." GROUP BY ib.course_id ORDER BY c.name";

		$tutor_assigned_courses = $this->db->query($query)->result();

		if(!empty($tutor_assigned_courses)) {

			$tutor_assigned_courses_opts = array('' => get_languageword('select_course'));

			foreach ($tutor_assigned_courses as $key => $value)
				$tutor_assigned_courses_opts[$value->course_id] = $value->name;
		}

		return $tutor_assigned_courses_opts;
	}


	function get_tutor_dashboard_data($tutor_id = "")
	{

		 $tutor_dashboard_data = array();

		$query = "select count(*) total_bookings from ".$this->db->dbprefix('bookings')." where tutor_id = ".$tutor_id;
		$tutor_dashboard_data['total_bookings'] = $this->db->query($query)->row()->total_bookings;

		$query = "select count(*) pending_bookings from ".$this->db->dbprefix('bookings')." where tutor_id = ".$tutor_id." And status='pending'";
		$tutor_dashboard_data['pending_bookings'] = $this->db->query($query)->row()->pending_bookings;

		$query = "select count(*) completed_bookings from ".$this->db->dbprefix('bookings')." where tutor_id = ".$tutor_id." And status='completed'";
		$tutor_dashboard_data['completed_bookings'] = $this->db->query($query)->row()->completed_bookings;

		$query = "select count(*) running_bookings from ".$this->db->dbprefix('bookings')." where tutor_id = ".$tutor_id." And status='running'";
		$tutor_dashboard_data['running_bookings'] = $this->db->query($query)->row()->running_bookings;

		$query = "select count(*) courses from ".$this->db->dbprefix('tutor_courses')." where tutor_id = ".$tutor_id;
		$tutor_dashboard_data['courses'] = $this->db->query($query)->row()->courses;

		return $tutor_dashboard_data;
	}
	
	function get_inst_tutor_dashboard($tutor_id=" ")
	{
		$query = "SELECT u.username as inst_name, c.name courses ,count(ib.course_id) batches FROM ".$this->db->dbprefix('inst_batches')." ib join ".$this->db->dbprefix('users')." u on ib.inst_id=id join ".$this->db->dbprefix('categories')." c on ib.course_id= c.id  where tutor_id=".$tutor_id." group by ib.course_id,ib.inst_id";
		
		$get_inst_tutor_dashboard = $this->db->query($query)->result();

		return $get_inst_tutor_dashboard;
	}
}

?>