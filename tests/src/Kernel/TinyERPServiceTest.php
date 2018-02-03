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
use Drupal\Tests\simpletest\Unit\WebTestBaseTest;
use tiny\TinyERPService;

class TinyERPServiceTest extends KernelTestBase {

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        global $settings;
        parent::setUp();
        print_r($this->config('tiny_token'));
    }

    public function testTokenSetup() {

    //print_r($settings);
    //$this->assertNotNull(Settings::get('tiny_token'));
  }

  public function testCreateContact() {
    //$tiny = new TinyERPService(\Drupal::state()->get('tiny_token'));
  }
}