<?php

namespace Drupal\Tests\govcore\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\govcore\Update\Update405;

/**
 * @coversDefaultClass \Drupal\govcore\Update\Update405
 *
 * @group govcore
 */
class Update405Test extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system'];

  /**
   * @covers ::enableAutosaveForm
   * @covers ::enableRedirect
   */
  public function testUpdate() {
    $moduleHandler = $this->container->get('module_handler');

    $this->assertFalse($moduleHandler->moduleExists('autosave_form'));
    $this->assertFalse($moduleHandler->moduleExists('conflict'));
    $this->assertFalse($moduleHandler->moduleExists('redirect'));

    $update = Update405::create($this->container);
    $update->enableAutosaveForm();
    $update->enableRedirect();

    $moduleHandler = $this->container->get('module_handler');

    $this->assertTrue($moduleHandler->moduleExists('autosave_form'));
    $this->assertTrue($moduleHandler->moduleExists('conflict'));
    $this->assertTrue($moduleHandler->moduleExists('redirect'));
  }

}
