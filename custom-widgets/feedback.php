<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Feedback extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-feedback.css');
        wp_register_style('widget-feedback', get_template_directory_uri() . '/css/widget-feedback.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-feedback'];
    }

    public function get_name()
    {
        return 'feedback';
    }

    public function get_title()
    {
        return __('Feedback', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-editor-quote';
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
            'label' => __('Feedback', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'important_note',
            [
                'label' => esc_html__('Warning:', 'elementor-custom-widgets'),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<strong>This widget is working only on the Recommender Contact Info template</strong>', 'elementor-custom-widgets'),
                'content_classes' => 'your-class',
            ]
        );

        $this->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => false,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .feedback__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .feedback__title',
            ]
        );

        $this->add_control(
            'desc', [
            'label' => __('Description', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('Description', 'elementor-custom-widgets'),
            'label_block' => false,
        ]);

        $this->add_control(
            'desc_color',
            [
                'label' => __( 'Description Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .feedback__desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Description Typography', 'elementor-custom-widgets' ),
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .feedback__desc',
            ]
        );

        $this->add_control(
            'shortcode', [
            'label' => __('Shortcode ID', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('ID', 'elementor-custom-widgets'),
            'label_block' => false,
        ]);

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
        $result_id = get_query_var( 'result_id' );
        ?>
        <?php if (is_page_template('page-templates/turf-recommender-contact-info.php')) : ?>

        <div id="<?= esc_attr($section_id) ?>" class="section section--nobg feedback">
          <div class="grid-cont grid-cont--xs">

              <?php if (isset($settings['title'])) : ?>
                <div class="h1 tc-primary feedback__title wow fadeInUp"><?= wp_kses_post($settings['title']) ?></div>
              <?php endif; ?>

              <?php if (isset($settings['desc'])) : ?>
                <div class="mt-100 p p--lg wow fadeInUp feedback__desc"><?= wp_kses_post($settings['desc']) ?></div>
              <?php endif; ?>

              <?php if (isset($settings['shortcode'])) : ?>
                <?= do_shortcode('[contact-form-7 id="' . $settings['shortcode'] . '" result="' . $result_id . '"]') ?>
              <?php endif; ?>
          </div>
        </div>

    <?php else : ?>

        <div id="<?= esc_attr($section_id) ?>" class="section feedback">
            <div class="grid-cont grid-cont--xs">
                <p>Pick up Turf Recommender Contact Info template for the page</p>
            </div>
        </div>

    <?php endif; ?>
        <?php
    }
}

?>



