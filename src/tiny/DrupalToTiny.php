<?php
/**
 * Created by PhpStorm.
 * User: edmar
 * Date: 04/02/18
 * Time: 23:17
 */

namespace Drupal\erp_connect\tiny;

class DrupalToTiny
{

  /**
   * @param $response
   *  Tiny ERP response in json format.
   * @param $tinySettings
   *  Tiny ERP Settings
   * @param $typeClass
   *  Class to load entity and update value.
   */
  public static function addTinyIdInEntity($response, $tinySettings, $typeClass) {
    $response = json_decode($response);
    foreach ($response->retorno->registros as $register) {
      if ($register->registro->status == "OK") {
        $entity = $typeClass::load($register->registro->sequencia);
        $entity->set($tinySettings['erp_id'], $register->registro->id);
        $entity->save();
      }
    }
  }

  public static function userToContactTiny($account, $tinySettings) {
    $contact = new \StdClass();
    $contact->sequencia = $account->id();
    $contact->codigo = $account->uuid();
    $contact->email = $account->getEmail();
    $contact->situacao = 'A';

    foreach ($tinySettings['profile_fields']['address'] as $key => $fields) {
        foreach ($fields as $field) {
            $contact->$key .= $account->address->list[0]->values[$field] . ' ';
        }
    }

    foreach ($tinySettings['profile_fields'] as $fieldName => $field) {
      if ($account->get($fieldName)->value != "") {
        $contact->$field = $account->get($fieldName)->value;
      }
    }

    $contact->tipo_pessoa = strlen($contact->cpf_cnpj) == 11 ? 'F' : 'J';

    $contact->tipos_contato = [];
    foreach ($account->getRoles() as $key => $type) {
      if ($tinySettings['roles'][$type]) {
        $userType = new \StdClass();
        $userType->tipo = $tinySettings['profiles'][$type];
        $contact->tipos_contato[] = $userType;
      }
    }

    $tinyId = $account->get($tinySettings['erp_id']);
    if ($tinyId && $tinyId->value != "")
      $contact->id = $tinyId->value;

    return $contact;
  }
  /**
   * @param $product
   *  Drupal commerce product.
   * @param $tinySettings
   *  TinyERP settings
   * @return \StdClass
   *  TinyERP product
   */
  public static function productToProductTiny($product, $tinySettings) {
    $productERP = new \StdClass();
    $productERP->sequencia = $product->get('variation_id')->value;
    $productERP->codigo = $product->getSku();
    $productERP->nome = $product->getTitle();
    $productERP->unidade = 'Pç';
    $productERP->preco = $product->getPrice()->getNumber();
    $productERP->origem = '0';
    $productERP->situacao = 'A';
    $productERP->tipo = 'P';

    $tinyId = $product->get($tinySettings['erp_id']);
    if ($tinyId && $tinyId->value != "")
      $productERP->id = $tinyId->value;

    return $productERP;
  }
}