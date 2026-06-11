<?php
/**
 * Central asset registration for Testimonials Carousel Elementor widgets.
 *
 * @package TestimonialsCarouselElementor
 */

namespace TestimonialsCarouselElementor;

defined('ABSPATH') || exit;

/**
 * Registers shared scripts/styles once and picks the correct frontend handler per Elementor version.
 */
class Testimonials_Carousel_Assets
{

  const LEGACY_ELEMENTOR_VERSION = '3.5.0';

  /**
   * @var bool
   */
  private static $registered = false;

  /**
   * Register swiper + widget handler scripts (idempotent).
   */
  public static function register()
  {
    if (self::$registered) {
      return;
    }

    self::$registered = true;

    $plugin_file = TESTIMONIALS_CAROUSEL_ELEMENTOR;
    $version     = defined('TESTIMONIALS_VERSION') ? TESTIMONIALS_VERSION : '12.0.0';

    if (!wp_script_is('swiper', 'registered')) {
      wp_register_script(
        'swiper',
        plugins_url('/assets/js/swiper-bundle.min.js', $plugin_file),
        [],
        $version,
        true
      );
    }

    if (!wp_style_is('swiper', 'registered')) {
      wp_register_style(
        'swiper',
        plugins_url('/assets/css/swiper-bundle.min.css', $plugin_file),
        [],
        $version
      );
    }

    $handler_deps = [];

    if (self::uses_modern_handler()) {
      $handler_deps[] = 'jquery';
      $handler_deps[] = 'elementor-frontend-modules';
      $handler_deps[] = 'elementor-frontend';
      $handler_url    = plugins_url('/assets/js/testimonials-carousel-widget-handler.min.js', $plugin_file);
    } else {
      $handler_deps[] = 'swiper';
      $handler_deps[] = 'jquery';
      $handler_url    = plugins_url('/assets/js/testimonials-carousel-widget-old-elementor-handler.min.js', $plugin_file);
    }

    wp_register_script(
      'testimonials-carousel-widget-handler',
      $handler_url,
      $handler_deps,
      $version,
      true
    );
  }

  /**
   * Script handles for Elementor widgets (avoids loading plugin Swiper over Elementor's).
   *
   * @param array $extra Extra script handles (e.g. ai-btn).
   * @return array
   */
  public static function get_widget_script_depends(array $extra = array())
  {
    $scripts = array('testimonials-carousel-widget-handler');

    if (!self::uses_modern_handler()) {
      return array_merge(array('swiper'), $scripts, $extra);
    }

    if (in_array('swiper', $extra, true)) {
      return array_merge(array('swiper'), $scripts, array_diff($extra, array('swiper')));
    }

    return array_merge($scripts, $extra);
  }

  /**
   * Elementor 3.5+ exposes element settings to frontend handlers via elementorModules.
   *
   * @return bool
   */
  public static function uses_modern_handler()
  {
    return defined('ELEMENTOR_VERSION')
      && version_compare(ELEMENTOR_VERSION, self::LEGACY_ELEMENTOR_VERSION, '>=');
  }

  /**
   * @param string $css_file Filename under assets/css/.
   */
  public static function register_style($handle, $css_file)
  {
    self::register();

    if (!wp_style_is($handle, 'registered')) {
      wp_register_style(
        $handle,
        plugins_url('/assets/css/' . $css_file, TESTIMONIALS_CAROUSEL_ELEMENTOR),
        [],
        defined('TESTIMONIALS_VERSION') ? TESTIMONIALS_VERSION : '12.0.0'
      );
    }
  }

  /**
   * Swiper v11 bundle for widgets that require it.
   */
  public static function register_swiper_v11()
  {
    $version = defined('TESTIMONIALS_VERSION') ? TESTIMONIALS_VERSION : '12.0.0';

    wp_register_style(
      'swiper',
      plugins_url('/assets/css/swiper-bundle-v11.min.css', TESTIMONIALS_CAROUSEL_ELEMENTOR),
      [],
      $version
    );

    wp_register_script(
      'swiper',
      plugins_url('/assets/js/swiper-bundle-v11.min.js', TESTIMONIALS_CAROUSEL_ELEMENTOR),
      [],
      $version,
      true
    );

    global $wp_scripts;

    if (isset($wp_scripts->registered['testimonials-carousel-widget-handler'])) {
      $handler = $wp_scripts->registered['testimonials-carousel-widget-handler'];

      if (!in_array('swiper', $handler->deps, true)) {
        $handler->deps[] = 'swiper';
      }
    }
  }
}
