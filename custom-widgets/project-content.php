<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Project_Content extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-project-content.css');
        wp_register_style('widget-project-content', get_template_directory_uri() . '/css/widget-project-content.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-project-content.js');
        wp_register_script('widget-project-content', get_template_directory_uri() . '/js/widget-project-content.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-post-details', 'widget-project-result', 'widget-project-content'];
    }

    public function get_script_depends()
    {
        return ['jquery-ui', 'widget-project-content'];
    }

    public function get_name()
    {
        return 'project-content';
    }

    public function get_title()
    {
        return __('Project Content', 'elementor-custom-widgets');
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
            'label' => __('Project Content', 'elementor-custom-widgets'),
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
        $situation_title = get_field('the_situation_title', $project_id);
        $play_title = get_field('the_play_title', $project_id);
        $situation_content = get_field('the_situation', $project_id);
        $play_content = get_field('the_play_content', $project_id);
        $situation_image_before = get_field('situation_image_before', $project_id);
        $situation_image_after = get_field('situation_image_after', $project_id);
        $situation_video = get_field('the_situation_video', $project_id);
        $play_image_repeater = get_field('play_image_repeater', $project_id);
        $play_video = get_field('the_play_video', $project_id);
        $play_image_before = get_field('play_image_before', $project_id);
        $play_image_after = get_field('play_image_after', $project_id);

        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section post-details post-details--alt <?= esc_attr($section_class) ?>">
        <div class="grid-cont grid-cont--sm">
          <div class="post-details__text wow fadeInUp">


              <?php if (have_rows('project_images')): ?>
                <div class="post-details__gallery mb-300">
                    <?php $count = 0;
                    while (have_rows('project_images')) : the_row();
                        $count++;
                        $image = get_sub_field('project_image');
                        $video = get_sub_field('video');

                        if ($image):

                            if ($count == 1) {
                                echo '<div class="post-details__image post-details__image--main">'; ?>
                                <?= wp_get_attachment_image($image, 'full') ?>
                                <?php
                                echo '</div>';

                            } else {
                                echo '<div class="post-details__image">'; ?>
                                <?= wp_get_attachment_image($image, 'large') ?>
                                <?php
                                echo '</div>';
                            }
                        endif;

                        if ($video):

                            if ($count == 1) {
                                echo '<div class="post-details__image post-details__image--main">';
                                echo $video;
                                echo '</div>';
                            } else {
                                echo '<div class="post-details__image">';
                                echo $video;
                                echo '</div>';
                            }
                        endif;
                    endwhile; ?>
                </div>
              <?php endif; ?>

              <?php if ($situation_content) : ?>
                <div class="grid-row grid-row--aic">
                  <div class="grid-col grid-col--sm-12 grid-col--sm-order-1">
                      <?php if ($situation_title) : ?>
                        <h2 class="post-details__heading"><?= esc_html($situation_title) ?></h2>
                      <?php endif; ?>

                      <?php if ($situation_content) : ?>
                        <div class="mt-100 post-details__content">
                            <?= wp_kses_post($situation_content) ?>
                        </div>
                      <?php endif; ?>

                  </div>

                    <?php if ($situation_image_after) : ?>
                      <div class="mt-150 grid-col grid-col--auto grid-col--sm-12"></div>
                      <div class="grid-col grid-col--md-6 grid-col--sm-12 grid-col--sm-order-0">
                        <div class="before-after post-details__before-after">

                            <?= wp_get_attachment_image($situation_image_after, 'full', false, array('class' => 'before-after__image')) ?>

                            <?php if ($situation_image_before) : ?>
                              <div style="background-image: url(<?= wp_get_attachment_image_url($situation_image_before, 'full') ?>)" class="before-after__over"></div>
                            <?php endif; ?>

                            <?php if ($situation_video) : ?>
                              <div class="mt-100">
                                  <?= $situation_video ?>
                              </div>
                            <?php endif; ?>

                        </div>
                      </div>
                    <?php endif; ?>
                </div>
              <?php endif; ?>

              <?php if ($play_content) : ?>
                <div class="mt-300 grid-row grid-row--aic">
                  <div class="grid-col grid-col--md-6 grid-col--sm-12">

                      <?php if (is_array($play_image_repeater)) : ?>
                          <?php foreach ($play_image_repeater as $image) : ?>
                              <?php if (isset($image['play_image_row']) && !empty($image['play_image_row'])) : ?>
                                  <?= wp_get_attachment_image($image['play_image_row'], 'full', false, ['class' => 'mt-100']) ?>
                              <?php endif; ?>
                          <?php endforeach; ?>
                      <?php endif; ?>

                      <?php if ($play_video) : ?>
                        <div class="mt-100">
                            <?= $play_video ?>
                        </div>
                      <?php endif; ?>

                      <?php if ($play_image_after) : ?>
                        <div class="before-after post-details__before-after">
                            <?= wp_get_attachment_image($play_image_after, 'full', false, array('class' => 'before-after__image')) ?>

                            <?php if ($play_image_before) : ?>
                              <div style="background-image: url(<?= wp_get_attachment_image_url($play_image_before, 'full') ?>)" class="before-after__over"></div>
                            <?php endif; ?>
                        </div>
                      <?php endif; ?>

                  </div>
                  <div class="mt-150 grid-col grid-col--auto grid-col--sm-12"></div>
                  <div class="grid-col grid-col--sm-12">
                      <?php if ($play_title) : ?>
                        <h2 class="post-details__heading"><?= esc_html($play_title) ?></h2>
                      <?php endif; ?>

                      <?php if ($play_content) : ?>
                        <div class="mt-100 post-details__content">
                            <?= wp_kses_post($play_content) ?>
                        </div>
                      <?php endif; ?>

                  </div>
                </div>
              <?php endif; ?>
          </div>
        </div>
      </div>

        <?php
    }
}


