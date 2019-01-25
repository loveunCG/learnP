<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class Home_Model extends CI_Model  
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
	
	function count_records( $table, $condition = '' )
	{
		if( !(empty($condition)) )
		$this->db->where( $condition );
		$this->db->from( $this->db->dbprefix( $table ) );
		$reocrds = $this->db->count_all_results();
		//echo $this->db->last_query();
		return $reocrds;
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



	function get_categoryid_by_slug($category_slug)
	{
		if(empty($category_slug))
			return 0;

		$column_to_select = 'id';
		if(is_array($category_slug) || $category_slug instanceof Traversable) {

			$column_to_select = 'GROUP_CONCAT(id) AS id';
		}
		$result_set = $this->db->select($column_to_select)
						   		->where_in('slug', $category_slug)
						   		->get(TBL_CATEGORIES);

		return ($result_set->num_rows() > 0) ? $result_set->row()->id : 0;
	}



	function get_locationid_by_slug($location_slug)
	{
		if(empty($location_slug))
			return 0;

		$column_to_select = 'id';
		if(is_array($location_slug) || $location_slug instanceof Traversable) {

			$column_to_select = 'GROUP_CONCAT(id) AS id';
		}
		$result_set = $this->db->select($column_to_select)
						   		->where_in('slug', $location_slug)
						   		->get(TBL_LOCATIONS);

		return ($result_set->num_rows() > 0) ? $result_set->row()->id : 0;
	}



	function get_teachingtypeid_by_slug($teaching_type_slug)
	{
		if(empty($teaching_type_slug))
			return 0;

		$column_to_select = 'id';
		if(is_array($teaching_type_slug) || $teaching_type_slug instanceof Traversable) {

			$column_to_select = 'GROUP_CONCAT(id) AS id';
		}
		$result_set = $this->db->select($column_to_select)
						   		->where_in('slug', $teaching_type_slug)
						   		->get(TBL_TEACHING_TYPES);

		return ($result_set->num_rows() > 0) ? $result_set->row()->id : 0;
	}


	function get_categoryname_by_slug($category_slug)
	{
		if(empty($category_slug))
			return NULL;

		$result_set = $this->db->select('name')
						   ->get_where(
						   				TBL_CATEGORIES, 
						   				array('slug' => $category_slug)
						   			  );

		return ($result_set->num_rows() > 0) ? $result_set->row()->name : 0;
	}



	//GET CATEGORY-WISE POPULAR COURSES
	function get_popular_courses($category_limit = '', $course_limit = '', $is_popular = true)
	{
		$records 			= array();
		$categories 		= array();
		$courses 			= array();
		$course_limit_cond 	= "";
		$cnt 				= 0;
		$is_courses_found	= 0;
        $is_popular_cond    = "";

        if($is_popular)
            $is_popular_cond = ' AND courses.is_popular=1 ';


		if($category_limit > 0)
			$this->db->limit($category_limit);

		$categories = $this->db->order_by('sort_order', 'ASC')
							   ->get_where(
											TBL_CATEGORIES, 
											array(
													'is_parent' => 1, 
													'status' => 1
												)
										  )
								->result();

		if(empty($categories))
			return $records;

		if($course_limit > 0)
			$course_limit_cond = ' LIMIT '.$course_limit;

		foreach ($categories as $record) {

			$query   = "SELECT courses.* FROM ".TBL_COURSE_CATEGORIES." cc 
						INNER JOIN ".TBL_CATEGORIES." courses ON courses.id=cc.course_id 
						WHERE cc.category_id=".$record->id." AND courses.is_parent=0 
						AND courses.status=1 ".$is_popular_cond." 
						ORDER BY courses.sort_order ASC ".$course_limit_cond." ";

			$courses = $this->db->query($query)->result();

			if(!empty($courses)) {

				$is_courses_found = 1;

				foreach ($courses as $record1) {

					$records[$record->id."_".$record->slug."_".$record->name][] = $record1->id."_".$record1->slug."_".$record1->name;
				}
			}

		}

		if($is_courses_found == 1)
			return $records;
		else
			return array();
	}



	/* GET ONLY CATEGORIES WHICH HAS COURSES */
	function get_categories($params = array())
	{
		$limit_cond = "";

        if(!empty($params['start']) && !empty($params['limit']) && $params['start'] >= 0 && $params['limit'] >= 0) {

            $limit_cond = ' LIMIT '.$params['start'].', '.$params['limit'];

        } elseif(empty($params['start']) && !empty($params['limit']) && $params['limit'] >= 0){

            $limit_cond = ' LIMIT '.$params['limit'];
        }

		$query = "SELECT cat.* FROM ".TBL_CATEGORIES." cat 
				  WHERE is_parent=1 AND status=1 AND 
				  EXISTS (SELECT * FROM ".TBL_COURSE_CATEGORIES." cc WHERE cc.category_id=cat.id) 
				  ORDER BY cat.sort_order ASC ".$limit_cond." ";

		$categories = $this->db->query($query)->result();

		return $categories;
	}



	/* GET COURSES */
    function get_courses($params = array())
    {

        $query      = "";
        $limit_cond = "";
        $order_cond = " ORDER BY courses.sort_order ASC";


        if(!empty($params['start']) && !empty($params['limit']) && $params['start'] >= 0 && $params['limit'] >= 0) {

            $limit_cond = ' LIMIT '.$params['start'].', '.$params['limit'];

        } elseif(empty($params['start']) && !empty($params['limit']) && $params['limit'] >= 0){

            $limit_cond = ' LIMIT '.$params['limit'];
        }


        if(!empty($params['order_by'])) {

            $order_cond = " ORDER BY ".$params['order_by'];
        }



        if(empty($params['category_slug'])) {

            $query = "SELECT courses.* FROM ".TBL_CATEGORIES." courses WHERE courses.is_parent=0 AND courses.status=1 ".$order_cond." ".$limit_cond." ";

        } else if(!empty($params['category_slug'])) {

            $category_id = $this->get_categoryid_by_slug($params['category_slug']);

            if(!($category_id > 0))
                return FALSE;

            $query = "SELECT courses.* FROM ".TBL_COURSE_CATEGORIES." cc 
                    INNER JOIN ".TBL_CATEGORIES." courses ON courses.id=cc.course_id 
                    WHERE cc.category_id=".$category_id." 
                    AND courses.is_parent=0 AND courses.status=1 
                    ".$order_cond." ".$limit_cond." ";

        }


        $result_set = $this->db->query($query);        

        return ($result_set->num_rows() > 0) ? $result_set->result() : FALSE;
    }



    /* GET SELLING COURSES */
	function get_selling_courses($params = array())
    {

        $query 		= "";
        $limit_cond = "";
        $tutor_cond = "";
        $order_cond = " ORDER BY course_name ASC";


        if(!empty($params['start']) && !empty($params['limit']) && $params['start'] >= 0 && $params['limit'] >= 0) {

            $limit_cond = ' LIMIT '.$params['start'].', '.$params['limit'];

        } elseif(empty($params['start']) && !empty($params['limit']) && $params['limit'] >= 0){

            $limit_cond = ' LIMIT '.$params['limit'];
        }


        if(!empty($params['tutor_id'])) {

            $tutor_cond = " AND tutor_id= ".$params['tutor_id'];
        }

        if(!empty($params['order_by'])) {

        	$order_cond = " ORDER BY ".$params['order_by'];
        }



        $query = "SELECT * FROM ".TBL_PREFIX."tutor_selling_courses WHERE status='Active' AND admin_approved='Yes' ".$tutor_cond.$order_cond." ".$limit_cond." ";


        $result_set = $this->db->query($query);        

        return ($result_set->num_rows() > 0) ? $result_set->result() : FALSE;
    }



	/* GET LOCATIONS */
	function get_locations($params = array())
    {

        $query 		= "";
        $limit_cond = "";
        $extra_cond = "";


        if(!empty($params['start']) && !empty($params['limit']) && $params['start'] >= 0 && $params['limit'] >= 0) {

            $limit_cond = ' LIMIT '.$params['start'].', '.$params['limit'];

        } elseif(empty($params['start']) && !empty($params['limit']) && $params['limit'] >= 0){

            $limit_cond = ' LIMIT '.$params['limit'];
        }

        if(!empty($params['child'])) {

            $extra_cond = ' AND parent_location_id != 0';
        }

        $query = "SELECT * FROM ".TBL_LOCATIONS." WHERE (status='Active' OR status=1) ".$extra_cond." ORDER BY sort_order ASC ".$limit_cond." ";

        $result_set = $this->db->query($query);        

        return ($result_set->num_rows() > 0) ? $result_set->result() : FALSE;
    }



    /* GET TEACHING TYPES */
	function get_teaching_types($params = array())
    {

        $query 		= "";
        $limit_cond = "";


        if(!empty($params['start']) && !empty($params['limit']) && $params['start'] >= 0 && $params['limit'] >= 0) {

            $limit_cond = ' LIMIT '.$params['start'].', '.$params['limit'];

        } elseif(empty($params['start']) && !empty($params['limit']) && $params['limit'] >= 0){

            $limit_cond = ' LIMIT '.$params['limit'];
        }


        $query = "SELECT * FROM ".TBL_TEACHING_TYPES." WHERE status=1 ORDER BY sort_order ASC ".$limit_cond." ";

        $result_set = $this->db->query($query);        

        return ($result_set->num_rows() > 0) ? $result_set->result() : FALSE;
    }



    /* GET TUTORS */
	function get_tutors($params = array())
    {

        $query 					= "";
        $limit_cond 			= "";

        $adm_approval_cond 		= "";

        $course_tbl_join		= "";
        $location_tbl_join 		= "";
        $teaching_type_tbl_join = "";

        $course_cond 			= "";
        $location_cond 			= "";
        $teaching_type_cond 	= "";


        if(!empty($params['start']) && !empty($params['limit']) && $params['start'] >= 0 && $params['limit'] >= 0) {

            $limit_cond = ' LIMIT '.$params['start'].', '.$params['limit'];

        } elseif(empty($params['start']) && !empty($params['limit']) && $params['limit'] >= 0){

            $limit_cond = ' LIMIT '.$params['limit'];
        }


        if(strcasecmp(get_system_settings('need_admin_for_tutor'), 'yes') == 0) {

        	$adm_approval_cond = ' AND u.admin_approved = "Yes" ';
        }



        if(!empty($params['course_slug'])) {

        	$course_id 	 = $this->get_categoryid_by_slug($params['course_slug']);

        	if(empty($course_id))
        		return FALSE;

        	$course_tbl_join = " INNER JOIN ".TBL_TUTOR_COURSES." tc ON tc.tutor_id=u.id ";
        	$course_cond = " AND tc.course_id IN (".$course_id.") AND tc.status=1 ";
        }

        if(!empty($params['location_slug'])) {

        	$location_id = $this->get_locationid_by_slug($params['location_slug']);

        	if(empty($location_id))
        		return FALSE;

        	$location_tbl_join = " INNER JOIN ".TBL_TUTOR_LOCATIONS." tl ON tl.tutor_id=u.id ";
        	$location_cond = " AND tl.location_id IN (".$location_id.") ";
        }

        if(!empty($params['teaching_type_slug'])) {

        	$teaching_type_id = $this->get_teachingtypeid_by_slug($params['teaching_type_slug']);

        	if(empty($teaching_type_id))
        		return FALSE;

        	$teaching_type_tbl_join = " INNER JOIN ".TBL_TUTOR_TEACHING_TYPES." tt ON tt.tutor_id=u.id ";
        	$teaching_type_cond = " AND tt.teaching_type_id IN (".$teaching_type_id.") ";
        }


        $query = "SELECT u.* FROM ".TBL_USERS." u 
	    			INNER JOIN ".TBL_USERS_GROUPS." ug ON ug.user_id=u.id 
	    			".$course_tbl_join." 
	    			".$location_tbl_join." 
	    			".$teaching_type_tbl_join." 
					WHERE u.active=1 AND u.visibility_in_search='1' 
                    AND u.is_profile_update=1 AND (u.parent_id=0 OR u.parent_id='') AND ug.group_id=3 
					".$adm_approval_cond." 
					".$course_cond." 
					".$location_cond." 
					".$teaching_type_cond." 
					GROUP BY u.id ORDER BY u.net_credits DESC ".$limit_cond." ";


        $result_set = $this->db->query($query);        

        return ($result_set->num_rows() > 0) ? $result_set->result() : array();
    }



    /* GET TUTORS */
    function get_institutes($params = array())
    {

        $query                  = "";
        $limit_cond             = "";

        $adm_approval_cond      = "";

        $course_tbl_join        = "";
        $location_tbl_join      = "";
        $teaching_type_tbl_join = "";

        $inst_cond              = "";
        $course_cond            = "";
        $location_cond          = "";
        $teaching_type_cond     = "";
        $visibility_in_search   = "";

        if(!empty($params['start']) && !empty($params['limit']) && $params['start'] >= 0 && $params['limit'] >= 0) {

            $limit_cond = ' LIMIT '.$params['start'].', '.$params['limit'];

        } elseif(empty($params['start']) && !empty($params['limit']) && $params['limit'] >= 0){

            $limit_cond = ' LIMIT '.$params['limit'];
        }


        if(strcasecmp(get_system_settings('need_admin_for_inst'), 'yes') == 0) {

            $adm_approval_cond = ' AND u.admin_approved = "Yes" ';
        }


        if(!empty($params['course_slug'])) {

            $course_id   = $this->get_categoryid_by_slug($params['course_slug']);

            if(empty($course_id))
                return FALSE;

            $course_tbl_join = " INNER JOIN ".TBL_INST_OFFERED_COURSES." ic ON ic.inst_id=u.id ";
            $course_cond = " AND ic.course_id IN (".$course_id.") AND ic.status=1 ";
        }

        if(!empty($params['location_slug'])) {

            $location_id = $this->get_locationid_by_slug($params['location_slug']);

            if(empty($location_id))
                return FALSE;

            $location_tbl_join = " INNER JOIN ".TBL_INST_LOCATIONS." il ON il.inst_id=u.id ";
            $location_cond = " AND il.location_id IN (".$location_id.") ";
        }

        if(!empty($params['teaching_type_slug'])) {

            $teaching_type_id = $this->get_teachingtypeid_by_slug($params['teaching_type_slug']);

            if(empty($teaching_type_id))
                return FALSE;

            $teaching_type_tbl_join = " INNER JOIN ".TBL_INST_TEACHING_TYPES." tt ON tt.inst_id=u.id ";
            $teaching_type_cond = " AND tt.teaching_type_id IN (".$teaching_type_id.") ";
        }

        if(!empty($params['inst_slug'])) {

            $inst_slugs = "'".implode("','", $params['inst_slug'])."'";
            $inst_cond = ' AND u.slug IN ('.$inst_slugs.')';
        }


        $query = "SELECT u.* FROM ".TBL_USERS." u 
                    INNER JOIN ".TBL_USERS_GROUPS." ug ON ug.user_id=u.id 
                    ".$course_tbl_join." 
                    ".$location_tbl_join." 
                    ".$teaching_type_tbl_join." 
                    WHERE u.active=1 AND u.visibility_in_search='1' AND u.is_profile_update=1 AND ug.group_id=4 
                    ".$inst_cond." 
                    ".$adm_approval_cond." 
                    ".$course_cond." 
                    ".$location_cond." 
                    ".$teaching_type_cond." 
                    GROUP BY u.id ORDER BY u.net_credits DESC ".$limit_cond." ";


        $result_set = $this->db->query($query);

        return ($result_set->num_rows() > 0) ? $result_set->result() : array();
    }



    /* GET STUDENT LEADS */
	function get_student_leads($params = array())
    {

        $query 				= "";
        $limit_cond 		= "";
        $course_cond 		= "";
        $location_cond 		= "";
        $teaching_type_cond = "";


        if(!empty($params['start']) && !empty($params['limit']) && $params['start'] >= 0 && $params['limit'] >= 0) {

            $limit_cond = ' LIMIT '.$params['start'].', '.$params['limit'];

        } elseif(empty($params['start']) && !empty($params['limit']) && $params['limit'] >= 0){

            $limit_cond = ' LIMIT '.$params['limit'];
        }



        if(!empty($params['course_slug'])) {

        	$course_id 	 = $this->get_categoryid_by_slug($params['course_slug']);

        	if(empty($course_id))
        		return FALSE;

        	$course_cond = " AND sl.course_id IN (".$course_id.") ";
        }

        if(!empty($params['location_slug'])) {

        	$location_id = $this->get_locationid_by_slug($params['location_slug']);

        	if(empty($location_id))
        		return FALSE;

        	$location_cond = " AND sl.location_id IN (".$location_id.") ";
        }

        if(!empty($params['teaching_type_slug'])) {

        	$teaching_type_id = $this->get_teachingtypeid_by_slug($params['teaching_type_slug']);

        	if(empty($teaching_type_id))
        		return FALSE;

        	$teaching_type_cond = " AND sl.teaching_type_id IN (".$teaching_type_id.") ";
        }


    	$query = "SELECT u.*, sl.*, sl.id AS lead_id FROM ".TBL_USERS." u 
    			  INNER JOIN ".TBL_USERS_GROUPS." ug ON ug.user_id=u.id 
    			  INNER JOIN ".TBL_STUDENT_LEADS." sl ON sl.user_id=u.id 
    			  WHERE u.active=1 AND u.visibility_in_search='1' AND u.availability_status='1' 
                  AND u.is_profile_update=1 AND ug.group_id=2 AND sl.status='opened' 
    			  ".$course_cond." 
    			  ".$location_cond." 
    			  ".$teaching_type_cond." 
    			  ORDER BY sl.id DESC ".$limit_cond." ";


        $result_set = $this->db->query($query);        

        return ($result_set->num_rows() > 0) ? $result_set->result() : array();
    }


    function get_uid_by_slug($uslug = "")
    {
        if(empty($uslug))
            return NULL;

        $row = $this->db->select('id')->get_where($this->db->dbprefix('users'), array('slug' => $uslug))->row();

        return (!empty($row)) ? $row->id : '';
    }


    function get_courseid_by_slug($cslug = "")
    {
    	if(empty($cslug))
    		return NULL;

    	return $this->db->select('id')->get_where($this->db->dbprefix('categories'), array('slug' => $cslug))->row()->id;
    }


    function get_tutor_profile($tutor_slug = "")
    {
    	if(empty($tutor_slug))
    		return NULL;

    	$adm_approval_cond 	= "";

    	if(strcasecmp(get_system_settings('need_admin_for_tutor'), 'yes') == 0) {

        	$adm_approval_cond = ' AND u.admin_approved = "Yes" ';
        }

        $tutor_id = $this->get_uid_by_slug($tutor_slug);


        $tutor_info_query = "SELECT u.* FROM ".$this->db->dbprefix('users')." u WHERE u.active=1 AND u.visibility_in_search='1' AND u.availability_status='1' AND u.is_profile_update=1 AND (u.parent_id=0 OR u.parent_id='') AND u.slug='".$tutor_slug."' ".$adm_approval_cond." ";

    	$tutor_details = $this->db->query($tutor_info_query)->result();

        if(empty($tutor_details)) {
            return array();
        }

    	//Tutoring Courses
    	$tutor_courses_query = "SELECT GROUP_CONCAT(' ', courses.name) AS tutoring_courses FROM ".$this->db->dbprefix('tutor_courses')." tc INNER JOIN ".$this->db->dbprefix('categories')." courses ON courses.id=tc.course_id WHERE tc.tutor_id=".$tutor_id." AND tc.status=1 AND courses.status=1 ORDER BY tc.sort_order ASC";
    	$tutor_details[0]->tutoring_courses = $this->db->query($tutor_courses_query)->row()->tutoring_courses;

    	//Tutoring Locations
    	$tutor_locations_query = "SELECT GROUP_CONCAT(' ', l.location_name) AS tutoring_locations FROM ".$this->db->dbprefix('tutor_locations')." tl INNER JOIN ".$this->db->dbprefix('locations')." l ON l.id=tl.location_id WHERE tl.tutor_id=".$tutor_id." AND tl.status=1 AND l.status=1 ORDER BY tl.sort_order ASC";
    	$tutor_details[0]->tutoring_locations = $this->db->query($tutor_locations_query)->row()->tutoring_locations;

        //Tutor's Gallery
        $tutor_gallery_query = "SELECT image_title, image_name FROM ".$this->db->dbprefix('gallery')." WHERE user_id=".$tutor_id." AND image_status='Active' ORDER BY image_order ASC";
        $tutor_details[0]->tutor_gallery = $this->db->query($tutor_gallery_query)->result();

        //Tutor Experience
        $tutor_experience_query = "SELECT company, role, description, from_date, to_date FROM ".$this->db->dbprefix('users_experience')." WHERE user_id=".$tutor_id." ";
        $tutor_details[0]->tutor_experience = $this->db->query($tutor_experience_query)->result();


    	return $tutor_details;
    }



    function get_full_location_name($location_id = "")
    {
    	if(!($location_id > 0))
    		return NULL;

    	$query = "SELECT CONCAT(l.location_name, ', ', pl.location_name) AS full_location_name FROM ".$this->db->dbprefix('locations')." l INNER JOIN ".$this->db->dbprefix('locations')." pl ON pl.id=l.parent_location_id WHERE l.id=".$location_id;
    	$result_set = $this->db->query($query);

    	return ($result_set->num_rows() > 0) ? $result_set->row()->full_location_name : FALSE;
    }



    /* GET TUTOR COURSES BY TUTOR SLUG */
    function get_tutor_courses($tutor_slug = "", $result_type = "")
    {
        if(empty($tutor_slug))
            return NULL;

        $tutor_id = $this->get_uid_by_slug($tutor_slug);

        $query = "SELECT courses.slug, courses.name FROM ".$this->db->dbprefix('categories')." courses INNER JOIN ".$this->db->dbprefix('tutor_courses')." tc ON tc.course_id=courses.id WHERE tc.tutor_id=".$tutor_id." AND tc.status=1 AND courses.status=1 ORDER BY tc.sort_order ASC ";

        if($result_type == "grouped") {

            $query = "SELECT GROUP_CONCAT(' ', courses.name) AS tutoring_courses FROM ".$this->db->dbprefix('tutor_courses')." tc INNER JOIN ".$this->db->dbprefix('categories')." courses ON courses.id=tc.course_id WHERE tc.tutor_id=".$tutor_id." AND tc.status=1 AND courses.status=1 ORDER BY tc.sort_order ASC";

            $rs = $this->db->query($query);

            return ($rs->num_rows() > 0) ? $rs->row()->tutoring_courses : NULL;
        }

        $rs = $this->db->query($query);

        return ($rs->num_rows() > 0) ? $rs->result() : NULL;
    }


    /* GET TUTOR LOCATIONS BY TUTOR SLUG */
    function get_tutor_locations($tutor_slug = "")
    {
        if(empty($tutor_slug))
            return NULL;

        $tutor_id = $this->get_uid_by_slug($tutor_slug);

        $query = "SELECT l.slug, l.location_name FROM ".$this->db->dbprefix('locations')." l INNER JOIN ".$this->db->dbprefix('tutor_locations')." tl ON tl.location_id=l.id WHERE tl.tutor_id=".$tutor_id." AND tl.status=1 AND (l.status=1 OR l.status='Active') ORDER BY tl.sort_order ASC ";

        $rs = $this->db->query($query);

        return ($rs->num_rows() > 0) ? $rs->result() : NULL;
    }


    /* GET TUTOR TEACHING TYPES BY TUTOR SLUG */
    function get_tutor_teaching_types($tutor_slug = "")
    {
        if(empty($tutor_slug))
            return NULL;

        $tutor_id = ($tutor_slug > 0) ? $tutor_slug : $this->get_uid_by_slug($tutor_slug);

        $query = "SELECT tt.slug, tt.teaching_type FROM ".$this->db->dbprefix('teaching_types')." tt INNER JOIN ".$this->db->dbprefix('tutor_teaching_types')." ttt ON ttt.teaching_type_id=tt.id WHERE ttt.tutor_id=".$tutor_id." AND ttt.status=1 AND tt.status=1 ORDER BY ttt.sort_order ASC ";

        $rs = $this->db->query($query);

        return ($rs->num_rows() > 0) ? $rs->result() : NULL;
    }


    function get_tutor_course_details($course_slug = "", $tutor_id = "")
    {
        if(empty($course_slug) || empty($tutor_id))
            return NULL;

        $course_id = ($course_slug > 0) ? $course_slug : $this->get_courseid_by_slug($course_slug);

        $rs = $this->db->select('course_id, duration_value, duration_type, fee, per_credit_value, content, time_slots, days_off')
                       ->get_where(
                                    $this->db->dbprefix('tutor_courses'), 
                                    array(
                                            'course_id' => $course_id, 
                                            'tutor_id' => $tutor_id, 
                                            'status' => 1
                                         )
                                    );

        return ($rs->num_rows() > 0) ? $rs->row() : NULL;

    }


    function get_booked_slots($tutor_id = "", $course_id = "", $selected_date = "")
    {
        if(empty($tutor_id) || empty($course_id) || empty($selected_date))
            return NULL;

        $query = "SELECT time_slot FROM ".TBL_BOOKINGS." WHERE tutor_id=".$tutor_id." AND course_id=".$course_id." AND ('".$selected_date."' BETWEEN start_date AND end_date) AND status!='pending' AND status!='cancelled_before_course_started' AND status!='cancelled_when_course_running' AND status!='cancelled_after_course_completed' AND status!='completed' AND status!='closed' ";
        $rs = $this->db->query($query);

        if($rs->num_rows() > 0) {

            $slots = array();

            foreach ($rs->result() as $key => $value) {
               $slots[] = $value->time_slot;
            }

            return $slots;

        } else return NULL;

    }


    function is_already_booked_the_tutor($student_id = "", $tutor_id = "", $course_id = "", $selected_date = "", $time_slot = "")
    {
        if(empty($student_id) || empty($tutor_id) || empty($course_id) || empty($selected_date) || empty($time_slot))
            return FALSE;

        $query = "SELECT booking_id FROM ".TBL_BOOKINGS." WHERE student_id=".$student_id." AND tutor_id=".$tutor_id." AND course_id=".$course_id." AND ('".$selected_date."' BETWEEN start_date AND end_date) AND time_slot='".$time_slot."' AND (status='pending' OR status='approved' OR status='session_initiated' OR status='running' OR status='called_for_admin_intervention') ";
        $rs = $this->db->query($query);

        return ($rs->num_rows() > 0) ? TRUE : FALSE;
    }


    function is_already_enrolled_in_the_batch($student_id = "", $batch_id = "")
    {
        if(empty($student_id) || empty($batch_id))
            return FALSE;

        $query = "SELECT enroll_id FROM ".TBL_INST_ENROLLED_STUDENTS." WHERE student_id=".$student_id." AND batch_id=".$batch_id." AND (status='pending' OR status='approved' OR status='session_initiated' OR status='running' OR status='called_for_admin_intervention') ";
        $rs = $this->db->query($query);

        return ($rs->num_rows() > 0) ? TRUE : FALSE;
    }


    function is_time_slot_avail($tutor_id = "", $course_id = "", $selected_date = "", $time_slot = "")
    {
        if(empty($tutor_id) || empty($course_id) || empty($selected_date) || empty($time_slot))
            return FALSE;

        $query = "SELECT booking_id FROM ".TBL_BOOKINGS." WHERE tutor_id=".$tutor_id." AND course_id=".$course_id." AND ('".$selected_date."' BETWEEN start_date AND end_date) AND time_slot='".$time_slot."' AND (status='approved' OR status='session_initiated' OR status='running' OR status='called_for_admin_intervention') ";
        $rs = $this->db->query($query);

        return ($rs->num_rows() > 0) ? FALSE : TRUE;
    }


    //To check for new enrollment availability
    function total_enrolled_students_in_batch($batch_id = "")
    {
        if(empty($batch_id))
            return 0;

        $query = "SELECT enroll_id FROM ".TBL_INST_ENROLLED_STUDENTS." WHERE batch_id=".$batch_id." AND (status='pending' OR status='approved' OR status='session_initiated' OR status='running' OR status='called_for_admin_intervention') ";
        $rs = $this->db->query($query);

        return $rs->num_rows();
    }


    function log_user_credits_transaction($log_data = array())
    {
        if(empty($log_data))
            return NULL;

        if($this->db->insert(TBL_USER_CREDIT_TRANSACTIONS, $log_data))
            return TRUE;
        else
            return FALSE;
    }


    function update_user_credits($user_id = "", $credits = 0, $action = "")
    {
        if(!($user_id > 0) || !($credits > 0) || !in_array($action, array('credit', 'debit')))
            return NULL;

        $operation = '-';
        if($action == "credit")
            $operation = '+';

        $date = date('Y-m-d H:i:s');
        $query = "UPDATE ".TBL_USERS." SET net_credits=net_credits".$operation.$credits.", last_updated='".$date."' WHERE id=".$user_id." ";

        $this->db->query($query);

        return $this->db->affected_rows();
    }



    function get_student_profile($student_slug = "",$student_lead_id= "")
    {
        if(empty($student_slug))
            return NULL;

        $CI =& get_instance();

        if(!$this->ion_auth->logged_in()) {
            $CI->prepare_flashmessage(get_languageword('please_login_to_continue.'), 2);
            return redirect(URL_AUTH_LOGIN);
        }

        $student_id = $this->get_uid_by_slug($student_slug);

        if(empty($student_id))
            return NULL;

        $student_info_query = "SELECT * FROM ".TBL_USERS." WHERE id=".$student_id." AND active=1 AND visibility_in_search='1' AND availability_status='1' 
                  AND is_profile_update=1 ";

        $student_details = $this->db->query($student_info_query)->result();

        if(!empty($student_details)) {

            if($student_lead_id > 0) {

                $lead_info_query = "SELECT sl.*, l.location_name, pl.location_name AS parent_location_name, c.name AS course_name, t.teaching_type FROM ".TBL_STUDENT_LEADS." sl INNER JOIN ".TBL_LOCATIONS." l ON l.id=sl.location_id INNER JOIN ".TBL_LOCATIONS." pl ON pl.id=l.parent_location_id INNER JOIN ".TBL_CATEGORIES." c ON c.id=sl.course_id INNER JOIN ".TBL_TEACHING_TYPES." t ON t.id=sl.teaching_type_id WHERE sl.id=".$student_lead_id." AND sl.status='Opened' ";

                $lead_details = $this->db->query($lead_info_query)->result();

                if(!empty($lead_details) && !$this->ion_auth->is_admin()) {

                    $credits_required_for_viewing_lead = get_system_settings('credits_for_viewing_lead');

                    if($credits_required_for_viewing_lead > 0) {

                        $viewer_id = $this->ion_auth->get_user_id();

                        if(!$this->is_already_viewed_the_lead($viewer_id, 'student_leads', $student_lead_id)) {

                            $viewer_credits = get_user_credits($viewer_id);

                            if($viewer_credits >= $credits_required_for_viewing_lead) {

                                //Log Credits transaction data & update user net credits - Start
                                $log_data = array(
                                                'user_id' => $viewer_id,
                                                'credits' => $credits_required_for_viewing_lead,
                                                'per_credit_value' => get_system_settings('per_credit_value'),
                                                'action'  => 'debited',
                                                'purpose' => get_languageword('For viewing lead ').' "'.$lead_details[0]->title_of_requirement.'" '.get_languageword('of Student').' "'.$student_details[0]->username.'"',
                                                'date_of_action ' => date('Y-m-d H:i:s'),
                                                'reference_table' => 'student_leads',
                                                'reference_id' => $student_lead_id,
                                            );

                                log_user_credits_transaction($log_data);

                                update_user_credits($viewer_id, $credits_required_for_viewing_lead, 'debit');
                                //Log Credits transaction data & update user net credits - End

                                //Update Lead View Count
                                $this->update_lead_view_count($student_lead_id);

                            } else {

                                $hlink = '#';
                                if($this->ion_auth->is_tutor())
                                    $hlink = URL_TUTOR_LIST_PACKAGES;
                                else if($this->ion_auth->is_institute())
                                    $hlink = URL_INSTITUTE_LIST_PACKAGES;

                                $CI->prepare_flashmessage(get_languageword('you_don\'t_have_enough_credits_to_view_the_lead_details. Please')." <a href='".$hlink."'><strong>".get_languageword('_get_credits_here.')."</strong></a> ", 2);
                                return redirect(URL_HOME_SEARCH_STUDENT_LEADS);
                            }
                        }
                    }

                }


                $student_details[0]->lead_details = $lead_details;
            }

             //Student's Gallery
            $student_gallery_query = "SELECT image_title, image_name FROM ".$this->db->dbprefix('gallery')." WHERE user_id=".$student_id." AND image_status='Active' ORDER BY image_order ASC";
            $student_details[0]->student_gallery = $this->db->query($student_gallery_query)->result();

            return $student_details;

        } else return array();

    }


    function is_already_viewed_the_lead($user_id = "", $reference_table = "", $reference_id = "")
    {
        if(empty($user_id) || empty($reference_table) || empty($reference_id))
            return FALSE;

        $is_exist = $this->db->select('id')->get_where(TBL_USER_CREDIT_TRANSACTIONS, array('user_id' => $user_id, 'reference_table' => $reference_table, 'reference_id' => $reference_id))->row();
        if(count($is_exist) > 0)
            return TRUE;
        else
            return FALSE;
    }


    function update_lead_view_count($lead_id = "")
    {
        if(empty($lead_id))
            return NULL;

        $query = "UPDATE ".TBL_STUDENT_LEADS." SET no_of_views=no_of_views+1 WHERE id=".$lead_id." ";

        $this->db->query($query);

        return $this->db->affected_rows();
    }



    function is_uploaded_certificates($user_id = "")
    {
        if(empty($user_id))
            return FALSE;

        $user_type = getUserType($user_id);

        $sub_query = "SELECT certificate_id
                        FROM ".$this->db->dbprefix('certificates')." c
                        WHERE c.certificate_for =  '".$user_type."'
                        AND c.status =  'Active'";

        $admin_req_cert_cnt = $this->db->query($sub_query)->num_rows();


        $query = "SELECT uc.admin_certificate_id
                    FROM ".$this->db->dbprefix('users_certificates')." uc
                    WHERE uc.admin_certificate_id !=0
                    AND uc.user_id =".$user_id."
                    AND EXISTS (

                    ".$sub_query."
                    )";

        $user_uploaded_cert_cnt = $this->db->query($query)->num_rows();


        return ($user_uploaded_cert_cnt >= $admin_req_cert_cnt) ? TRUE : FALSE;
    }


    function get_faqs()
    {
       $faqData = $this->db->get_where($this->db->dbprefix('faqs'), array('status' => 'Active'))->result();
       return $faqData;
    }

    function get_inst_profile($inst_slug= " ")
    {
        if(empty($inst_slug))
            return NULL;

        $adm_approval_cond  = "";

        if(strcasecmp(get_system_settings('need_admin_for_institute'), 'yes') == 0) {

            $adm_approval_cond = ' AND u.admin_approved = "Yes" ';
        }

        $inst_id = $this->get_uid_by_slug($inst_slug);


        $inst_info_query = "SELECT u.* FROM ".$this->db->dbprefix('users')." u WHERE u.active=1 AND u.is_profile_update=1 AND u.slug='".$inst_slug."' ".$adm_approval_cond." ";

        $inst_details = $this->db->query($inst_info_query)->result();

        if(empty($inst_details)) {
            return array();
        }

        //Institute Offered Courses
        $inst_offered_courses_query=" SELECT ic.course_id, c.slug, c.name FROM ". $this->db->dbprefix('inst_offered_courses')." ic INNER JOIN  ". $this->db->dbprefix('categories')."  c ON c.id=ic.course_id WHERE ic.inst_id= ".$inst_id." GROUP BY ic.course_id";
        $inst_details[0]->institute_offered_courses = $this->db->query($inst_offered_courses_query)->result();


        //Institute Tutoring Locations
        $inst_tutoring_locations_query = "SELECT l.location_name FROM ".$this->db->dbprefix('inst_locations')." il INNER JOIN ".TBL_LOCATIONS." l ON l.id=il.location_id WHERE l.status='Active' AND il.status=1 AND il.inst_id=".$inst_id." ";
        $inst_details[0]->institute_tutoring_locations = $this->db->query($inst_tutoring_locations_query)->result();


        //Institute's Gallery
        $inst_gallery_query = "SELECT image_title, image_name FROM ".$this->db->dbprefix('gallery')." WHERE user_id=".$inst_id." AND image_status='Active' ORDER BY image_order ASC";
        $inst_details[0]->inst_gallery = $this->db->query($inst_gallery_query)->result();


        return $inst_details;
    }

    function get_institute_batches_info_by_course($course_id="",$inst_id="",$batch_id="")
    {
        $query = "select *, (select username from ".$this->db->dbprefix('users')." where p.tutor_id = id) as tutorname from ". $this->db->dbprefix('inst_batches')." p where course_id = ".$course_id ." AND inst_id =".$inst_id." AND batch_id = ".$batch_id;
        $institute_batches_info_by_course = $this->db->query($query)->result();
        return $institute_batches_info_by_course;
    }


    function get_inst_batch_details($batch_id = "")
    {
        if(empty($batch_id))
            return NULL;

        $rs = $this->db->select('*')
                       ->get_where(
                                    $this->db->dbprefix('inst_batches'), 
                                    array(
                                            'batch_id' => $batch_id, 
                                            'status' => 1
                                         )
                                    );

        return ($rs->num_rows() > 0) ? $rs->row_array() : NULL;

    }

    // get testimonials
    function get_site_testimonials()
    {
        $site_testimonials_query = "select * from ".$this->db->dbprefix('sitetestimonials')." where status='Active'";
        $site_testimonials = $this->db->query($site_testimonials_query)->result();
        return $site_testimonials;
    }

    function get_scroll_news()
    {
        $scroll_news= $this->db->get_where($this->db->dbprefix('scroll_news'),array('status' => 'Active'))->result();
        return $scroll_news;
    } 


    function get_tutor_reviews($tutor_slug = "")
    {
       $tutor_id = $this->get_uid_by_slug($tutor_slug);
       $query = "select  u.username as student_name,u.gender,u.photo,c.name as course,tr.rating,tr.comments,tr.created_at as posted_on from ".$this->db->dbprefix('tutor_reviews')." tr inner join ".$this->db->dbprefix('users')." u on tr.student_id = u.id inner join ".$this->db->dbprefix('categories')." c on tr.course_id=c.id where tr.tutor_id=".$tutor_id." and tr.status='Approved' ORDER by rating DESC limit 0,5";
     
       $tutor_reviews = $this->db->query($query)->result();
       return $tutor_reviews;

    }  

   function get_tutor_rating($tutor_slug= "")
   {
       $tutor_id = $this->get_uid_by_slug($tutor_slug);
       if(empty($tutor_id))
           return NULL;
             
       $query = "select count(*) as no_of_ratings, (sum(rating)/count(*)) as avg_rating from ".$this->db->dbprefix('tutor_reviews')." where tutor_id=".$tutor_id." AND status='Approved'";
       $tutor_ratings = $this->db->query($query)->row();
       return $tutor_ratings;
   }

   function get_home_tutor_ratings()
   {
        $query = "select u.username,u.photo,u.qualification,u.gender,u.slug,avg(tr.rating)as rating  from ".$this->db->dbprefix('tutor_reviews')." tr join ".$this->db->dbprefix('users')." u on tr.tutor_id = u.id group by tr.tutor_id having avg(tr.rating)>=3";
        $tutor_home_rating = $this->db->query($query)->result();
        return $tutor_home_rating;

   }





}
?>