<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

class Our_Services extends Widget_Base
{

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    $the_theme = wp_get_theme();
    $theme_version = $the_theme->get('Version');

    $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-our-services.css');
    wp_register_style('widget-our-services', get_template_directory_uri() . '/css/widget-our-services.css', array('main-styles'), $css_version);

    $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-our-services.js');
    wp_register_script('widget-our-services', get_template_directory_uri() . '/js/widget-our-services.js', array('jquery'), $js_version, true);
  }

  public function get_style_depends() {
    return ['widget-our-services'];
  }

  public function get_script_depends() {
    return ['widget-our-services'];
  }

  public function get_name() {
    return 'our-services';
  }

  public function get_title() {
    return __('Our Services', 'elementor-custom-widgets');
  }

  public function get_icon() {
    return 'eicon-wrench';
  }

  public function get_categories() {
    return ['Motz'];
  }

  public function get_keywords() {
    return ['custom'];
  }

  protected function register_controls() {

    $this->start_controls_section('section_intro', [
      'label' => __('Our Services', 'elementor-custom-widgets'),
    ]);


    $this->add_control(
      'title', [
        'label' => __('Title', 'elementor-custom-widgets'),
        'type' => Controls_Manager::TEXT,
        'default' => __('Title', 'elementor-custom-widgets'),
      ]
    );

      $this->add_control(
          'title_color',
          [
              'label' => __( 'Title Color', 'elementor-custom-widgets' ),
              'type' => Controls_Manager::COLOR,
              'selectors' => [
                  '{{WRAPPER}} .our-services__title' => 'color: {{VALUE}};',
              ],
          ]
      );

      $this->add_group_control(
          Group_Control_Typography::get_type(),
          [
              'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
              'name' => 'title_typography',
              'selector' => '{{WRAPPER}} .our-services__title',
          ]
      );

    $repeater = new Repeater();

    $repeater->add_control(
      'title', [
        'label' => esc_html__( 'Title', 'elementor-custom-widgets' ),
        'type' => Controls_Manager::TEXT,
        'default' => esc_html__( 'Title' , 'elementor-custom-widgets' ),
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'image',
      [
        'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
        'type' => Controls_Manager::MEDIA,
        'default' => [
          'url' => Utils::get_placeholder_image_src(),
        ],
      ]
    );

    $repeater->add_control(
      'image_small',
      [
        'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
        'type' => Controls_Manager::MEDIA,
        'default' => [
          'url' => Utils::get_placeholder_image_src(),
        ],
      ]
    );

    $repeater->add_control(
      'icon',
      [
        'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
        'type' => Controls_Manager::MEDIA,
        'default' => [
          'url' => Utils::get_placeholder_image_src(),
        ],
      ]
    );

    $repeater->add_control('list', [
      'label' => __('List', 'elementor-custom-widgets'),
      'type' => Controls_Manager::WYSIWYG,
      'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
    ]);



    $this->add_control(
      'services',
      [
        'label' => esc_html__( 'Services', 'elementor-custom-widgets' ),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'default' => [
          [
            'title' => esc_html__('List Item #1', 'elementor-custom-widgets'),
            'list' => esc_html__('List Items', 'elementor-custom-widgets'),
          ],
        ],
        'title_field' => '{{{ title }}}',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section('section_settings', [
      'label' => __('Settings', 'elementor-custom-widgets'),
      'tab' => Controls_Manager::TAB_STYLE,
    ]);

    $this->add_control(
      'section_id', [
        'label' => esc_html__('Section ID', 'elementor-custom-widgets'),
        'type' => Controls_Manager::TEXT,
      ]
    );

    $this->end_controls_section();

  }

  protected function render() {
    $settings = $this->get_active_settings();
    $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

    ?>

    <div id="<?= esc_attr($section_id) ?>" class="section our-services">
      <div class="grid-cont">

        <?php if (isset($settings['title'])) : ?>
          <h2 class="tc-primary wow fadeInUp our-services__title"><?= wp_kses_post($settings['title']) ?></h2>
        <?php endif; ?>

        <div class="our-services__row">
          <?php
          $delay = 0.25;
          foreach ($settings['services'] as $service) : ?>

            <div data-wow-delay="<?= $delay ?>s" class="our-services__col wow fadeInUp">
              <?php if (isset($service['image']) && !empty($service['image']['id'])) : ?>
                <?= wp_get_attachment_image($service['image']['id'], 'full', false, array('class' => 'our-services__bg')) ?>
              <?php endif; ?>

              <?php if (isset($service['image_small']) && !empty($service['image_small']['id'])) : ?>
                <?= wp_get_attachment_image($service['image_small']['id'], 'full', false, array('class' => 'our-services__bg our-services__bg--sm')) ?>
              <?php endif; ?>

              <div class="our-services__icon">
                <?php if (isset($service['icon']) && !empty($service['icon']['id'])) : ?>
                  <?= wp_get_attachment_image($service['icon']['id'], 'full') ?>
                <?php endif; ?>
              </div>

              <?php if (isset($service['title'])) : ?>
                <h3 class="our-services__heading"><?= wp_kses_post($service['title']) ?></h3>
              <?php endif; ?>

              <button type="button" class="our-services__toggle"></button>

              <div class="our-services__list">

                <?php if (isset($service['list'])) : ?>
                  <?= wp_kses_post($service['list']) ?>
                <?php endif; ?>

                <button type="button" class="our-services__close"></button>
              </div>


            </div>
            <?php $delay += 0.25; ?>
          <?php endforeach; ?>

        </div>
      </div>
    </div>

    <?php
  }
}


