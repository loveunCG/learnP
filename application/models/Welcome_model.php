<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome_model extends CI_Model{

    public function getCountry($page){
        $offset = 10*$page;
        $limit = 10;
        $sql = "select * from countries limit $offset ,$limit";
        $result = $this->db->query($sql)->result();
        return $result;
    }
}