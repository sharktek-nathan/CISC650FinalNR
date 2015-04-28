<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Analyze extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        require(APPPATH.'libraries/Pcap_analyzer.php');
    }
    
    
    function index()
    {	
        if(!$this->input->post('file_location')) {
            $this->session->set_flashdata('danger', '<b>Sorry!</b> We could not find the file you requested!');
            redirect('upload','refresh');
        }
        $file_location = $this->input->post('file_location');
        
        $data['dump'] = dump_pcap($file_location, FALSE);

        $header_data['success'] = $this->session->flashdata('success');
        $header_data['info'] = $this->session->flashdata('info');
        $header_data['warning'] = $this->session->flashdata('warning');
        $header_data['danger'] = $this->session->flashdata('danger');
        $this->load->view('templates/header', $header_data);
        $this->load->view('analyze/index', $data);
        $this->load->view('templates/footer');
    }
    
    function sample($file)
    {	
        if(!$file) {
            $this->session->set_flashdata('danger', '<b>Sorry!</b> We could not find the file you requested!');
            redirect('upload','refresh');
        }
        switch($file) {
            case 'shutterstock':
                $file_location = "/www/pcapviewer.com/samples/shutterstock.pcap";
                break;
            case 'images_search':
                $file_location = "/www/pcapviewer.com/samples/images_search.pcap";
                break;
            case 'request':
                $file_location = "/www/pcapviewer.com/samples/request.pcap";
                break;
            case 'response':
                $file_location = "/www/pcapviewer.com/samples/responses.pcap";
                break;
            case 'test':
                $file_location = "/www/pcapviewer.com/samples/test.pcap";
                break;
            
        } 
        
        $data['dump'] = dump_pcap($file_location, FALSE);

        $header_data['success'] = $this->session->flashdata('success');
        $header_data['info'] = $this->session->flashdata('info');
        $header_data['warning'] = $this->session->flashdata('warning');
        $header_data['danger'] = $this->session->flashdata('danger');
        $this->load->view('templates/header', $header_data);
        $this->load->view('analyze/index', $data);
        $this->load->view('templates/footer');
    }
    
}