<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Upload extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        //$this->load->model('dashboard_model');
        $this->load->model('upload_model');
        $this->load->helper('form');
        $this->load->helper('html');
    }
    
    
    function index()
    {	
        $data[] = '';
        $header_data['success'] = $this->session->flashdata('success');
        $header_data['info'] = $this->session->flashdata('info');
        $header_data['warning'] = $this->session->flashdata('warning');
        $header_data['danger'] = $this->session->flashdata('danger');
        $this->load->view('templates/header', $header_data);
        $this->load->view('upload/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function save_file() {
    /*
    * This function handles a single file upload from the web interface.
    *
    * Required parameters: $_FILES['file']
    */
        if (!empty($_FILES)) {

            if($this->input->post('user_ip')) {
                $user_ip = $this->input->post('user_ip');
            } else {
                exit("Your IP address could not be resolved!");
            }

            // Get the file info later user for its exentsion
            $fileinfo = pathinfo($_FILES['file']['name']);

            //create temp path for files
            $sys_tmp_directory = sys_get_temp_dir();
            $new_tmp_directory = $sys_tmp_directory . '/' . time() . uniqid() . '/';
            mkdir($new_tmp_directory);
            chmod($new_tmp_directory, 0777);
            chgrp($new_tmp_directory, 'apache');

            // Represents the temporary name assigned by PHP curl
            $tmp_name = $_FILES['file']['tmp_name'];

            // Create a unique name for the converted file
            $tmp_file_path = $new_tmp_directory . time() . uniqid() . "." . $fileinfo['extension'];

            // Verify that the uploaded file has been transferred
            if (move_uploaded_file($tmp_name, $tmp_file_path)) {
                $data = array('user_ip' => $user_ip, 'file_name' => $fileinfo['basename'], 'tmp_location' => $tmp_file_path);
                $this->upload_model->insert_upload_record($data);
                echo $tmp_file_path;
            } else { 
                $this->session->set_flashdata('danger', '<b>Sorry!</b> Your upload failed.');
                redirect('upload','refresh');	 
            }
        } else {
            exit("No valid files were passed!");
        }
    }
}