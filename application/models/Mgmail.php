<?php

use PhpMimeMailParser;
require __DIR__ . '/vendor/autoload.php';

class Mgmail extends CI_Model
{
   /**
 * Devuelve el cliente autorizado de GMAIL.
 * @return Google_Client objeto del cliente
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Gmail API PHP Quickstart');
    $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    return $client;
}

/**
 * Comprueba el token y devuelve el cliente con el token si este existe
 * Si no existe nos devuelve el cliente vacio
 * @return data cliente de Google_Service 
 */
function checkToken(){
  $myClient = $this->getClient();
  $data;

  // Carga un token autorizado si este existe
  // Este archivo se crea atumaticamente cuando el usuario inicia por primera vez y concede permisos para leer sus correos
  $tokenPath = 'token.json';
      if (file_exists($tokenPath)) {

          $accessToken = json_decode(file_get_contents($tokenPath), true);

          $myClient->setAccessToken($accessToken);

          $data=$this->getResponse($myClient);

  // Si no hay token o se ha expirado        
      }else if ($myClient->isAccessTokenExpired()) {

      // Refrescamos el token si es posible
              if ($myClient->getRefreshToken()) {

                  $myClient->fetchAccessTokenWithRefreshToken($myClient->getRefreshToken());

                  $data=$this->getResponse($myClient);
                 
              } else {

                  // Pedimos un nuevo token
                  $authUrl = $myClient->createAuthUrl();
                  echo '<script type="text/javascript" language="javascript">
                  window.open("'.$authUrl.'");
                  </script>';
                  $data =0;
                }
                  
          }

          return $data;
      
}


/**
 * Devuelve la respuesta del cliente
 * @param  Google_Service $clienteRecibido instancia de gmail.
 */
function getResponse($clientRecibido){

  //usuario
   $user ='me';
  // array de mensajes
  $mensajes = array(); 
  //creamos un servicio de gmail con el cliente recibido
  $service = new Google_Service_Gmail($clientRecibido);

  //obtenemos el perfil del usuario
  $profile = $service->users->getProfile($user);

  //obtiene las listas de mensajes
 $mensajes= $this->listMessages($service, $user);
 
 //si el array de mensajes esta vacio
      if($mensajes == null){

        echo 'Sin mensajes en la bandeja de entrada';

//si el array no esta vacio
      }else{

        $this->getMessage($service,$user,$mensajes[0]->getId());

      }
 //Enumera todas las etiquetas en el buzón del usuario. (labels.listUsersLabels)
  //$listLabel = $service->users_labels->listUsersLabels($user);
  //obtiene todas las etiquetas del buzón
  //$labels=$listLabel->getLabels();
    }
    
/**
 * Devuelve una array de mensajes
 *  @param  Google_Service_Gmail $service instancia de gmail.
 *  @param  string $userId Email del usuario, se le asigna el valor 'me'.
 * 
 */
function listMessages($service, $userId) {
  $pageToken = NULL;
  $optParams = [];
  $messages = array();
  $optParams['q'] = 'is:unread'; // Devuelve solo los que tienen la etiqueta unread(no leidos)
  $optParams['labelIds'] = 'INBOX'; // Solo muestra los de la carpeta inbox

  do {

    try {

      if ($pageToken) {

        //paginacion de google
        $optParam['pageToken'] = $pageToken;  

      }
      //obtenemos la lista de mensajes del servicio por usuario
      $messagesResponse = $service->users_messages->listUsersMessages($userId, $optParams);

      //si hay mensajes
      if ($messagesResponse->getMessages()) {

        //los añadimos al array de mensajes
        $messages = array_merge($messages, $messagesResponse->getMessages());

        //asignamos el valor de la pagina donde nos encontramos
        $pageToken = $messagesResponse->getNextPageToken();

      }

    } catch (Exception $e) {

      print 'An error occurred: ' . $e->getMessages();
    }

  } while ($pageToken);

//devolvemos el array de mensajes
  return $messages;
}


/**
 * Obtiene los mensajes por id.
 *
 * @param  Google_Service_Gmail $service instancia de gmail.
 * @param  string $userId email del usuario. Asignado el valor'me'
 * @param  string $messageId Id del mensaje a obtener.
 * @return Google_Service_Gmail_Message $message mensaje a devolver.
 */
function getMessage($service, $userId, $messageId) {
  try {
    //obtenemos el mensaje por id
    $message = $service->users_messages->get($userId, $messageId,["format"=>"full"]);

/////////////////////////////////////////////////////////////////////////////////////
    print 'Message with ID: ' . $message->getId() . ' unread.'.'</br>';

    //sacamos la etiqueta payload, donde se almacena el contenido del mensaje
    $payload = $message->getPayload();

    //sacamos las cabeceras
    $headers = $payload->getHeaders();
    //quien lo ha enviado
    $send =$headers[0]->value;
    //hora de cuando se ha recibido
    $recived = $headers[17]->value;

    //obtenemos las partes del payload    
    $parts = $payload->getParts();
    //obtenemos la etiqueta body
   
    for ($i=0; $i<= sizeof($parts); $i++) {
      
      if($parts[$i]->parts->body->data!= null){
        
        //obtenemos el valor decodificado de la etiqueta body
        $value []= $this->gmailBodyDecode($body->data);
        $cont++;
        echo json_encode($value).$cont;
        
      }
    }

    $body= $parts[0]->body;
    //obtenemos el valor decodificado de la etiqueta body
    $value = $this->gmailBodyDecode($body->data);
    //obtenemos los archivos adjuntos si existen
    foreach ($parts as $adjunto) {

      if ($adjunto->filename != null){
        echo "existo";
        $filename = $adjunto->filename;
        $attId = $adjunto->body->attachmentId;

        

      }
    }



    $messageFormated = [
      "remitente" => $send,
      "hora" => $recived, 
      "body" => $value
  ];
      echo json_encode($messageFormated); 
    //return $messageFormated;
  } catch (Exception $e) {
    print 'An error occurred: ' . $e->getMessage();
  }
}

function gmailBodyDecode($data) {

  $data = base64_decode(str_replace(array('-', '_'), array('+', '/'), $data)); 
 
  $data = imap_qprint($data);

  return($data);
} 



}
