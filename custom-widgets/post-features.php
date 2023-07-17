<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Post_Features extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-post-features.css');
        wp_register_style('widget-post-features', get_template_directory_uri() . '/css/widget-post-features.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-post-features.js');
        wp_register_script('widget-post-features', get_template_directory_uri() . '/js/widget-post-features.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-post-features'];
    }

    public function get_script_depends()
    {
        return ['jquery-ui', 'widget-post-features'];
    }

    public function get_name()
    {
        return 'post-features';
    }

    public function get_title()
    {
        return __('Post Features', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-featured-image';
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
            'label' => __('Post Features', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'col_align',
            [
                'label' => esc_html__('Column Alignment', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => false,
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
                    '{{WRAPPER}} .post-features__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .post-features__title',
            ]
        );

        $this->add_control(
            'is_image',
            [
                'label' => esc_html__('Media Switcher', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Comparison', 'elementor-custom-widgets'),
                'label_off' => esc_html__('Image', 'elementor-custom-widgets'),
                'return_value' => 'comparison',
                'default' => 'image',
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose After Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'image_before',
            [
                'label' => esc_html__('Choose Before Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'is_image' => 'comparison',
                ],
            ]
        );

        $this->add_control('text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-features__text > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .post-features__text > *',
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

      <div id="<?= esc_attr($section_id) ?>" class="mt-300 grid-row grid-row--aic post-features">

          <div class="grid-row grid-row--aic">

              <?php if ($settings['col_align'] === 'right') : ?>

                <div class="grid-col grid-col--auto grid-col--md-6 grid-col--sm-12">

                    <?php if (isset($settings['image']) && !empty($settings['image']['id']) && $settings['is_image'] !== 'comparison') : ?>
                      <div class="post-details__before-after">
                          <?= wp_get_attachment_image($settings['image']['id'], 'full') ?>
                      </div>
                    <?php endif; ?>

                    <?php if ($settings['is_image'] === 'comparison' && isset($settings['image']) && !empty($settings['image']['id'])) : ?>

                      <div class="before-after post-details__before-after">
                          <?= wp_get_attachment_image($settings['image']['id'], 'full', false, array('class' => 'before-after__image')) ?>

                          <?php if (isset($settings['image_before']) && !empty($settings['image_before']['id'])) : ?>
                            <div style="background-image: url(<?= wp_get_attachment_image_url($settings['image_before']['id'], 'full') ?>)" class="before-after__over"></div>
                          <?php endif; ?>
                      </div>

                    <?php endif; ?>
                </div>

              <?php else : ?>

                <div class="grid-col grid-col--sm-12">

                    <?php if (isset($settings['title'])) : ?>
                      <h4 class="post-features__title"><?= wp_kses_post($settings['title']) ?></h4>
                    <?php endif; ?>

                    <?php if (isset($settings['text'])) : ?>
                      <div class="mt-100 post-features__text"><?= wp_kses_post($settings['text']) ?></div>
                    <?php endif; ?>

                </div>

              <?php endif; ?>

            <div class="mt-150 grid-col grid-col--auto grid-col--sm-12"></div>

              <?php if ($settings['col_align'] === 'right') : ?>

                <div class="grid-col grid-col--sm-12">
                    <?php if (isset($settings['title'])) : ?>
                      <h4 class="post-features__title"><?= wp_kses_post($settings['title']) ?></h4>
                    <?php endif; ?>

                    <?php if (isset($settings['text'])) : ?>
                      <div class="mt-100 post-features__text"><?= wp_kses_post($settings['text']) ?></div>
                    <?php endif; ?>
                </div>

              <?php else : ?>

                <div class="grid-col grid-col--auto grid-col--md-6 grid-col--sm-12">

                    <?php if (isset($settings['image']) && !empty($settings['image']['id']) && $settings['is_image'] !== 'comparison') : ?>
                      <div class="post-details__before-after">
                          <?= wp_get_attachment_image($settings['image']['id'], 'full') ?>
                      </div>
                    <?php endif; ?>

                    <?php if ($settings['is_image'] === 'comparison' && isset($settings['image']) && !empty($settings['image']['id'])) : ?>

                      <div class="before-after post-details__before-after">
                          <?= wp_get_attachment_image($settings['image']['id'], 'full', false, array('class' => 'before-after__image')) ?>

                          <?php if (isset($settings['image_before']) && !empty($settings['image_before']['id'])) : ?>
                            <div style="background-image: url(<?= wp_get_attachment_image_url($settings['image_before']['id'], 'full') ?>)" class="before-after__over"></div>
                          <?php endif; ?>
                      </div>

                    <?php endif; ?>

                </div>

              <?php endif; ?>

          </div>

      </div>

        <?php
    }
}


