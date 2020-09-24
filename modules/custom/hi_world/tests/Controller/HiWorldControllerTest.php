<?php

namespace Drupal\hi_world\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the hi_world module.
 */
class HiWorldControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "hi_world HiWorldController's controller functionality",
      'description' => 'Test Unit for module hi_world and controller HiWorldController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests hi_world functionality.
   */
  public function testHiWorldController() {
    // Check that the basic functions of module hi_world.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
