<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token extends CI_Controller {

	
    public function __construct() {

		parent::__construct();

		$this->load->model('Mgmail');

        
        
    

    }
/**
 * Funcion que recibe el codigo de acceso para crear el token
 * 
 */
	public function index()
	{

            $tokenPath = 'token.json'; 
            $codigo = $_POST['token'];
            $authCode = trim($codigo);
            $cliente = $this->Mgmail->getClient();
            // Cambia el codigo de autorizacion por el token
            $accessToken = $cliente->fetchAccessTokenWithAuthCode($authCode);
            $cliente->setAccessToken($accessToken);
            // comprueba si hay algun error
            if (array_key_exists('error', $accessToken)) {
            
                    throw new Exception(join(', ', $accessToken));
            }
                    
            // Guarda el token en un archivo.
            if (!file_exists(dirname($tokenPath))) {
            
               mkdir(dirname($tokenPath), 0700, true);
            
            }
            file_put_contents($tokenPath, json_encode($cliente->getAccessToken()));
             /**
              * hacemos la llamada para que compruebe el token
               */   
            $this->Mgmail->checkToken();
            
            }

       
		
	
    }
    