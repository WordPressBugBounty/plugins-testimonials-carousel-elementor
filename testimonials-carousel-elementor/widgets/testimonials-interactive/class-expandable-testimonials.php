<?php
/**
 * Expandable_Testimonials widget class.
 *
 * @category   Class
 * @package    TestimonialsCarouselElementor
 * @subpackage WordPress
 * @author     UAPP GROUP
 * @copyright  2026 UAPP GROUP
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @since      13.0.0
 * php version 7.4.1
 */

namespace TestimonialsCarouselElementor\Widgets;

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') || die();

/**
 * Expandable Testimonials widget.
 *
 * Accordion-style testimonials panel: each item expands to reveal
 * the full quote, body text, author and image.
 *
 * @since 13.0.0
 */
class Expandable_Testimonials extends Widget_Base
{
  public function get_name()
  {
    return 'expandable-testimonials';
  }

  public function get_title()
  {
    return __('Expandable Testimonials', 'testimonials-carousel-elementor');
  }

  public function get_icon()
  {
    return 'icon-expandable-testimonials';
  }

  public function get_categories()
  {
    return ['testimonials_interactive'];
  }

  public function get_style_depends()
  {
    return ['expandable-testimonials'];
  }

  public function get_script_depends()
  {
    return ['expandable-testimonials-handler'];
  }

  /**
   * Returns a single repeater slide default array.
   */
  protected function get_default_slide($quote, $text, $name, $company)
  {
    return [
        'et_quote'         => $quote,
        'et_text'          => $text,
        'et_name'          => $name,
        'et_company'       => $company,
        'et_rating_enable' => 'yes',
        'et_rating'        => 5,
    ];
  }

  protected function register_controls()
  {
    // Content Tab

    $this->start_controls_section('section_slides', [
        'label' => __('Testimonials', 'testimonials-carousel-elementor'),
    ]);

    $repeater = new Repeater();

    $repeater->add_control('et_quote', [
        'label'   => __('Quote', 'testimonials-carousel-elementor'),
        'type'    => Controls_Manager::TEXTAREA,
        'default' => __('Your testimonial headline quote.', 'testimonials-carousel-elementor'),
        'rows'    => 2,
    ]);

    $repeater->add_control('et_text', [
        'label'   => __('Body Text', 'testimonials-carousel-elementor'),
        'type'    => Controls_Manager::TEXTAREA,
        'default' => __('Detailed testimonial body text goes here.', 'testimonials-carousel-elementor'),
        'rows'    => 4,
    ]);

    $repeater->add_control('et_name', [
        'label'   => __('Author Name', 'testimonials-carousel-elementor'),
        'type'    => Controls_Manager::TEXT,
        'default' => __('John Doe', 'testimonials-carousel-elementor'),
    ]);

    $repeater->add_control('et_company', [
        'label'   => __('Company', 'testimonials-carousel-elementor'),
        'type'    => Controls_Manager::TEXT,
        'default' => __('Company Name', 'testimonials-carousel-elementor'),
    ]);

    $repeater->add_control('et_rating_enable', [
        'label'        => __('Rating', 'testimonials-carousel-elementor'),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => __('Show', 'testimonials-carousel-elementor'),
        'label_off'    => __('Hide', 'testimonials-carousel-elementor'),
        'return_value' => 'yes',
        'default'      => 'yes',
        'separator'    => 'before',
    ]);

    $repeater->add_control('et_rating', [
        'label'     => __('Rating Score', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::NUMBER,
        'min'       => 0,
        'max'       => 5,
        'step'      => 1,
        'default'   => 5,
        'condition' => ['et_rating_enable' => 'yes'],
    ]);

    $repeater->add_control('et_image', [
        'label'   => __('Image', 'testimonials-carousel-elementor'),
        'type'    => Controls_Manager::MEDIA,
        'default' => ['url' => Utils::get_placeholder_image_src()],
    ]);

    $this->add_control('testimonials', [
        'label'       => __('Testimonials', 'testimonials-carousel-elementor'),
        'type'        => Controls_Manager::REPEATER,
        'fields'      => $repeater->get_controls(),
        'default'     => [
            $this->get_default_slide(
                'Pivot creates innovative design solutions that inspire.',
                'Collaborate with a digital design agency that will make you proud. From strategy to launch, every step was thoughtful and transparent.',
                'Michael David',
                'SkyPulse'
            ),
            $this->get_default_slide(
                'Their team delivered beyond our expectations.',
                'From strategy to launch, every step was thoughtful and transparent. We saw measurable growth within the first quarter.',
                'Sarah Chen',
                'Nova Labs'
            ),
            $this->get_default_slide(
                'A partner that truly understands brand.',
                'The creative direction elevated our identity and resonated with customers across every touchpoint.',
                'James Rivera',
                'BrightPath'
            ),
            $this->get_default_slide(
                'Seamless collaboration from day one.',
                'Communication was clear, deadlines were met, and the final product exceeded what we imagined possible.',
                'Emily Watson',
                'CoreSync'
            ),
        ],
        'title_field' => '{{{ et_name }}} — {{{ et_company }}}',
    ]);

    $this->end_controls_section();

    // Options Section

    $this->start_controls_section('section_options', [
        'label' => __('Options', 'testimonials-carousel-elementor'),
    ]);

    $this->add_responsive_control('max_items', [
        'label'          => __('Max Visible Panels', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::NUMBER,
        'default'        => 4,
        'tablet_default' => 2,
        'mobile_default' => 1,
        'min'            => 1,
        'max'            => 4,
        'description'    => __('Maximum panels visible at once. On mobile always 1. When total exceeds this, arrow navigation appears.', 'testimonials-carousel-elementor'),
    ]);

    $this->add_control('default_active', [
        'label'       => __('Default Active Item', 'testimonials-carousel-elementor'),
        'type'        => Controls_Manager::NUMBER,
        'default'     => 0,
        'min'         => 0,
        'description' => __('Zero-based index of the initially expanded item.', 'testimonials-carousel-elementor'),
    ]);

    $this->add_control('show_navigation', [
        'label'                => __('Show Navigation', 'testimonials-carousel-elementor'),
        'type'                 => Controls_Manager::SWITCHER,
        'label_on'             => __('Yes', 'testimonials-carousel-elementor'),
        'label_off'            => __('No', 'testimonials-carousel-elementor'),
        'return_value'         => 'yes',
        'default'              => 'yes',
        'separator'            => 'before',
        'selectors'            => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-nav-visible: {{VALUE}};',
        ],
        'selectors_dictionary' => [
            'yes' => '1',
            ''    => '0',
        ],
    ]);

    $this->add_control('show_buttons', [
        'label'                => __('Show Arrow Buttons', 'testimonials-carousel-elementor'),
        'type'                 => Controls_Manager::SWITCHER,
        'label_on'             => __('Yes', 'testimonials-carousel-elementor'),
        'label_off'            => __('No', 'testimonials-carousel-elementor'),
        'return_value'         => 'yes',
        'default'              => 'yes',
        'condition'            => ['show_navigation' => 'yes'],
        'selectors'            => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-buttons-visible: {{VALUE}};',
        ],
        'selectors_dictionary' => [
            'yes' => '1',
            ''    => '0',
        ],
    ]);

    $this->add_control('show_counter', [
        'label'                => __('Show Counter', 'testimonials-carousel-elementor'),
        'type'                 => Controls_Manager::SWITCHER,
        'label_on'             => __('Yes', 'testimonials-carousel-elementor'),
        'label_off'            => __('No', 'testimonials-carousel-elementor'),
        'return_value'         => 'yes',
        'default'              => 'yes',
        'condition'            => ['show_navigation' => 'yes'],
        'selectors'            => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-counter-visible: {{VALUE}};',
        ],
        'selectors_dictionary' => [
            'yes' => '1',
            ''    => '0',
        ],
    ]);

    $this->add_control('autoplay_enabled', [
        'label'              => __('Autoplay', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SWITCHER,
        'label_on'           => __('Yes', 'testimonials-carousel-elementor'),
        'label_off'          => __('No', 'testimonials-carousel-elementor'),
        'return_value'       => 'yes',
        'default'            => '',
        'frontend_available' => true,
    ]);

    $this->add_control('autoplay_interval', [
        'label'              => __('Interval (ms)', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::NUMBER,
        'default'            => 4000,
        'min'                => 1000,
        'step'               => 500,
        'frontend_available' => true,
        'description'        => __('Auto-advance interval in milliseconds (minimum 1000).', 'testimonials-carousel-elementor'),
        'condition'          => ['autoplay_enabled' => 'yes'],
    ]);

    $this->add_control('loop', [
        'label'        => __('Loop', 'testimonials-carousel-elementor'),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => __('Yes', 'testimonials-carousel-elementor'),
        'label_off'    => __('No', 'testimonials-carousel-elementor'),
        'return_value' => 'true',
        'default'      => 'true',
        'description'  => __('Autoplay and arrows wrap around from last to first item.', 'testimonials-carousel-elementor'),
    ]);

    $this->add_control('show_name_accent', [
        'label'                => __('Name Accent Bar', 'testimonials-carousel-elementor'),
        'type'                 => Controls_Manager::SWITCHER,
        'label_on'             => __('Show', 'testimonials-carousel-elementor'),
        'label_off'            => __('Hide', 'testimonials-carousel-elementor'),
        'return_value'         => 'yes',
        'default'              => 'yes',
        'selectors'            => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-accent-visible: {{VALUE}};',
        ],
        'selectors_dictionary' => [
            'yes' => '1',
            ''    => '0',
        ],
    ]);

    $this->end_controls_section();

    // Style Tab

    $this->start_controls_section('section_style_layout', [
        'label' => __('Layout', 'testimonials-carousel-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]);

    $this->start_controls_tabs('layout_style_tabs');

    // Section tab
    $this->start_controls_tab('layout_section_tab', [
        'label' => __('Section', 'testimonials-carousel-elementor'),
    ]);

    $this->add_responsive_control('max_width', [
        'label'          => __('Max Width', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', '%', 'vw', 'custom'],
        'range'          => [
            'px' => ['min' => 200, 'max' => 2000, 'step' => 10],
            '%'  => ['min' => 10, 'max' => 100, 'step' => 1],
            'vw' => ['min' => 10, 'max' => 100, 'step' => 1],
        ],
        'default'        => ['unit' => '%', 'size' => 100],
        'tablet_default' => ['unit' => '%', 'size' => 100],
        'mobile_default' => ['unit' => '%', 'size' => 100],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-max-width: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_responsive_control('height', [
        'label'          => __('Height', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'vh', 'custom'],
        'range'          => [
            'px' => ['min' => 200, 'max' => 900, 'step' => 10],
            'vh' => ['min' => 20, 'max' => 100, 'step' => 1],
        ],
        'default'        => ['unit' => 'px', 'size' => 500],
        'tablet_default' => ['unit' => 'px', 'size' => 420],
        'description'    => __('Panel height on desktop and tablet. Ignored on mobile — layout height is automatic.', 'testimonials-carousel-elementor'),
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-height: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_responsive_control('widget_align', [
        'label'                => __('Alignment', 'testimonials-carousel-elementor'),
        'type'                 => Controls_Manager::CHOOSE,
        'options'              => [
            'left'   => [
                'title' => __('Left', 'testimonials-carousel-elementor'),
                'icon'  => 'eicon-text-align-left',
            ],
            'center' => [
                'title' => __('Center', 'testimonials-carousel-elementor'),
                'icon'  => 'eicon-text-align-center',
            ],
            'right'  => [
                'title' => __('Right', 'testimonials-carousel-elementor'),
                'icon'  => 'eicon-text-align-right',
            ],
        ],
        'default'              => 'center',
        'tablet_default'       => 'center',
        'mobile_default'       => 'center',
        'description'          => __('Horizontally aligns the widget block within its Elementor column. The effect is visible when Max Width is below 100%. Does not change text alignment inside panels.', 'testimonials-carousel-elementor'),
        'frontend_available'   => true,
        'selectors'            => [
            '{{WRAPPER}} .expandable-testimonials' => 'margin-inline: {{VALUE}};',
        ],
        'selectors_dictionary' => [
            'left'   => '0 auto',
            'center' => 'auto',
            'right'  => 'auto 0',
        ],
    ]);

    $this->add_group_control(Group_Control_Background::get_type(), [
        'name'     => 'section_background',
        'types'    => ['classic', 'gradient'],
        'selector' => '{{WRAPPER}} .expandable-testimonials',
    ]);
    
    $this->end_controls_tab();

    // Items tab
    $this->start_controls_tab('layout_items_tab', [
        'label' => __('Items', 'testimonials-carousel-elementor'),
    ]);

    $this->add_responsive_control('items_gap', [
        'label'          => __('Gap Between Items', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'rem', 'custom'],
        'range'          => [
            'px'  => ['min' => 0, 'max' => 60, 'step' => 1],
            'rem' => ['min' => 0, 'max' => 4, 'step' => 0.1],
        ],
        'default'        => ['unit' => 'px', 'size' => 0],
        'tablet_default' => ['unit' => 'px', 'size' => 0],
        'mobile_default' => ['unit' => 'px', 'size' => 0],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-item-gap: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_responsive_control('collapsed_min_width', [
        'label'              => __('Collapsed Min Width', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SLIDER,
        'size_units'         => ['px', 'rem'],
        'range'              => [
            'px'  => ['min' => 30, 'max' => 200, 'step' => 1],
            'rem' => ['min' => 1, 'max' => 12, 'step' => 0.1],
        ],
        'default'            => ['unit' => 'px', 'size' => 75],
        'tablet_default'     => ['unit' => 'px', 'size' => 56],
        'devices'            => ['desktop', 'tablet'],
        'description'        => __('Width of each collapsed panel strip on desktop and tablet. The active panel fills the remaining space. Ignored on mobile.', 'testimonials-carousel-elementor'),
        'frontend_available' => true,
        'selectors'          => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-min-width: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_group_control(Group_Control_Border::get_type(), [
        'name'     => 'item_border',
        'selector' => '{{WRAPPER}} .expandable-testimonials__item',
    ]);

    $this->add_responsive_control('item_border_radius', [
        'label'      => __('Border Radius', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
            '{{WRAPPER}} .expandable-testimonials__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
        ],
    ]);

    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
        'name'     => 'item_box_shadow',
        'selector' => '{{WRAPPER}} .expandable-testimonials__item',
    ]);

    $this->end_controls_tab();

    // Content tab
    $this->start_controls_tab('layout_content_tab', [
        'label' => __('Content', 'testimonials-carousel-elementor'),
    ]);

    $layout_options = [
        'side-right'   => __('Side — Content Left', 'testimonials-carousel-elementor'),
        'side-left'    => __('Side — Image Left', 'testimonials-carousel-elementor'),
        'stack-top'    => __('Stack — Content Top', 'testimonials-carousel-elementor'),
        'stack-bottom' => __('Stack — Image Top', 'testimonials-carousel-elementor'),
        'overlay'      => __('Overlay — Content on Image', 'testimonials-carousel-elementor'),
    ];

    $this->add_control('active_layout_help', [
        'type'            => Controls_Manager::RAW_HTML,
        'raw'             => __('Choose how text and photo are arranged inside the expanded (active) panel. Collapsed strips keep the vertical photo strip on desktop/tablet.', 'testimonials-carousel-elementor'),
        'content_classes' => 'elementor-descriptor',
    ]);

    $this->add_responsive_control('active_layout', [
        'label'              => __('Active Panel Layout', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SELECT,
        'options'            => $layout_options,
        'default'            => 'side-right',
        'tablet_default'     => 'side-right',
        'mobile_default'     => 'stack-top',
        'frontend_available' => true,
    ]);

    $this->add_control('stack_layout_heading', [
        'label'      => __('Stacked Options', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::HEADING,
        'separator'  => 'before',
        'conditions' => $this->get_layout_conditions_stack(),
    ]);

    $this->add_responsive_control('active_stack_gap', [
        'label'          => __('Stack Gap', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'rem', 'custom'],
        'range'          => [
            'px'  => ['min' => 0, 'max' => 80, 'step' => 1],
            'rem' => ['min' => 0, 'max' => 5, 'step' => 0.1],
        ],
        'default'        => ['unit' => 'px', 'size' => 0],
        'tablet_default' => ['unit' => 'px', 'size' => 0],
        'mobile_default' => ['unit' => 'px', 'size' => 16],
        'description'    => __('Space between content and image in stacked layouts.', 'testimonials-carousel-elementor'),
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-stack-gap: {{SIZE}}{{UNIT}};',
        ],
        'conditions'     => $this->get_layout_conditions_stack(),
    ]);

    $this->add_responsive_control('stacked_photo_area', [
        'label'                => __('Stacked Image Min Height', 'testimonials-carousel-elementor'),
        'type'                 => Controls_Manager::SLIDER,
        'size_units'           => ['px'],
        'range'                => ['px' => ['min' => 100, 'max' => 500, 'step' => 10]],
        'default'              => ['unit' => 'px', 'size' => 220],
        'tablet_default'       => ['unit' => 'px', 'size' => 200],
        'mobile_default'       => ['unit' => 'px', 'size' => 180],
        'description'          => __('Fixed height of the photo area in stacked layouts. Independent of Section Height — changing section height does not resize the image. Image spans the full panel width.', 'testimonials-carousel-elementor'),
        'frontend_available'   => true,
        'selectors'            => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-stack-image-min: {{SIZE}}{{UNIT}};',
        ],
        'conditions'           => $this->get_layout_conditions_stack(),
    ]);

    $this->add_control('overlay_layout_heading', [
        'label'      => __('Overlay Options', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::HEADING,
        'separator'  => 'before',
        'conditions' => $this->get_layout_conditions_overlay(),
    ]);

    $this->add_responsive_control('overlay_min_height', [
        'label'          => __('Overlay Min Height', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'vh', 'custom'],
        'range'          => [
            'px' => ['min' => 200, 'max' => 800, 'step' => 10],
            'vh' => ['min' => 20, 'max' => 100, 'step' => 1],
        ],
        'default'        => ['unit' => 'px', 'size' => 420],
        'tablet_default' => ['unit' => 'px', 'size' => 360],
        'mobile_default' => ['unit' => 'px', 'size' => 320],
        'description'    => __('Minimum height of the active panel in overlay layout.', 'testimonials-carousel-elementor'),
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-overlay-min-height: {{SIZE}}{{UNIT}};',
        ],
        'conditions'     => $this->get_layout_conditions_overlay(),
    ]);

    $this->add_responsive_control('overlay_content_position', [
        'label'          => __('Content Position', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::CHOOSE,
        'options'        => [
            'flex-start' => [
                'title' => __('Top', 'testimonials-carousel-elementor'),
                'icon'  => 'eicon-v-align-top',
            ],
            'center'     => [
                'title' => __('Middle', 'testimonials-carousel-elementor'),
                'icon'  => 'eicon-v-align-middle',
            ],
            'flex-end'   => [
                'title' => __('Bottom', 'testimonials-carousel-elementor'),
                'icon'  => 'eicon-v-align-bottom',
            ],
        ],
        'default'        => 'center',
        'tablet_default' => 'center',
        'mobile_default' => 'flex-end',
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-overlay-content-align: {{VALUE}};',
        ],
        'conditions'     => $this->get_layout_conditions_overlay(),
    ]);

    $this->add_control('overlay_tint_color', [
        'label'      => __('Overlay Tint', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::COLOR,
        'default'    => '#000000',
        'selectors'  => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-overlay-tint-color: {{VALUE}};',
        ],
        'conditions' => $this->get_layout_conditions_overlay(),
    ]);

    $this->add_control('overlay_tint_opacity', [
        'label'      => __('Overlay Tint Opacity', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'range'      => [
            'px' => ['min' => 0, 'max' => 1, 'step' => 0.05],
        ],
        'default'    => ['size' => 0.35],
        'selectors'  => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-overlay-tint-opacity: {{SIZE}};',
        ],
        'conditions' => $this->get_layout_conditions_overlay(),
    ]);

    $this->add_control('side_layout_heading', [
        'label'      => __('Side-by-Side Options', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::HEADING,
        'separator'  => 'before',
        'conditions' => $this->get_layout_conditions_side(),
    ]);

    $this->add_responsive_control('content_width', [
        'label'          => __('Content Width', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['%', 'px', 'custom'],
        'range'          => [
            '%'  => ['min' => 30, 'max' => 80, 'step' => 1],
            'px' => ['min' => 200, 'max' => 800, 'step' => 10],
        ],
        'default'        => ['unit' => '%', 'size' => 55],
        'tablet_default' => ['unit' => '%', 'size' => 55],
        'mobile_default' => ['unit' => '%', 'size' => 60],
        'description'    => __('Text column width in side-by-side layouts (Side — Content/Image Left).', 'testimonials-carousel-elementor'),
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-content-width: {{SIZE}}{{UNIT}};',
        ],
        'conditions'     => $this->get_layout_conditions_side(),
    ]);

    // Control key must NOT contain "min_height" — Elementor treats that as a CSS
    // property and live preview fails with "Failed to apply changes".
    $this->add_responsive_control('side_photo_area', [
        'label'                => __('Side Layout Image Min Height', 'testimonials-carousel-elementor'),
        'type'                 => Controls_Manager::SLIDER,
        'size_units'           => ['px'],
        'range'                => ['px' => ['min' => 100, 'max' => 400, 'step' => 10]],
        'default'              => ['unit' => 'px', 'size' => 180],
        'tablet_default'       => ['unit' => 'px', 'size' => 180],
        'mobile_default'       => ['unit' => 'px', 'size' => 180],
        'description'          => __('Minimum photo height in side-by-side layouts. Applies only when the current breakpoint uses a Side layout. If Image States → Active → Height is larger, lowering this value will not shrink the image.', 'testimonials-carousel-elementor'),
        'frontend_available'   => true,
        'selectors'            => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-mobile-image-min: {{SIZE}}{{UNIT}};',
        ],
        'conditions'           => $this->get_layout_conditions_side(),
    ]);

    $this->add_control('content_common_heading', [
        'label'     => __('Content Spacing', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
    ]);

    $this->add_responsive_control('padding', [
        'label'          => __('Content Padding', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::DIMENSIONS,
        'size_units'     => ['px', '%', 'em', 'rem', 'custom'],
        'default'        => [
            'top'      => '3',
            'right'    => '3',
            'bottom'   => '3',
            'left'     => '3',
            'unit'     => 'rem',
            'isLinked' => true,
        ],
        'tablet_default' => [
            'top'      => '2',
            'right'    => '2',
            'bottom'   => '2',
            'left'     => '2',
            'unit'     => 'rem',
            'isLinked' => true,
        ],
        'mobile_default' => [
            'top'      => '1.25',
            'right'    => '1.25',
            'bottom'   => '1.25',
            'left'     => '1.25',
            'unit'     => 'rem',
            'isLinked' => true,
        ],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]);

    $this->add_responsive_control('content_vertical_align', [
        'label'                => __('Vertical Alignment', 'testimonials-carousel-elementor'),
        'type'                 => Controls_Manager::CHOOSE,
        'options'              => [
            'flex-start' => [
                'title' => __('Top', 'testimonials-carousel-elementor'),
                'icon'  => 'eicon-v-align-top',
            ],
            'center'     => [
                'title' => __('Middle', 'testimonials-carousel-elementor'),
                'icon'  => 'eicon-v-align-middle',
            ],
            'flex-end'   => [
                'title' => __('Bottom', 'testimonials-carousel-elementor'),
                'icon'  => 'eicon-v-align-bottom',
            ],
        ],
        'default'              => 'center',
        'tablet_default'       => 'center',
        'mobile_default'       => 'flex-start',
        'description'          => __('Vertical alignment inside the available text area. Not used in overlay layout — use Content Position there.', 'testimonials-carousel-elementor'),
        'frontend_available'   => true,
        'selectors'            => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-content-vertical-align: {{VALUE}};',
        ],
        'conditions'           => $this->get_layout_conditions_not_overlay(),
    ]);

    $this->end_controls_tab();
    $this->end_controls_tabs();
    $this->end_controls_section();

    $this->register_panel_style_controls();
    $this->register_content_style_controls();
    $this->register_image_style_controls();
    $this->register_navigation_style_controls();
  }

  /**
   * Panel backgrounds and states (collapsed, active, hover).
   */
  protected function register_panel_style_controls()
  {
    $this->start_controls_section('section_style_panels', [
        'label' => __('Panels', 'testimonials-carousel-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]);

    $this->start_controls_tabs('panel_style_tabs');

    $this->start_controls_tab('panel_collapsed_tab', [
        'label' => __('Collapsed', 'testimonials-carousel-elementor'),
    ]);

    $this->add_group_control(Group_Control_Background::get_type(), [
        'name'     => 'panel_collapsed_background',
        'types'    => ['classic', 'gradient'],
        'selector' => '{{WRAPPER}} .expandable-testimonials__item:not(.expandable-testimonials__item--active)',
    ]);

    $this->add_control('bg_item', [
        'label'     => __('Fallback Background', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#a0acb0',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-bg-item: {{VALUE}};',
        ],
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('panel_active_tab', [
        'label' => __('Active', 'testimonials-carousel-elementor'),
    ]);

    $this->add_group_control(Group_Control_Background::get_type(), [
        'name'     => 'panel_active_background',
        'types'    => ['classic', 'gradient'],
        'selector' => '{{WRAPPER}} .expandable-testimonials__item--active',
    ]);

    $this->add_control('bg_active', [
        'label'     => __('Fallback Background', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#dce5f0',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-bg-active: {{VALUE}};',
        ],
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('panel_hover_tab', [
        'label' => __('Hover', 'testimonials-carousel-elementor'),
    ]);

    $this->add_control('bg_item_hover', [
        'label'     => __('Collapsed Hover Background', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials'                                                         => '--et-bg-item-hover: {{VALUE}};',
            '{{WRAPPER}} .expandable-testimonials__item:not(.expandable-testimonials__item--active):hover' => 'background-color: {{VALUE}}; background-blend-mode: multiply;',
        ],
    ]);

    $this->end_controls_tab();
    $this->end_controls_tabs();
    $this->end_controls_section();
  }

  /**
   * Quote, body text, name and company typography.
   */
  protected function register_content_style_controls()
  {
    $this->start_controls_section('section_style_content', [
        'label' => __('Content', 'testimonials-carousel-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]);

    $this->start_controls_tabs('content_style_tabs');

    $this->start_controls_tab('content_quote_tab', [
        'label' => __('Quote', 'testimonials-carousel-elementor'),
    ]);

    $this->add_responsive_control('quote_align', [
        'label'          => __('Alignment', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::CHOOSE,
        'options'        => [
            'left'   => ['title' => __('Left', 'testimonials-carousel-elementor'), 'icon' => 'eicon-text-align-left'],
            'center' => ['title' => __('Center', 'testimonials-carousel-elementor'), 'icon' => 'eicon-text-align-center'],
            'right'  => ['title' => __('Right', 'testimonials-carousel-elementor'), 'icon' => 'eicon-text-align-right'],
        ],
        'default'        => 'left',
        'tablet_default' => 'left',
        'mobile_default' => 'left',
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__quote' => 'text-align: {{VALUE}};',
        ],
    ]);

    $this->add_control('quote_color', [
        'label'     => __('Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials__quote' => 'color: {{VALUE}};',
        ],
    ]);

    $this->add_responsive_control('quote_spacing', [
        'label'          => __('Bottom Spacing', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'em', 'rem'],
        'range'          => ['px' => ['min' => 0, 'max' => 60]],
        'default'        => ['unit' => 'rem', 'size' => 0.75],
        'tablet_default' => ['unit' => 'rem', 'size' => 0.75],
        'mobile_default' => ['unit' => 'rem', 'size' => 0.5],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__quote' => 'margin-bottom: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
        'name'           => 'quote_typography',
        'selector'       => '{{WRAPPER}} .expandable-testimonials__quote',
        'fields_options' => [
            'typography'  => ['default' => 'custom'],
            'font_size'   => [
                'default'        => ['unit' => 'rem', 'size' => 2],
                'tablet_default' => ['unit' => 'rem', 'size' => 1.75],
                'mobile_default' => ['unit' => 'rem', 'size' => 1.35],
            ],
            'line_height' => [
                'default' => ['unit' => 'em', 'size' => 1.3],
            ],
        ],
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('content_body_tab', [
        'label' => __('Text', 'testimonials-carousel-elementor'),
    ]);

    $this->add_responsive_control('text_align', [
        'label'          => __('Alignment', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::CHOOSE,
        'options'        => [
            'left'   => ['title' => __('Left', 'testimonials-carousel-elementor'), 'icon' => 'eicon-text-align-left'],
            'center' => ['title' => __('Center', 'testimonials-carousel-elementor'), 'icon' => 'eicon-text-align-center'],
            'right'  => ['title' => __('Right', 'testimonials-carousel-elementor'), 'icon' => 'eicon-text-align-right'],
        ],
        'default'        => 'left',
        'tablet_default' => 'left',
        'mobile_default' => 'left',
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__text' => 'text-align: {{VALUE}};',
        ],
    ]);

    $this->add_control('text_color', [
        'label'     => __('Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials__text' => 'color: {{VALUE}};',
        ],
    ]);

    $this->add_responsive_control('text_spacing', [
        'label'          => __('Bottom Spacing', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'em', 'rem'],
        'range'          => ['px' => ['min' => 0, 'max' => 60]],
        'default'        => ['unit' => 'rem', 'size' => 0.75],
        'tablet_default' => ['unit' => 'rem', 'size' => 0.75],
        'mobile_default' => ['unit' => 'rem', 'size' => 0.5],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
        'name'           => 'text_typography',
        'selector'       => '{{WRAPPER}} .expandable-testimonials__text',
        'fields_options' => [
            'typography'  => ['default' => 'custom'],
            'font_style'  => ['default' => 'italic'],
            'font_size'   => [
                'default'        => ['unit' => 'rem', 'size' => 1],
                'tablet_default' => ['unit' => 'rem', 'size' => 0.95],
                'mobile_default' => ['unit' => 'rem', 'size' => 0.9],
            ],
            'line_height' => [
                'default' => ['unit' => 'em', 'size' => 1.6],
            ],
        ],
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('content_rating_tab', [
        'label' => __('Rating', 'testimonials-carousel-elementor'),
    ]);

    $this->add_control('rating_help', [
        'type'            => Controls_Manager::RAW_HTML,
        'raw'             => __('Stars appear in the expanded panel only. Enable rating per testimonial in the Content tab.', 'testimonials-carousel-elementor'),
        'content_classes' => 'elementor-descriptor',
    ]);

    $this->add_responsive_control('rating_align', [
        'label'                => __('Alignment', 'testimonials-carousel-elementor'),
        'type'                 => Controls_Manager::CHOOSE,
        'options'              => [
            'left'   => ['title' => __('Left', 'testimonials-carousel-elementor'), 'icon' => 'eicon-text-align-left'],
            'center' => ['title' => __('Center', 'testimonials-carousel-elementor'), 'icon' => 'eicon-text-align-center'],
            'right'  => ['title' => __('Right', 'testimonials-carousel-elementor'), 'icon' => 'eicon-text-align-right'],
        ],
        'default'              => 'left',
        'tablet_default'       => 'left',
        'mobile_default'       => 'left',
        'selectors'            => [
            '{{WRAPPER}} .expandable-testimonials__rating' => 'justify-content: {{VALUE}};',
        ],
        'selectors_dictionary' => [
            'left'   => 'flex-start',
            'center' => 'center',
            'right'  => 'flex-end',
        ],
    ]);

    $this->add_responsive_control('rating_icon_size', [
        'label'          => __('Icon Size', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'em', 'rem'],
        'range'          => ['px' => ['min' => 8, 'max' => 48]],
        'default'        => ['unit' => 'px', 'size' => 16],
        'tablet_default' => ['unit' => 'px', 'size' => 16],
        'mobile_default' => ['unit' => 'px', 'size' => 14],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__rating i' => 'font-size: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_responsive_control('rating_icon_spacing', [
        'label'          => __('Icon Spacing', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'em'],
        'range'          => ['px' => ['min' => 0, 'max' => 20]],
        'default'        => ['unit' => 'px', 'size' => 4],
        'tablet_default' => ['unit' => 'px', 'size' => 4],
        'mobile_default' => ['unit' => 'px', 'size' => 3],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__rating i:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_control('rating_stars_color', [
        'label'     => __('Marked Star Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#f5a623',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials__rating .icon-star-full' => 'color: {{VALUE}};',
        ],
        'separator' => 'before',
    ]);

    $this->add_control('rating_stars_unmarked_color', [
        'label'     => __('Unmarked Star Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#c5cdd3',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials__rating .icon-star-empty' => 'color: {{VALUE}};',
        ],
    ]);

    $this->add_responsive_control('rating_spacing', [
        'label'          => __('Bottom Spacing', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'em', 'rem'],
        'range'          => ['px' => ['min' => 0, 'max' => 60]],
        'default'        => ['unit' => 'rem', 'size' => 0.75],
        'tablet_default' => ['unit' => 'rem', 'size' => 0.75],
        'mobile_default' => ['unit' => 'rem', 'size' => 0.5],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('content_name_tab', [
        'label' => __('Name', 'testimonials-carousel-elementor'),
    ]);

    $this->add_control('name_color', [
        'label'     => __('Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials__name' => 'color: {{VALUE}};',
        ],
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
        'name'           => 'name_typography',
        'selector'       => '{{WRAPPER}} .expandable-testimonials__name',
        'fields_options' => [
            'typography'  => ['default' => 'custom'],
            'font_size'   => [
                'default'        => ['unit' => 'rem', 'size' => 2],
                'tablet_default' => ['unit' => 'rem', 'size' => 1.75],
                'mobile_default' => ['unit' => 'rem', 'size' => 1.35],
            ],
            'line_height' => [
                'default' => ['unit' => 'em', 'size' => 1.2],
            ],
        ],
    ]);

    $this->add_control('accent_bar_heading', [
        'label'     => __('Accent Bar', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
        'condition' => ['show_name_accent' => 'yes'],
    ]);

    $this->add_control('accent_bar_color', [
        'label'     => __('Bar Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#1d9fd9',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials__name::before' => 'background-color: {{VALUE}};',
        ],
        'condition' => ['show_name_accent' => 'yes'],
    ]);

    $this->add_responsive_control('accent_bar_width', [
        'label'          => __('Bar Width', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px'],
        'range'          => ['px' => ['min' => 2, 'max' => 20]],
        'default'        => ['unit' => 'px', 'size' => 5],
        'tablet_default' => ['unit' => 'px', 'size' => 5],
        'mobile_default' => ['unit' => 'px', 'size' => 4],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__name::before' => 'width: {{SIZE}}{{UNIT}};',
        ],
        'condition'      => ['show_name_accent' => 'yes'],
    ]);

    $this->add_responsive_control('accent_bar_skew', [
        'label'          => __('Bar Skew', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['deg'],
        'range'          => ['deg' => ['min' => -30, 'max' => 30]],
        'default'        => ['unit' => 'deg', 'size' => -15],
        'tablet_default' => ['unit' => 'deg', 'size' => -15],
        'mobile_default' => ['unit' => 'deg', 'size' => -15],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__name::before' => 'transform: skew({{SIZE}}deg);',
        ],
        'condition'      => ['show_name_accent' => 'yes'],
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('content_company_tab', [
        'label' => __('Company', 'testimonials-carousel-elementor'),
    ]);

    $this->add_control('company_color', [
        'label'     => __('Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials__company' => 'color: {{VALUE}};',
        ],
    ]);

    $this->add_responsive_control('company_opacity', [
        'label'          => __('Opacity', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['custom'],
        'range'          => ['custom' => ['min' => 0, 'max' => 1, 'step' => 0.05]],
        'default'        => ['unit' => 'custom', 'size' => 0.85],
        'tablet_default' => ['unit' => 'custom', 'size' => 0.85],
        'mobile_default' => ['unit' => 'custom', 'size' => 0.85],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__company' => 'opacity: {{SIZE}};',
        ],
    ]);

    $this->add_responsive_control('company_spacing', [
        'label'          => __('Top Spacing', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'em', 'rem'],
        'range'          => ['px' => ['min' => 0, 'max' => 40]],
        'default'        => ['unit' => 'rem', 'size' => 0.25],
        'tablet_default' => ['unit' => 'rem', 'size' => 0.25],
        'mobile_default' => ['unit' => 'rem', 'size' => 0.2],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__company' => 'margin-top: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
        'name'           => 'company_typography',
        'selector'       => '{{WRAPPER}} .expandable-testimonials__company',
        'fields_options' => [
            'typography' => ['default' => 'custom'],
            'font_size'  => [
                'default'        => ['unit' => 'rem', 'size' => 1],
                'tablet_default' => ['unit' => 'rem', 'size' => 0.95],
                'mobile_default' => ['unit' => 'rem', 'size' => 0.9],
            ],
        ],
    ]);

    $this->end_controls_tab();
    $this->end_controls_tabs();
    $this->end_controls_section();
  }

  /**
   * Image appearance for collapsed and active states.
   */
  protected function register_image_style_controls()
  {
    $this->start_controls_section('section_style_image', [
        'label' => __('Image States', 'testimonials-carousel-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]);

    $this->add_control('image_states_help', [
        'type'            => Controls_Manager::RAW_HTML,
        'raw'             => __('<strong>Collapsed</strong> — photo strip on desktop/tablet only (not visible on mobile).<br><strong>Active</strong> — expanded panel photo. Height and offset apply to side-by-side layouts; stack/overlay sizing is under Layout → Content.', 'testimonials-carousel-elementor'),
        'content_classes' => 'elementor-descriptor',
    ]);

    $this->start_controls_tabs('image_style_tabs');

    $collapsed_wrap     = '{{WRAPPER}} .expandable-testimonials__item:not(.expandable-testimonials__item--active) .expandable-testimonials__image-wrap';
    $active_wrap        = '{{WRAPPER}} .expandable-testimonials__item--active .expandable-testimonials__image-wrap';
    $active_selector    = '{{WRAPPER}} .expandable-testimonials__item--active .expandable-testimonials__image';
    $object_positions   = [
        'top left'      => __('Top Left', 'testimonials-carousel-elementor'),
        'top center'    => __('Top Center', 'testimonials-carousel-elementor'),
        'top right'     => __('Top Right', 'testimonials-carousel-elementor'),
        'center left'   => __('Center Left', 'testimonials-carousel-elementor'),
        'center center' => __('Center Center', 'testimonials-carousel-elementor'),
        'center right'  => __('Center Right', 'testimonials-carousel-elementor'),
        'bottom left'   => __('Bottom Left', 'testimonials-carousel-elementor'),
        'bottom center' => __('Bottom Center', 'testimonials-carousel-elementor'),
        'bottom right'  => __('Bottom Right', 'testimonials-carousel-elementor'),
    ];
    $object_fit_options = [
        'contain' => __('Contain', 'testimonials-carousel-elementor'),
        'cover'   => __('Cover', 'testimonials-carousel-elementor'),
        'fill'    => __('Fill', 'testimonials-carousel-elementor'),
        'none'    => __('None', 'testimonials-carousel-elementor'),
    ];

    $this->start_controls_tab('image_collapsed_tab', [
        'label' => __('Collapsed', 'testimonials-carousel-elementor'),
    ]);

    $this->add_group_control(Group_Control_Border::get_type(), [
        'name'     => 'image_collapsed_border',
        'selector' => $collapsed_wrap,
    ]);

    $this->add_responsive_control('image_collapsed_border_radius', [
        'label'      => __('Border Radius', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
            $collapsed_wrap => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
        ],
    ]);

    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
        'name'     => 'image_collapsed_box_shadow',
        'selector' => $collapsed_wrap,
    ]);

    // Control key must NOT end with "_height" / contain "min_height" — Elementor
    // treats that as a CSS property and live preview fails with
    // "Failed to apply changes".
    $this->add_responsive_control('collapsed_photo_area', [
        'label'              => __('Max Height', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SLIDER,
        'size_units'         => ['%', 'px'],
        'range'              => [
            '%'  => ['min' => 40, 'max' => 100, 'step' => 1],
            'px' => ['min' => 80, 'max' => 600, 'step' => 10],
        ],
        'default'            => ['unit' => '%', 'size' => 80],
        'tablet_default'     => ['unit' => '%', 'size' => 80],
        'devices'            => ['desktop', 'tablet'],
        'description'        => __('Height of the collapsed thumbnail area on desktop and tablet. Supports % and px. Use together with Max Width. Ignored on mobile.', 'testimonials-carousel-elementor'),
        'frontend_available' => true,
        'selectors'          => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-collapsed-photo-area: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_responsive_control('collapsed_photo_width', [
        'label'              => __('Max Width', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SLIDER,
        'size_units'         => ['%', 'px'],
        'range'              => [
            '%'  => ['min' => 20, 'max' => 200, 'step' => 1],
            'px' => ['min' => 30, 'max' => 600, 'step' => 10],
        ],
        'default'            => ['unit' => '%', 'size' => 100],
        'tablet_default'     => ['unit' => '%', 'size' => 100],
        'devices'            => ['desktop', 'tablet'],
        'description'        => __('Width of the collapsed thumbnail area on desktop and tablet. Supports % and px. % is relative to the collapsed panel width. Ignored on mobile.', 'testimonials-carousel-elementor'),
        'frontend_available' => true,
        'selectors'          => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-collapsed-photo-width: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_responsive_control('image_collapsed_offset_x', [
        'label'          => __('Horizontal Offset', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'custom'],
        'range'          => ['px' => ['min' => -80, 'max' => 80, 'step' => 1]],
        'default'        => ['unit' => 'px', 'size' => -20],
        'tablet_default' => ['unit' => 'px', 'size' => -20],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-collapsed-img-offset-x: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_control('image_collapsed_grayscale', [
        'label'                => __('Grayscale', 'testimonials-carousel-elementor'),
        'type'                 => Controls_Manager::SWITCHER,
        'label_on'             => __('Yes', 'testimonials-carousel-elementor'),
        'label_off'            => __('No', 'testimonials-carousel-elementor'),
        'return_value'         => 'yes',
        'default'              => 'yes',
        'description'          => __('Converts the author photo to black and white. Helps visually de-emphasize collapsed panels.', 'testimonials-carousel-elementor'),
        'selectors'            => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-collapsed-img-filter: {{VALUE}};',
        ],
        'selectors_dictionary' => [
            'yes' => 'grayscale(1)',
            ''    => 'none',
        ],
    ]);

    $this->add_control('image_collapsed_scale', [
        'label'      => __('Scale', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['%'],
        'range'      => [
            '%' => ['min' => 50, 'max' => 150, 'step' => 1],
        ],
        'default'    => ['unit' => '%', 'size' => 90],
        'selectors'  => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-collapsed-img-scale: calc({{SIZE}} / 100);',
        ],
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('image_active_tab', [
        'label' => __('Active', 'testimonials-carousel-elementor'),
    ]);

    $this->add_group_control(Group_Control_Border::get_type(), [
        'name'     => 'image_active_border',
        'selector' => $active_wrap,
    ]);

    $this->add_responsive_control('image_active_border_radius', [
        'label'      => __('Border Radius', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
            $active_wrap => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
        ],
    ]);

    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
        'name'     => 'image_active_box_shadow',
        'selector' => $active_wrap,
    ]);

    $this->add_responsive_control('image_active_object_fit', [
        'label'          => __('Object Fit', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SELECT,
        'default'        => 'contain',
        'tablet_default' => 'contain',
        'mobile_default' => 'cover',
        'options'        => $object_fit_options,
        'selectors'      => [
            $active_selector => 'object-fit: {{VALUE}};',
        ],
    ]);

    $this->add_responsive_control('image_active_object_position', [
        'label'          => __('Object Position', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SELECT,
        'default'        => 'bottom right',
        'tablet_default' => 'bottom right',
        'mobile_default' => 'top center',
        'options'        => $object_positions,
        'selectors'      => [
            $active_selector => 'object-position: {{VALUE}};',
        ],
    ]);

    $this->add_control('image_active_side_heading', [
        'label'      => __('Side-by-Side', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::HEADING,
        'separator'  => 'before',
        'conditions' => $this->get_layout_conditions_side(),
    ]);

    $this->add_responsive_control('image_active_height', [
        'label'          => __('Height', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['%', 'px', 'custom'],
        'range'          => [
            '%'  => ['min' => 40, 'max' => 100, 'step' => 1],
            'px' => ['min' => 80, 'max' => 600, 'step' => 10],
        ],
        'default'        => ['unit' => '%', 'size' => 80],
        'tablet_default' => ['unit' => '%', 'size' => 80],
        'mobile_default' => ['unit' => '%', 'size' => 100],
        'description'    => __('Active photo height in side-by-side layouts. Stacked and overlay modes use full-width image sizing.', 'testimonials-carousel-elementor'),
        'selectors'      => [
            $active_wrap     => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
            $active_selector => 'height: 100%; max-height: 100%;',
        ],
        'conditions'     => $this->get_layout_conditions_side(),
    ]);

    $this->add_responsive_control('image_active_offset_x', [
        'label'          => __('Horizontal Offset', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'custom'],
        'range'          => ['px' => ['min' => -80, 'max' => 80, 'step' => 1]],
        'default'        => ['unit' => 'px', 'size' => -1],
        'tablet_default' => ['unit' => 'px', 'size' => -1],
        'mobile_default' => ['unit' => 'px', 'size' => 0],
        'description'    => __('Horizontal offset in side-by-side layouts. Positive values move the image inward.', 'testimonials-carousel-elementor'),
        'selectors'      => [
            $active_wrap => '--et-active-img-offset: {{SIZE}}{{UNIT}};',
        ],
        'conditions'     => $this->get_layout_conditions_side(),
    ]);

    $this->end_controls_tab();
    $this->end_controls_tabs();
    $this->end_controls_section();
  }

  /**
   * Navigation arrows, counter and play/pause button styles.
   */
  protected function register_navigation_style_controls()
  {
    $this->start_controls_section('section_style_navigation', [
        'label'     => __('Navigation', 'testimonials-carousel-elementor'),
        'tab'       => Controls_Manager::TAB_STYLE,
        'condition' => ['show_navigation' => 'yes'],
    ]);

    $this->add_responsive_control('nav_gap', [
        'label'          => __('Elements Gap', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'rem'],
        'range'          => ['px' => ['min' => 0, 'max' => 60]],
        'default'        => ['unit' => 'px', 'size' => 16],
        'tablet_default' => ['unit' => 'px', 'size' => 16],
        'mobile_default' => ['unit' => 'px', 'size' => 12],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__nav' => 'gap: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->add_responsive_control('nav_margin_top', [
        'label'          => __('Top Spacing', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'rem'],
        'range'          => ['px' => ['min' => 0, 'max' => 80]],
        'default'        => ['unit' => 'px', 'size' => 16],
        'tablet_default' => ['unit' => 'px', 'size' => 16],
        'mobile_default' => ['unit' => 'px', 'size' => 12],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__nav' => 'margin-top: {{SIZE}}{{UNIT}};',
        ],
    ]);

    $this->start_controls_tabs('navigation_style_tabs');

    // Arrows
    $this->start_controls_tab('nav_arrows_tab', [
        'label'     => __('Arrows', 'testimonials-carousel-elementor'),
        'condition' => ['show_buttons' => 'yes'],
    ]);

    $this->add_control('arrows_normal_heading', [
        'label' => __('Normal', 'testimonials-carousel-elementor'),
        'type'  => Controls_Manager::HEADING,
    ]);

    $this->add_responsive_control('arrows_size', [
        'label'          => __('Size', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'rem'],
        'range'          => ['px' => ['min' => 24, 'max' => 80]],
        'default'        => ['unit' => 'px', 'size' => 40],
        'tablet_default' => ['unit' => 'px', 'size' => 40],
        'mobile_default' => ['unit' => 'px', 'size' => 36],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__arrow'     => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
            '{{WRAPPER}} .expandable-testimonials__arrow svg' => 'width: calc({{SIZE}}{{UNIT}} * 0.44); height: calc({{SIZE}}{{UNIT}} * 0.44);',
        ],
    ]);

    $this->add_control('arrows_color', [
        'label'     => __('Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#1d9fd9',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-arrow-color: {{VALUE}};',
            '{{WRAPPER}} .expandable-testimonials__arrow' => 'color: {{VALUE}}; border-color: {{VALUE}};',
        ],
    ]);

    $this->add_control('arrows_background', [
        'label'     => __('Background', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => 'transparent',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-arrow-bg: {{VALUE}};',
            '{{WRAPPER}} .expandable-testimonials__arrow' => 'background-color: {{VALUE}};',
        ],
    ]);

    $this->add_group_control(Group_Control_Border::get_type(), [
        'name'           => 'arrows_border',
        'selector'       => '{{WRAPPER}} .expandable-testimonials__arrow',
        'fields_options' => [
            'border' => ['default' => 'solid'],
            'width'  => [
                'default' => [
                    'top'      => '2',
                    'right'    => '2',
                    'bottom'   => '2',
                    'left'     => '2',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
            ],
            'color'  => ['default' => '#1d9fd9'],
        ],
    ]);

    $this->add_responsive_control('arrows_border_radius', [
        'label'          => __('Border Radius', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::DIMENSIONS,
        'size_units'     => ['px', '%', 'em', 'rem'],
        'default'        => [
            'top'      => '50',
            'right'    => '50',
            'bottom'   => '50',
            'left'     => '50',
            'unit'     => '%',
            'isLinked' => true,
        ],
        'tablet_default' => [
            'top'      => '50',
            'right'    => '50',
            'bottom'   => '50',
            'left'     => '50',
            'unit'     => '%',
            'isLinked' => true,
        ],
        'mobile_default' => [
            'top'      => '50',
            'right'    => '50',
            'bottom'   => '50',
            'left'     => '50',
            'unit'     => '%',
            'isLinked' => true,
        ],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]);

    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
        'name'     => 'arrows_box_shadow',
        'selector' => '{{WRAPPER}} .expandable-testimonials__arrow',
    ]);

    $this->add_control('arrows_hover_heading', [
        'label'     => __('Hover', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
    ]);

    $this->add_control('arrows_hover_color', [
        'label'     => __('Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#ffffff',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials.expandable-testimonials--hover-capable .expandable-testimonials__arrow:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
        ],
    ]);

    $this->add_control('arrows_hover_background', [
        'label'     => __('Background', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#1d9fd9',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials.expandable-testimonials--hover-capable .expandable-testimonials__arrow:hover' => 'background-color: {{VALUE}};',
        ],
    ]);

    $this->add_control('arrows_disabled_heading', [
        'label'     => __('Disabled', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
    ]);

    $this->add_control('arrows_disabled_opacity', [
        'label'      => __('Opacity', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['custom'],
        'range'      => [
            'custom' => [
                'min'  => 0,
                'max'  => 1,
                'step' => 0.01,
            ],
        ],
        'default'    => ['unit' => 'custom', 'size' => 0.3],
        'selectors'  => [
            '{{WRAPPER}} .expandable-testimonials__arrow--disabled' => 'opacity: {{SIZE}};',
            '{{WRAPPER}} .expandable-testimonials__arrow:disabled'  => 'opacity: {{SIZE}};',
        ],
    ]);

    $this->end_controls_tab();

    // Counter
    $this->start_controls_tab('nav_counter_tab', [
        'label'     => __('Counter', 'testimonials-carousel-elementor'),
        'condition' => ['show_counter' => 'yes'],
    ]);

    $this->add_control('counter_color', [
        'label'     => __('Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials__counter' => 'color: {{VALUE}};',
        ],
    ]);

    $this->add_responsive_control('counter_opacity', [
        'label'          => __('Opacity', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['custom'],
        'range'          => [
            'custom' => [
                'min'  => 0,
                'max'  => 1,
                'step' => 0.01,
            ],
        ],
        'default'        => ['unit' => 'custom', 'size' => 0.75],
        'tablet_default' => ['unit' => 'custom', 'size' => 0.75],
        'mobile_default' => ['unit' => 'custom', 'size' => 0.75],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__counter' => 'opacity: {{SIZE}};',
        ],
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
        'name'           => 'counter_typography',
        'selector'       => '{{WRAPPER}} .expandable-testimonials__counter',
        'fields_options' => [
            'typography' => ['default' => 'custom'],
            'font_size'  => [
                'default'        => ['unit' => 'rem', 'size' => 0.9],
                'tablet_default' => ['unit' => 'rem', 'size' => 0.9],
                'mobile_default' => ['unit' => 'rem', 'size' => 0.85],
            ],
        ],
    ]);

    $this->end_controls_tab();

    // Play / Pause
    $this->start_controls_tab('nav_play_tab', [
        'label'     => __('Play / Pause', 'testimonials-carousel-elementor'),
        'condition' => ['show_navigation' => 'yes'],
    ]);

    $this->add_control('play_normal_heading', [
        'label' => __('Playing', 'testimonials-carousel-elementor'),
        'type'  => Controls_Manager::HEADING,
    ]);

    $this->add_responsive_control('play_size', [
        'label'          => __('Size', 'testimonials-carousel-elementor'),
        'type'           => Controls_Manager::SLIDER,
        'size_units'     => ['px', 'rem'],
        'range'          => ['px' => ['min' => 24, 'max' => 80]],
        'default'        => ['unit' => 'px', 'size' => 40],
        'tablet_default' => ['unit' => 'px', 'size' => 40],
        'mobile_default' => ['unit' => 'px', 'size' => 36],
        'selectors'      => [
            '{{WRAPPER}} .expandable-testimonials__play'     => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            '{{WRAPPER}} .expandable-testimonials__play svg' => 'width: calc({{SIZE}}{{UNIT}} * 0.4); height: calc({{SIZE}}{{UNIT}} * 0.4);',
        ],
    ]);

    $this->add_control('play_color', [
        'label'     => __('Icon Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#ffffff',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-play-color: {{VALUE}};',
            '{{WRAPPER}} .expandable-testimonials__play:not(.expandable-testimonials__play--paused)' => 'color: {{VALUE}};',
        ],
    ]);

    $this->add_control('play_background', [
        'label'     => __('Background', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#1d9fd9',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-play-bg: {{VALUE}};',
            '{{WRAPPER}} .expandable-testimonials__play:not(.expandable-testimonials__play--paused)' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
        ],
    ]);

    $this->add_control('play_paused_heading', [
        'label'     => __('Paused', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
    ]);

    $this->add_control('play_paused_color', [
        'label'     => __('Icon Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => '#1d9fd9',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-play-paused-color: {{VALUE}};',
            '{{WRAPPER}} .expandable-testimonials__play--paused' => 'color: {{VALUE}}; border-color: {{VALUE}};',
        ],
    ]);

    $this->add_control('play_paused_background', [
        'label'     => __('Background', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'default'   => 'transparent',
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials' => '--et-play-paused-bg: {{VALUE}};',
            '{{WRAPPER}} .expandable-testimonials__play--paused' => 'background-color: {{VALUE}};',
        ],
    ]);

    $this->add_control('play_hover_heading', [
        'label'     => __('Hover', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
    ]);

    $this->add_control('play_hover_color', [
        'label'     => __('Icon Color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials.expandable-testimonials--hover-capable .expandable-testimonials__play:hover' => 'color: {{VALUE}};',
        ],
    ]);

    $this->add_control('play_hover_background', [
        'label'     => __('Background', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .expandable-testimonials.expandable-testimonials--hover-capable .expandable-testimonials__play:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
        ],
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();
    $this->end_controls_section();
  }

  /**
   * Build Elementor condition terms for responsive active_layout values.
   *
   * @param string[] $layouts Layout slugs.
   * @return array<int, array<string, mixed>>
   */
  protected function get_layout_condition_terms(array $layouts)
  {
    $terms = [];

    foreach (['active_layout', 'active_layout_tablet', 'active_layout_mobile'] as $setting_key) {
      $terms[] = [
          'name'     => $setting_key,
          'operator' => 'in',
          'value'    => $layouts,
      ];
    }

    return $terms;
  }

  /**
   * Show control when any breakpoint uses side-by-side layout.
   *
   * @return array<string, mixed>
   */
  protected function get_layout_conditions_side()
  {
    return [
        'relation' => 'or',
        'terms'    => $this->get_layout_condition_terms(['side-right', 'side-left']),
    ];
  }

  /**
   * Show control when any breakpoint uses stacked layout.
   *
   * @return array<string, mixed>
   */
  protected function get_layout_conditions_stack()
  {
    return [
        'relation' => 'or',
        'terms'    => $this->get_layout_condition_terms(['stack-top', 'stack-bottom']),
    ];
  }

  /**
   * Show control when any breakpoint uses overlay layout.
   *
   * @return array<string, mixed>
   */
  protected function get_layout_conditions_overlay()
  {
    return [
        'relation' => 'or',
        'terms'    => $this->get_layout_condition_terms(['overlay']),
    ];
  }

  /**
   * Show control when any breakpoint uses a non-overlay layout.
   *
   * @return array<string, mixed>
   */
  protected function get_layout_conditions_not_overlay()
  {
    return [
        'relation' => 'or',
        'terms'    => $this->get_layout_condition_terms(['side-right', 'side-left', 'stack-top', 'stack-bottom']),
    ];
  }

  /**
   * Allowed active-panel layout slugs.
   *
   * @return string[]
   */
  protected function get_allowed_layouts()
  {
    return ['side-right', 'side-left', 'stack-top', 'stack-bottom', 'overlay'];
  }

  /**
   * Sanitize layout value from settings.
   *
   * @param mixed  $value   Raw setting value.
   * @param string $default Fallback layout slug.
   * @return string
   */
  protected function sanitize_layout($value, $default = 'side-right')
  {
    $allowed = $this->get_allowed_layouts();
    if (!in_array($value, $allowed, true)) {
      return in_array($default, $allowed, true) ? $default : 'side-right';
    }
    return $value;
  }

  /**
   * Map legacy control keys to their replacements when reading saved settings.
   *
   * @param array $settings Widget settings.
   * @return array
   */
  protected function migrate_legacy_settings(array $settings)
  {
    $migrations = [
      ['collapsed_photo_area', 'image_collapsed_photo_max'],
      ['collapsed_photo_area', 'image_collapsed_height'],
      ['collapsed_photo_area_tablet', 'image_collapsed_photo_max_tablet'],
      ['collapsed_photo_area_tablet', 'image_collapsed_height_tablet'],
      ['collapsed_photo_area_mobile', 'image_collapsed_photo_max_mobile'],
      ['collapsed_photo_area_mobile', 'image_collapsed_height_mobile'],
    ];

    foreach ($migrations as [$new_key, $old_key]) {
      if (!array_key_exists($new_key, $settings) && isset($settings[$old_key])) {
        $settings[$new_key] = $settings[$old_key];
      }
    }

    return $settings;
  }

  /**
   * Read a slider control value as a CSS size string (any unit).
   *
   * @param array       $settings Widget settings.
   * @param string[]    $keys     Setting keys in priority order.
   * @param string|null $fallback Optional fallback value.
   * @return string|null
   */
  protected function get_slider_size(array $settings, array $keys, $fallback = null)
  {
    foreach ($keys as $key) {
      if (
        isset($settings[$key])
        && is_array($settings[$key])
        && isset($settings[$key]['size'])
        && $settings[$key]['size'] !== ''
        && is_numeric($settings[$key]['size'])
      ) {
        $unit = !empty($settings[$key]['unit']) ? $settings[$key]['unit'] : 'px';

        return $settings[$key]['size'] . $unit;
      }
    }

    return $fallback;
  }

  /**
   * Read a px slider value from the first available setting key.
   *
   * @param array    $settings Widget settings.
   * @param string[] $keys     Responsive setting keys in priority order.
   * @param string   $fallback Fallback CSS value.
   * @return string
   */
  protected function get_px_slider_value(array $settings, array $keys, $fallback = '180px')
  {
    foreach ($keys as $key) {
      if (
        isset($settings[$key])
        && is_array($settings[$key])
        && isset($settings[$key]['size'])
        && $settings[$key]['size'] !== ''
        && is_numeric($settings[$key]['size'])
      ) {
        return (float)$settings[$key]['size'] . 'px';
      }
    }

    return $fallback;
  }

  /**
   * Read a vertical flex alignment from the first available setting key.
   *
   * @param array    $settings Widget settings.
   * @param string[] $keys     Responsive setting keys in priority order.
   * @param string   $fallback Fallback alignment.
   * @return string
   */
  protected function get_vertical_position(array $settings, array $keys, $fallback = 'center')
  {
    $allowed = ['flex-start', 'center', 'flex-end'];

    foreach ($keys as $key) {
      if (isset($settings[$key]) && in_array($settings[$key], $allowed, true)) {
        return $settings[$key];
      }
    }

    return $fallback;
  }

  /**
   * Render star rating markup for a testimonial item.
   *
   * @param array $item Repeater item settings.
   */
  protected function render_rating(array $item): void
  {
    if (empty($item['et_rating_enable']) || $item['et_rating_enable'] !== 'yes') {
      return;
    }

    $rating = isset($item['et_rating']) && $item['et_rating'] !== '' ? (int)$item['et_rating'] : 0;
    $rating = max(0, min(5, $rating));
    ?>
    <div
        class="expandable-testimonials__rating"
        role="img"
        aria-label="<?php echo esc_attr(sprintf(
            __('%d out of 5 stars', 'testimonials-carousel-elementor'),
            $rating
        )); ?>"
    >
      <?php for ($i = 0; $i < $rating; $i++) : ?>
        <i class="icon-star-full" aria-hidden="true"></i>
      <?php endfor;

      for ($i = 0; $i < (5 - $rating); $i++) : ?>
        <i class="icon-star-empty" aria-hidden="true"></i>
      <?php endfor; ?>
    </div>
    <?php
  }

  protected function render()
  {
    $settings     = $this->migrate_legacy_settings($this->get_settings_for_display());
    $testimonials = !empty($settings['testimonials']) ? $settings['testimonials'] : [];

    if (empty($testimonials)) {
      return;
    }

    $max_items        = isset($settings['max_items']) && $settings['max_items'] !== '' ? min(4, max(1, (int)$settings['max_items'])) : 4;
    $max_items_tablet = isset($settings['max_items_tablet']) && $settings['max_items_tablet'] !== '' ? min(4, max(1, (int)$settings['max_items_tablet'])) : 2;
    $max_items_mobile = isset($settings['max_items_mobile']) && $settings['max_items_mobile'] !== '' ? min(4, max(1, (int)$settings['max_items_mobile'])) : 1;
    $default_active   = isset($settings['default_active']) && $settings['default_active'] !== '' ? (int)$settings['default_active'] : 0;
    $autoplay_enabled = !empty($settings['autoplay_enabled']) && $settings['autoplay_enabled'] === 'yes';
    $autoplay_ms      = $autoplay_enabled && !empty($settings['autoplay_interval'])
        ? max(1000, (int)$settings['autoplay_interval'])
        : 0;
    $loop             = (!empty($settings['loop']) && $settings['loop'] === 'true') ? 'true' : 'false';
    $show_navigation  = !isset($settings['show_navigation']) || $settings['show_navigation'] === 'yes';
    $show_buttons     = $show_navigation && (!isset($settings['show_buttons']) || $settings['show_buttons'] === 'yes');
    $show_counter     = $show_navigation && (!isset($settings['show_counter']) || $settings['show_counter'] === 'yes');

    $total = count($testimonials);
    if ($default_active < 0 || $default_active >= $total) {
      $default_active = 0;
    }

    $autoplay_attr     = $autoplay_ms >= 1000 ? ' data-autoplay="' . esc_attr($autoplay_ms) . '"' : '';
    $hide_nav_attr     = !$show_navigation ? ' data-hide-nav="true"' : '';
    $hide_buttons_attr = !$show_buttons ? ' data-hide-buttons="true"' : '';
    $hide_play_attr    = !$autoplay_enabled ? ' data-hide-play="true"' : '';
    $hide_counter_attr = !$show_counter ? ' data-hide-counter="true"' : '';
    $nav_class         = !$show_navigation ? ' expandable-testimonials--no-nav' : '';

    $layout        = $this->sanitize_layout($settings['active_layout'] ?? 'side-right', 'side-right');
    $layout_tablet = $this->sanitize_layout($settings['active_layout_tablet'] ?? $layout, $layout);
    $layout_mobile = $this->sanitize_layout($settings['active_layout_mobile'] ?? 'stack-top', 'stack-top');
    $layout_class  = ' expandable-testimonials--layout-' . esc_attr($layout);
    $side_photo_area = $this->get_px_slider_value(
      $settings,
      ['side_photo_area', 'side_image_min_height']
    );
    $side_photo_area_tablet = $this->get_px_slider_value(
      $settings,
      ['side_photo_area_tablet', 'side_image_min_height_tablet'],
      $side_photo_area
    );
    $side_photo_area_mobile = $this->get_px_slider_value(
      $settings,
      ['side_photo_area_mobile', 'side_image_min_height_mobile'],
      $side_photo_area_tablet
    );
    $stacked_photo_area = $this->get_px_slider_value(
      $settings,
      ['stacked_photo_area', 'stacked_image_area_height', 'stacked_image_min_height', 'stack_image_min_height'],
      '220px'
    );
    $stacked_photo_area_tablet = $this->get_px_slider_value(
      $settings,
      ['stacked_photo_area_tablet', 'stacked_image_area_height_tablet', 'stacked_image_min_height_tablet', 'stack_image_min_height_tablet'],
      $stacked_photo_area
    );
    $stacked_photo_area_mobile = $this->get_px_slider_value(
      $settings,
      ['stacked_photo_area_mobile', 'stacked_image_area_height_mobile', 'stacked_image_min_height_mobile', 'stack_image_min_height_mobile'],
      $stacked_photo_area_tablet
    );
    $stack_gap = $this->get_slider_size($settings, ['active_stack_gap'], '0px');
    $stack_gap_tablet = $this->get_slider_size($settings, ['active_stack_gap_tablet'], $stack_gap);
    $stack_gap_mobile = $this->get_slider_size($settings, ['active_stack_gap_mobile'], '16px');
    $content_position_y = $this->get_vertical_position(
      $settings,
      ['content_vertical_align', 'content_position_y']
    );
    $content_position_y_tablet = $this->get_vertical_position(
      $settings,
      ['content_vertical_align_tablet', 'content_position_y_tablet'],
      $content_position_y
    );
    $content_position_y_mobile = $this->get_vertical_position(
      $settings,
      ['content_vertical_align_mobile', 'content_position_y_mobile'],
      $content_position_y_tablet
    );
    $collapsed_photo_area = $this->get_slider_size(
      $settings,
      ['collapsed_photo_area', 'image_collapsed_photo_max', 'image_collapsed_height'],
      '80%'
    );
    $collapsed_photo_area_tablet = $this->get_slider_size(
      $settings,
      ['collapsed_photo_area_tablet', 'image_collapsed_photo_max_tablet', 'image_collapsed_height_tablet'],
      $collapsed_photo_area
    );
    $collapsed_photo_width = $this->get_slider_size(
      $settings,
      ['collapsed_photo_width'],
      '100%'
    );
    $collapsed_photo_width_tablet = $this->get_slider_size(
      $settings,
      ['collapsed_photo_width_tablet'],
      $collapsed_photo_width
    );
    $collapsed_img_offset = $this->get_slider_size(
      $settings,
      ['image_collapsed_offset_x'],
      '-20px'
    );
    $collapsed_img_scale = '0.9';
    if (
      isset($settings['image_collapsed_scale'])
      && is_array($settings['image_collapsed_scale'])
      && isset($settings['image_collapsed_scale']['size'])
      && $settings['image_collapsed_scale']['size'] !== ''
      && is_numeric($settings['image_collapsed_scale']['size'])
    ) {
      $collapsed_img_scale = 'calc(' . $settings['image_collapsed_scale']['size'] . ' / 100)';
    }
    $collapsed_img_filter = 'grayscale(1)';
    if (isset($settings['image_collapsed_grayscale']) && $settings['image_collapsed_grayscale'] !== 'yes') {
      $collapsed_img_filter = 'none';
    }
    $arrow_color = !empty($settings['arrows_color']) ? $settings['arrows_color'] : '#1d9fd9';
    $arrow_bg = isset($settings['arrows_background']) && $settings['arrows_background'] !== '' ? $settings['arrows_background'] : 'transparent';
    $play_color = !empty($settings['play_color']) ? $settings['play_color'] : '#ffffff';
    $play_bg = !empty($settings['play_background']) ? $settings['play_background'] : '#1d9fd9';
    $play_paused_color = !empty($settings['play_paused_color']) ? $settings['play_paused_color'] : '#1d9fd9';
    $play_paused_bg = isset($settings['play_paused_background']) && $settings['play_paused_background'] !== '' ? $settings['play_paused_background'] : 'transparent';
    $inline_vars = '--et-collapsed-photo-area:' . esc_attr($collapsed_photo_area)
      . ';--et-collapsed-photo-width:' . esc_attr($collapsed_photo_width)
      . ';--et-collapsed-img-offset-x:' . esc_attr($collapsed_img_offset)
      . ';--et-collapsed-img-scale:' . esc_attr($collapsed_img_scale)
      . ';--et-collapsed-img-filter:' . esc_attr($collapsed_img_filter)
      . ';--et-stack-image-min:' . esc_attr($stacked_photo_area)
      . ';--et-stack-gap:' . esc_attr($stack_gap)
      . ';--et-arrow-color:' . esc_attr($arrow_color)
      . ';--et-arrow-bg:' . esc_attr($arrow_bg)
      . ';--et-play-color:' . esc_attr($play_color)
      . ';--et-play-bg:' . esc_attr($play_bg)
      . ';--et-play-paused-color:' . esc_attr($play_paused_color)
      . ';--et-play-paused-bg:' . esc_attr($play_paused_bg) . ';';
    $widget_dom_id = 'expandable-testimonials-' . $this->get_id();

    wp_add_inline_script(
      'expandable-testimonials-handler',
      '(function(){var root=document.getElementById(' . wp_json_encode($widget_dom_id) . ');if(!root)return;var layouts=["side-right","side-left","stack-top","stack-bottom","overlay"];var pickLayout=function(){if(window.matchMedia("(max-width: 767px)").matches){return root.getAttribute("data-layout-mobile")||root.getAttribute("data-layout")||"stack-top";}if(window.matchMedia("(max-width: 1024px)").matches){return root.getAttribute("data-layout-tablet")||root.getAttribute("data-layout")||"side-right";}return root.getAttribute("data-layout")||"side-right";};var applyLayout=function(){var layout=pickLayout();if(layouts.indexOf(layout)===-1)layout="side-right";layouts.forEach(function(name){root.classList.toggle("expandable-testimonials--layout-"+name,name===layout);});};applyLayout();window.addEventListener("resize",applyLayout,{passive:true});})();',
      'before'
    );
    ?>
    <section
        id="<?php echo esc_attr($widget_dom_id); ?>"
        class="expandable-testimonials<?php echo esc_attr($nav_class . $layout_class); ?>"
        style="<?php echo esc_attr($inline_vars); ?>"
        data-default-active="<?php echo esc_attr($default_active); ?>"
        data-max-items="<?php echo esc_attr($max_items); ?>"
        data-max-items-tablet="<?php echo esc_attr($max_items_tablet); ?>"
        data-max-items-mobile="<?php echo esc_attr($max_items_mobile); ?>"
        data-layout="<?php echo esc_attr($layout); ?>"
        data-layout-tablet="<?php echo esc_attr($layout_tablet); ?>"
        data-layout-mobile="<?php echo esc_attr($layout_mobile); ?>"
        data-collapsed-photo-area="<?php echo esc_attr($collapsed_photo_area); ?>"
        data-collapsed-photo-area-tablet="<?php echo esc_attr($collapsed_photo_area_tablet); ?>"
        data-collapsed-photo-width="<?php echo esc_attr($collapsed_photo_width); ?>"
        data-collapsed-photo-width-tablet="<?php echo esc_attr($collapsed_photo_width_tablet); ?>"
        data-collapsed-img-offset-x="<?php echo esc_attr($collapsed_img_offset); ?>"
        data-collapsed-img-scale="<?php echo esc_attr($collapsed_img_scale); ?>"
        data-collapsed-img-filter="<?php echo esc_attr($collapsed_img_filter); ?>"
        data-side-photo-area="<?php echo esc_attr($side_photo_area); ?>"
        data-side-photo-area-tablet="<?php echo esc_attr($side_photo_area_tablet); ?>"
        data-side-photo-area-mobile="<?php echo esc_attr($side_photo_area_mobile); ?>"
        data-stacked-photo-area="<?php echo esc_attr($stacked_photo_area); ?>"
        data-stacked-photo-area-tablet="<?php echo esc_attr($stacked_photo_area_tablet); ?>"
        data-stacked-photo-area-mobile="<?php echo esc_attr($stacked_photo_area_mobile); ?>"
        data-stack-gap="<?php echo esc_attr($stack_gap); ?>"
        data-stack-gap-tablet="<?php echo esc_attr($stack_gap_tablet); ?>"
        data-stack-gap-mobile="<?php echo esc_attr($stack_gap_mobile); ?>"
        data-content-position-y="<?php echo esc_attr($content_position_y); ?>"
        data-content-position-y-tablet="<?php echo esc_attr($content_position_y_tablet); ?>"
        data-content-position-y-mobile="<?php echo esc_attr($content_position_y_mobile); ?>"<?php echo $autoplay_attr; ?>
        data-loop="<?php echo esc_attr($loop); ?>"<?php echo $hide_nav_attr . $hide_buttons_attr . $hide_play_attr . $hide_counter_attr; ?>
        aria-label="<?php esc_attr_e('Customer testimonials', 'testimonials-carousel-elementor'); ?>"
    >
      <div class="expandable-testimonials__list">
        <?php foreach ($testimonials as $index => $item) :
          $image_url = !empty($item['et_image']['url']) ? $item['et_image']['url'] : '';
          $image_id = !empty($item['et_image']['id']) ? (int)$item['et_image']['id'] : 0;
          $image_alt = $image_id ? (string)get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
          $is_active = ($index === $default_active);
          $item_class = 'expandable-testimonials__item' . ($is_active ? ' expandable-testimonials__item--active' : '');
          ?>
          <div
              class="<?php echo esc_attr($item_class); ?> elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>"<?php echo $is_active ? ' aria-expanded="true"' : ' aria-expanded="false"'; ?>>
            <div class="expandable-testimonials__content">
              <?php $this->render_rating($item);

              if (!empty($item['et_quote'])) : ?>
                <p class="expandable-testimonials__quote">
                  <i>&ldquo;<?php echo wp_kses_post($item['et_quote']); ?>&rdquo;</i>
                </p>
              <?php endif;

              if (!empty($item['et_text'])) : ?>
                <p class="expandable-testimonials__text">
                  <?php echo wp_kses_post($item['et_text']); ?>
                </p>
              <?php endif; ?>

              <div class="expandable-testimonials__author">
                <?php if (!empty($item['et_name'])) : ?>
                  <p class="expandable-testimonials__name">
                    <?php echo esc_html($item['et_name']); ?>
                  </p>
                <?php endif;

                if (!empty($item['et_company'])) : ?>
                  <p class="expandable-testimonials__company">
                    <?php echo esc_html($item['et_company']); ?>
                  </p>
                <?php endif; ?>
              </div>
            </div>

            <?php if (!empty($image_url)) : ?>
              <span class="expandable-testimonials__image-wrap">
                <img
                    class="expandable-testimonials__image"
                    src="<?php echo esc_url($image_url); ?>"
                    alt="<?php echo esc_attr($image_alt); ?>"
                    loading="eager"
                    decoding="async"
                />
              </span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php
  }
}
