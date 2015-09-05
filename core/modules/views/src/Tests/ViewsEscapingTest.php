<?php

/**
 * @file
 * Contains \Drupal\views\Tests\ViewsEscapingTest.
 */

namespace Drupal\views\Tests;

/**
 * Tests output of Views.
 *
 * @group views
 */
class ViewsEscapingTest extends ViewTestBase {

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = array('test_page_display');

  /**
   * Used by WebTestBase::setup()
   *
   * We need theme_test for testing against test_basetheme and test_subtheme.
   *
   * @var array
   *
   * @see \Drupal\simpletest\WebTestBase::setup()
   */
  public static $modules = array('views', 'theme_test');

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->enableViewsTestModule();
  }

  /**
   * Tests for incorrectly escaped markup in the views-view-fields.html.twig.
   */
  public function testViewsViewFieldsEscaping() {
    // Test with system theme using theme function.
    $this->drupalGet('test_page_display_200');

    // Assert that there are no escaped '<'s characters.
    $this->assertNoEscaped('<');

    // Install theme to test with template system.
    \Drupal::service('theme_handler')->install(array('views_test_theme'));

    // Make base theme default then test for hook invocations.
    $this->config('system.theme')
        ->set('default', 'views_test_theme')
        ->save();
    $this->assertEqual($this->config('system.theme')->get('default'), 'views_test_theme');

    $this->drupalGet('test_page_display_200');

    // Assert that we are using the correct template.
    $this->assertText('force', 'The force is strong with this one');

    // Assert that there are no escaped '<'s characters.
    $this->assertNoEscaped('<');
  }

}