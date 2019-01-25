<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Student_model extends CI_Model  
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
					FROM ".$this->db->dbprefix( 'student_locations' )." tl,
					 ".$this->db->dbprefix( 'users' )." u,
					 ".$this->db->dbprefix( 'users_groups' )." ug
					WHERE (tl.location_id = l.id OR 
					u.location_id = l.id) 
					AND ug.group_id = 3
					AND ug.user_id = u.id
					AND u.id = tl.student_id
					AND u.active = 1
					AND tl.status = '1'
					) AS no_of_student
					FROM ".$this->db->dbprefix( 'locations' )." l
					WHERE l.parent_location_id = ".$p->parentLocation_id."
					AND l.status = 'active'";
			
			$childLocations = $this->db->query($query)->result();
			
			$locations[$p->parentLocation_name] = $childLocations;		
		}

		return $locations;
	
	}

	function get_courses()
	{
		
		$courses = array();
		
		$parentCourseDetails = $this->db->select('id AS parentCourse_id, name AS parentCourse_name')->get_where($this->db->dbprefix( 'categories' ), array('is_parent' => 1))->result();
		
		foreach($parentCourseDetails as $p) {
		
			
			
			$courses[$p->parentCourse_name] = $p->parentCourse_id;		
		}

		return $courses;
	
	}
	
	/****** GET TUTOR LOCATION IDs
	* Author @
	*Raghu
	******/
	function get_student_location_ids($student_id = null)
	{
	
		$studnentLocationIds = array();
		
		if($student_id != null && is_numeric($student_id)) {
		
			$studentLocationsRec = $this->db->select('location_id')->get_where($this->db->dbprefix( 'student_locations' ), array('student_id' => $student_id, 'status' => '1'))->result();
				
			foreach($studentLocationsRec as $l)
				array_push($studnentLocationIds, $l->location_id);
		}
		
		return $studnentLocationIds;
	
	}



	function get_student_preffered_course_ids($student_id = null)
	{
	
		$studnentPrefferedCourseIds = array();
		
		if($student_id != null && is_numeric($student_id)) {
		
			$studnentPrefferedCourseRec = $this->db->select('course_id')->get_where($this->db->dbprefix( 'student_preffered_courses' ), array('student_id' => $student_id, 'status' => '1'))->result();
				
			foreach($studnentPrefferedCourseRec as $l)
				array_push($studnentPrefferedCourseIds, $l->course_id);
		}
		
		return $studnentPrefferedCourseIds;
	
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
	function get_student_selected_teachingtype_ids($student_id = null)
	{
	
		$studentSelectedTypeIds = array();
		
		if($student_id != null && is_numeric($student_id)) {
		
			$studentSelectedTypesRec = $this->db->select('teaching_type_id')->get_where($this->db->dbprefix('student_prefferd_teaching_types' ), array('student_id' => $student_id, 'status' => '1'))->result();
				
			foreach($studentSelectedTypesRec as $t)
				array_push($studentSelectedTypeIds, $t->teaching_type_id);
		}
		
		return $studentSelectedTypeIds;
	
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

	function get_student_dashboard_data($student_id = "")
	{

		 $student_dashboard_data = array();

		$query = "select count(*) total_bookings from ".$this->db->dbprefix('bookings')." where student_id = ".$student_id;
		$student_dashboard_data['total_bookings'] = $this->db->query($query)->row()->total_bookings;

		$query = "select count(*) pending_bookings from ".$this->db->dbprefix('bookings')." where student_id = ".$student_id." And status='pending'";
		$student_dashboard_data['pending_bookings'] = $this->db->query($query)->row()->pending_bookings;

		$query = "select count(*) completed_bookings from ".$this->db->dbprefix('bookings')." where student_id = ".$student_id." And status='completed'";
		$student_dashboard_data['completed_bookings'] = $this->db->query($query)->row()->completed_bookings;

		$query = "select count(*) approved_bookings from ".$this->db->dbprefix('bookings')." where student_id = ".$student_id." And status='approved'";
		$student_dashboard_data['approved_bookings'] = $this->db->query($query)->row()->approved_bookings;

		$query = "select count(*) open_leads from ".$this->db->dbprefix('student_leads')." where user_id = ".$student_id." And status='Opened'";
		$student_dashboard_data['open_leads'] = $this->db->query($query)->row()->open_leads;

		$query = "select count(*) closed_leads from ".$this->db->dbprefix('student_leads')." where user_id = ".$student_id." And status='Closed'";
		$student_dashboard_data['closed_leads'] = $this->db->query($query)->row()->closed_leads;

		$query = "select count(*) inst_enrolled from ".$this->db->dbprefix('inst_enrolled_students')." where student_id = ".$student_id;
		$student_dashboard_data['inst_enrolled'] = $this->db->query($query)->row()->inst_enrolled;

		return $student_dashboard_data;
	}


	function update_student_course_downloads($student_id = "", $purchase_id = "")
	{
		if(!($student_id > 0) || !($purchase_id > 0))
			return false;

		$query = "UPDATE ".TBL_PREFIX."course_purchases SET total_downloads= total_downloads+1 WHERE user_id=".$student_id." AND purchase_id=".$purchase_id." AND payment_status='Completed' ";

		$this->db->query($query);

		return true;
	}


	function update_tutor_course_downloads($tutor_id = "", $sc_id = "")
	{
		if(!($tutor_id > 0) || !($sc_id > 0))
			return false;

		$query = "UPDATE ".TBL_PREFIX."tutor_selling_courses SET total_downloads= total_downloads+1 WHERE tutor_id=".$tutor_id." AND sc_id=".$sc_id." ";

		$this->db->query($query);

		return true;
	}


	function update_tutor_course_purchases($tutor_id = "", $sc_id = "")
	{
		if(!($tutor_id > 0) || !($sc_id > 0))
			return false;

		$query = "UPDATE ".TBL_PREFIX."tutor_selling_courses SET total_purchases= total_purchases+1 WHERE tutor_id=".$tutor_id." AND sc_id=".$sc_id." ";

		$this->db->query($query);

		return true;
	}





}

?>