<?php
/**
 * Created by PhpStorm.
 * User: edmar
 * Date: 31/01/18
 * Time: 21:19
 */

namespace Drupal\erp_connect\tiny;

class TinyERPService {

  private $token = '';
  const CONTACT_CREATE_URL = 'https://api.tiny.com.br/api2/contato.incluir.php';

  public function __construct($token) {
    $this->token = $token;
  }

  public function createContact($contact) {

    $data = "token=$this->token&contato=$contact&formato=JSON";

      $params = array('http' => array(
        'method' => 'POST',
        'content' => $data
      ));

      $ctx = stream_context_create($params);
      $fp = @fopen(self::CONTACT_CREATE_URL, 'rb', false, $ctx);
      if (!$fp) {
        throw new \Exception("Problem with ". self::CONTACT_CREATE_URL . "," . $php_errormsg);
      }
      $response = @stream_get_contents($fp);
      if ($response === false) {
        throw new \Exception("Problem with return from ". self::CONTACT_CREATE_URL . "," . $php_errormsg);
      }

    return $response;
  }
}