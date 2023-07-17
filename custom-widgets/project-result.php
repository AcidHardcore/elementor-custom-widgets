<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Project_Result extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-project-result.css');
        wp_register_style('widget-project-result', get_template_directory_uri() . '/css/widget-project-result.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-project-result'];
    }

    public function get_name()
    {
        return 'project-result';
    }

    public function get_title()
    {
        return __('Project Result', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-columns';
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
            'label' => __('Project Result', 'elementor-custom-widgets'),
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
            'title_color',
            [
                'label' => __( 'Headings Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-details__heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Headings Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .post-details__heading',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Content Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-details__content > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Content Typography', 'elementor-custom-widgets' ),
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .post-details__content > *',
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

        $this->add_control(
            'stacked',
            [
                'label' => esc_html__('Stacked', 'elementor-custom-widgets'),
                'description' => 'Decreasing space to the bottom section with the same option enabled',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('No', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_class = isset($settings['stacked']) && $settings['stacked'] === 'yes' ? 'section--nobg' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        $project_id = get_the_ID();
        $title = get_field('the_win_title', $project_id);
        $content = get_field('the_win_content', $project_id);
        $image = get_field('the_win_image', $project_id);
        $video = get_field('the_win_video', $project_id);
        $win_image_before = get_field('win_image_before', $project_id);
        $win_image_after = get_field('win_image_after', $project_id);
        ?>

        <?php if ($content) : ?>
      <div id="<?= esc_attr($section_id) ?>" class="section post-details post-details--alt <?= esc_attr($section_class) ?>">
        <div class="grid-cont grid-cont--sm">
          <div class="post-details__text wow fadeInUp">
            <div class="grid-row grid-row--aic">
              <div class="grid-col grid-col--sm-12">
                  <?php if ($title) : ?>
                    <h2 class="post-details__heading"><?= esc_html($title) ?></h2>
                  <?php endif; ?>

                  <?php if ($content) : ?>
                    <div class="post-details__content">
                        <?= wp_kses_post($content) ?>
                    </div>
                  <?php endif; ?>

              </div>
              <div class="mt-150 grid-col grid-col--auto grid-col--sm-12"></div>
              <div class="grid-col grid-col--md-6 grid-col--sm-12">

                  <?php if ($image) : ?>
                      <?= wp_get_attachment_image($image, 'full') ?>
                  <?php endif; ?>

                  <?php if ($video) : ?>
                    <div class="mt-100">
                        <?= $video ?>
                    </div>
                  <?php endif; ?>

                  <?php if ($win_image_after) : ?>
                    <div class="before-after post-details__before-after">
                        <?= wp_get_attachment_image($win_image_after, 'full', false, array('class' => 'before-after__image')) ?>

                        <?php if ($win_image_before) : ?>
                          <div style="background-image: url(<?= wp_get_attachment_image_url($win_image_before, 'full') ?>)" class="before-after__over"></div>
                        <?php endif; ?>
                    </div>
                  <?php endif; ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

        <?php
    }
}


