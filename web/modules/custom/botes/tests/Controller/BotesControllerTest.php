<?php

namespace Drupal\botes\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the botes module.
 */
class BotesControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "botes BotesController's controller functionality",
      'description' => 'Test Unit for module botes and controller BotesController.',
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
   * Tests botes functionality.
   */
  public function testBotesController() {
    // Check that the basic functions of module botes.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
