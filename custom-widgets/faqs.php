<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Faqs extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-faqs.css');
        wp_register_style('widget-faqs', get_template_directory_uri() . '/css/widget-faqs.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-faqs.js');
        wp_register_script('widget-faqs', get_template_directory_uri() . '/js/widget-faqs.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-faqs'];
    }

    public function get_script_depends()
    {
        return ['widget-faqs'];
    }

    public function get_name()
    {
        return 'faqs';
    }

    public function get_title()
    {
        return __('Faqs', 'elementor-custom-widgets');
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
            'label' => __('Faqs', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title and Headings Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faqs__title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .faqs__question' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .faqs__title',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Headings Typography', 'elementor-custom-widgets' ),
                'name' => 'headings_typography',
                'selector' => '{{WRAPPER}} .faqs__question',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faqs__answer' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .faqs__answer',
            ]
        );

        $repeater = new Repeater();


        $repeater->add_control(
            'is_title',
            [
                'label' => esc_html__('Add a title before', 'elementor-custom-widgets'),
                'description' => 'Showing and adding title and ID',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('no', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $repeater->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'is_title' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'title_id', [
                'label' => esc_html__('Title ID', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'is_title' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'question', [
                'label' => esc_html__('Question', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'answer', [
                'label' => esc_html__('Answer', 'elementor-custom-widgets'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'row' => 3
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => esc_html__('Questions', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'question' => esc_html__('Title - Question', 'elementor-custom-widgets'),
                    ],
                ],
                'title_field' => '<b>{{{ title }}}</b> - {{{ question }}}',
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

      <div id="<?= esc_attr($section_id) ?>" class="section faqs">
        <div class="grid-cont grid-cont--sm">

            <?php if (isset($settings['list']) && is_array($settings['list'])) : ?>
                <?php foreach ($settings['list'] as $item) : ?>

                    <?php if (isset($item['is_title']) && $item['is_title'] == 'yes') : ?>

                        <?php if (isset($item['title_id']) && !empty($item['title_id'])) : ?>
                    <div id="<?= esc_attr($item['title_id']) ?>"></div>
                        <?php endif; ?>

                        <?php if (isset($item['title']) && !empty($item['title'])) : ?>
                    <div class="faqs__title"><?= esc_html($item['title']) ?></div>
                        <?php endif; ?>

                    <?php endif; ?>

                <div class="faqs__item">
                    <?php if (isset($item['question']) && !empty($item['question'])) : ?>
                      <div class="h3 faqs__question"><i></i><?= esc_html($item['question']) ?></div>
                    <?php endif; ?>

                    <?php if (isset($item['answer']) && !empty($item['answer'])) : ?>
                      <div class="faqs__answer">
                       <?= wp_kses_post($item['answer']) ?>
                      </div>
                    <?php endif; ?>
                </div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
      </div>

        <?php
    }
}


