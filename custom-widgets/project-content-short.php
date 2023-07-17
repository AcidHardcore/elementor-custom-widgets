<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Project_Content_Short extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');
    }

    public function get_style_depends()
    {
        return ['widget-post-details', 'widget-project-result'];
    }

    public function get_script_depends()
    {
        return ['widget-project-content'];
    }

    public function get_name()
    {
        return 'project-content-short';
    }

    public function get_title()
    {
        return __('Project Content Short', 'elementor-custom-widgets');
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
            'label' => __('Project Content Short', 'elementor-custom-widgets'),
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

          </div>
        </div>
      </div>

        <?php
    }
}


