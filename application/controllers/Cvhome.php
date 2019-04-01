<?php


class Cvhome extends CI_Controller {

	
    public function __construct() {

		parent::__construct();

		//$this->load->model('Mgmail');

        //$this->cliente = $this ->Mgmail->getClient();
        
        
    
    }

	public function index()
	{

        
        $output = [
            "vista_principal" => "inicio",
        ];

        $this->session->set_userdata($output);

        $this->load->view('vhome',$this->session->userdata());
		
	
    }
	

		
	

	
}