<?php
class Upload_model extends CI_Model {


    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function insert_upload_record($data)
    {
        $this->db->insert('upload_records', $data); 
    }
}