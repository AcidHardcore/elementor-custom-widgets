<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Post_Subscribe extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-post-subscribe.css');
        wp_register_style('widget-post-subscribe', get_template_directory_uri() . '/css/widget-post-subscribe.css', array('main-styles'), $css_version);

        wp_enqueue_script('hubspot-script', 'https://js.hsforms.net/forms/v2.js');

    }

    public function get_style_depends()
    {
        return ['widget-post-subscribe'];
    }

    public function get_name()
    {
        return 'post-subscribe';
    }

    public function get_title()
    {
        return __('Post Subscribe', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-form-horizontal';
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
            'label' => __('Post Subscribe', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'form_heading', [
                'label' => esc_html__('Form Heading', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Form Heading Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-subscribe__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Form Heading Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .post-subscribe__title',
            ]
        );

        $this->add_control(
            'form_copy', [
                'label' => esc_html__('Form Copy', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Form Copy Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-subscribe__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Form Copy Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .post-subscribe__text',
            ]
        );

        $this->add_control(
            'form_hubspot', [
                'label' => esc_html__('Form HubSpot', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
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

      <div id="<?= esc_attr($section_id) ?>" class="grid-cont">
        <div class="grid-row grid-row--nog grid-row--jce">
          <div class="grid-col grid-col--4 grid-col--md-12">

                <div class="post-subscribe wow fadeInUp">
                    <?php if (isset($settings['form_heading'])) : ?>
                      <h2 class="post-subscribe__title"><?= esc_html($settings['form_heading']) ?></h2>
                    <?php endif; ?>

                    <?php if (isset($settings['form_copy'])) : ?>
                      <p class="mt-100 p p--md post-subscribe__text"><?= esc_html($settings['form_copy']) ?></p>
                    <?php endif; ?>

                    <?php if (isset($settings['form_hubspot'])) : ?>
                        <?= $settings['form_hubspot'] ?>
                    <?php endif; ?>

                </div>

          </div>
        </div>
      </div>
        <?php
    }
}


