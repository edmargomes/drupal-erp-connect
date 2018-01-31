<?php
/**
 * Created by PhpStorm.
 * User: edmar
 * Date: 31/01/18
 * Time: 21:19
 */

namespace erp;


class TinyERPService {

  public static function createUser() {

    $url = 'https://api.tiny.com.br/api2/contato.incluir.php';
    $token = 'd2d97cdb5ba38db662abb5741cac52f6ac05265f';
    $contato = '{
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
        "tipos_contato": [{
          "tipo": "Cliente"
        }],
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
        "situacao": "A",
        "obs": "teste de obs"
      }
    }
  ]
}';
    $data = "token=$token&contato=$contato&formato=JSON";

      $params = array('http' => array(
        'method' => 'POST',
        'content' => $data
      ));

      $ctx = stream_context_create($params);
      $fp = @fopen($url, 'rb', false, $ctx);
      if (!$fp) {
        throw new Exception("Problema com $url, $php_errormsg");
      }
      $response = @stream_get_contents($fp);
      if ($response === false) {
        throw new Exception("Problema obtendo retorno de $url, $php_errormsg");
      }

    return $response;
  }
}