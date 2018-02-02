<?php
/**
 * Created by PhpStorm.
 * User: edmar
 * Date: 01/02/18
 * Time: 10:21
 */

namespace tiny;

use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\simpletest\WebTestBase;
use tiny\TinyERPService;

class TinyERPServiceTest extends WebTestBase {

  public function testTokenSetup() {
    print_r(Settings::get('tiny_token'));
    $this->assertNotNull(Settings::get('tiny_token'));
  }

  public function testCreateContact() {
    //$tiny = new TinyERPService(\Drupal::state()->get('tiny_token'));
  }
}