<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Core_Values extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-core-values.css');
        wp_register_style('widget-core-values', get_template_directory_uri() . '/css/widget-core-values.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-core-values'];
    }

    public function get_name()
    {
        return 'core-values';
    }

    public function get_title()
    {
        return __('Core Values', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-banner';
    }

    public function get_categories()
    {
        return ['Motz'];
    }

    public function get_keywords()
    {
        return ['custom'];
    }

    protected function register_controls()
    {

        $this->start_controls_section('section_intro', [
            'label' => __('Core Values', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'number',
            [
                'label' => esc_html__('Choose Number', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h2.core-values__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} h2.core-values__title',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'heading_color',
            [
                'label' => __( 'Heading Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .core-values__heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Heading Typography', 'elementor-custom-widgets' ),
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .core-values__heading',
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

        $repeater->add_control('text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $repeater->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .core-values__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .core-values__text',
            ]
        );


        $this->add_control(
            'values',
            [
                'label' => esc_html__('Values', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Title #1', 'elementor-custom-widgets'),
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

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section core-values">
        <div class="grid-cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-primary num-title wow fadeInUp core-values__title">

                  <?php if (isset($settings['number']) && !empty($settings['number']['id'])) : ?>
                    <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['number']['id'])); ?></div>
                  <?php endif; ?>
                <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
              </h2>
            <?php endif; ?>

          <div class="mt-200 grid-row grid-row--jcc">
              <?php foreach ($settings['values'] as $item) : ?>
              <?php $current_item_class = 'elementor-repeater-item-' . $item['_id']; ?>

                <div class="mt-200 grid-col grid-col--md-4 grid-col--sm-6 grid-col--xs-12 wow fadeInUp <?= esc_attr($current_item_class) ?>">

                  <div class="core-values__icon">
                      <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                          <?= wp_get_attachment_image($item['image']['id'], 'full') ?>
                      <?php endif; ?>
                  </div>

                    <?php if (isset($item['title'])) : ?>
                      <h5 class="mt-150 tc-primary core-values__heading"><?= wp_kses_post($item['title']) ?></h5>
                    <?php endif; ?>

                    <?php if (isset($item['text'])) : ?>
                      <div class="mt-050 core-values__text"><?= wp_kses_post($item['text']) ?></div>
                    <?php endif; ?>

                </div>
              <?php endforeach; ?>

          </div>
        </div>
      </div>

        <?php
    }
}


