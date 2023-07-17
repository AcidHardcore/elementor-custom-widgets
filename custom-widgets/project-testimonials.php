<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Project_Testimonials extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

    }

    public function get_style_depends()
    {
        return ['slick-styles', 'widget-testimonials'];
    }

    public function get_script_depends()
    {
        return ['slick-scripts', 'widget-testimonials'];
    }

    public function get_name()
    {
        return 'project-testimonials';
    }

    public function get_title()
    {
        return __('Project Testimonials', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-person';
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
            'label' => __('Project Testimonials', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);


        $this->add_control(
            'more_options',
            [
                'label' => esc_html__('All settings are automatic takes from a Project post', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => __( 'Quote Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .testimonials__quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Quote Typography', 'elementor-custom-widgets' ),
                'name' => 'quote_typography',
                'selector' => '{{WRAPPER}} .testimonials__quote > *',
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label' => __( 'Author Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .testimonials__author > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Author Typography', 'elementor-custom-widgets' ),
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .testimonials__author > *',
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
        $project_id = get_the_ID();
        $content = get_field('project_quote', $project_id);
        $name = get_field('project_quote_source_name', $project_id);
        $position = get_field('project_quote_source_position', $project_id);
        $from = get_field('project_quote_source_from', $project_id);
        $image_id = get_field('project_quote_image', $project_id);
        $is_video = get_field('is_quote_video', $project_id);
        $video_id = get_field('project_quote_video', $project_id);
        ?>

        <?php if ($content) : ?>
      <div id="<?= esc_attr($section_id) ?>" class="testimonials">
        <div class="grid-cont">
          <div class="grid-row grid-row--nog">
            <div class="grid-col grid-col--5 grid-col--md-12">
              <div class="testimonials__cont">

                  <?php if ($content) : ?>
                    <div class="testimonials__quote wow fadeInUp"><?= wp_kses_post($content) ?></div>
                  <?php endif; ?>


                <div class="mt-100 testimonials__author wow fadeInUp">

                    <?php if ($name) : ?>
                      <span><?= esc_html($name) ?></span>
                    <?php endif; ?>

                    <?php if ($name && $position) : ?>
                      <span> <?= ' | ' ?></span>
                    <?php endif; ?>

                    <?php if ($position) : ?>
                      <span><?= esc_html($position) ?></span>
                    <?php endif; ?>

                    <?php if ($name || $position) : ?>
                      <br>
                    <?php endif; ?>

                    <?php if ($from) : ?>
                        <?= esc_html($from) ?>
                    <?php endif; ?>

                </div>
              </div>
            </div>
            <div class="grid-col grid-col--1 grid-col--md-12"></div>
            <div class="grid-col grid-col--6 grid-col--md-12 grid-col--md-order-0">
                <?php if ($image_id && !$is_video) : ?>
                  <div class="testimonials__figure">
                      <?= wp_get_attachment_image($image_id, 'full', false, array('class' => 'testimonials__image')) ?>
                  </div>
                <?php endif; ?>

                <?php if ($image_id && $is_video && $video_id) : ?>
                  <div class="testimonials__figure video-testimonials">
                    <a class="video-testimonials__link" href="https://youtu.be/<?= $video_id ?>" data-id="<?= $video_id ?>">
                        <?= wp_get_attachment_image($image_id, 'full', false, array('class' => 'testimonials__image video__media')) ?>
                    </a>
                    <button class="video-testimonials__button" type="button" aria-label="Play video">
                      <svg width="68" height="48" viewBox="0 0 68 48">
                        <path class="video-testimonials__button-shape"
                              d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"></path>
                        <path class="video-testimonials__button-icon" d="M 45,24 27,14 27,34"></path>
                      </svg>
                    </button>
                  </div>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

        <?php
    }
}


