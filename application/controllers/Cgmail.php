<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ERROR | E_PARSE);

class Cgmail extends CI_Controller {

	
    public function __construct() {

        parent::__construct();
        
        set_time_limit(10);

		$this->load->model('Mgmail');

        
        
    

    }

	public function index()
	{

        $this->cliente = $this ->Mgmail->checkToken();
        $this->clientResult($this->cliente);
		
	
    }
    

    private function clientResult($data){

        if($data == 0){

            $output = [
                "vista_principal" => "vToken",
                "cliente" =>$data,  
            ];

            $this->session->set_userdata($output);

            $this->load->view('vhome',$this->session->userdata());

            


        }else{

            $output = [
                "vista_principal" => "vCorreos",
                "cliente" =>$data,  
            ];

            $this->session->set_userdata($output);

            $this->load->view('vhome',$this->session->userdata());
        }
    }




	

		
	

	
}