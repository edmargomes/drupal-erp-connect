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
  const CONTACT_UPDATE_URL = 'https://api.tiny.com.br/api2/contato.alterar.php';
  const PRODUCT_CREATE_URL = 'https://api.tiny.com.br/api2/produto.incluir.php';
  const PRODUCT_UPDATE_URL = 'https://api.tiny.com.br/api2/produto.alterar.php';

  public function __construct($token) {
    $this->token = $token;
  }

  /**
   * @param $url
   *  API URL to call
   * @param $data
   *  Data paramns to URL
   * @return bool|string
   *  Response about the call
   * @throws \Exception
   */
  private function callApi($url, $data) {
    $params = array('http' => array(
      'method' => 'POST',
      'content' => $data
    ));

    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if (!$fp) {
      throw new \Exception("Problem with ". $url . "," . $php_errormsg);
    }
    $response = @stream_get_contents($fp);
    if ($response === false) {
      throw new \Exception("Problem with return from ". $url . "," . $php_errormsg);
    }

    return $response;
  }
  /**
   * Create contact in TinyERP
   * Info: https://tiny.com.br/info/api/api2-contatos-incluir
   * @param $contact
   * Exemple:
   * {
      "contatos": [
      {
        "contato": {
          "sequencia": "1",
          "codigo": "1235",
          "nome": "Contato Teste 2",
          "tipo_pessoa": "F",
          "cpf_cnpj": "22755777850",
          "ie": "",
          "rg": "1234567890",
          "im": "",
          "tipo_negocio": "",
          "endereco": "Rua Teste",
          "numero": "123",
          "complemento": "sala 2",
          "bairro": "Teste",
          "cep": "95700-000",
          "cidade": "Bento Gonçalves",
          "uf": "RS",
          "pais": "",
          "contatos": "Contato Teste",
          "fone": "(54) 3055 3808",
          "fax": "",
          "celular": "",
          "email": "teste@teste.com.br",
          "id_vendedor": "123",
          "situacao": "A",
          "obs": "teste de obs"
        }
      }]
   * }
   * @return string
   *  Response with result about register
   * @throws \Exception
   */
  public function createContact($contact) {

    $data = "token=$this->token&contato=$contact&formato=JSON";

    return $this->callApi(self::CONTACT_CREATE_URL, $data);
  }

  public function updateContact($contact) {

    $data = "token=$this->token&contato=$contact&formato=JSON";

    return $this->callApi(self::CONTACT_UPDATE_URL, $data);
  }


  /**
   * Create product in the Tiny ERP
   * @param $product
   * @return bool|string
   * @throws \Exception
   */
  public function createProduct($product) {
    $data = "token=$this->token&produto=$product&formato=JSON";

    return $this->callApi(self::PRODUCT_CREATE_URL, $data);
  }

  public function updateProduct($product) {
    $data = "token=$this->token&produto=$product&formato=JSON";

    return $this->callApi(self::PRODUCT_UPDATE_URL, $data);
  }
}