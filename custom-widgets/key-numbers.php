<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Key_Numbers extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-key-numbers.css');
        wp_register_style('widget-key-numbers', get_template_directory_uri() . '/css/widget-key-numbers.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-key-numbers'];
    }

    public function get_name()
    {
        return 'key-numbers';
    }

    public function get_title()
    {
        return __('Key Numbers', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-plus';
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
            'label' => __('Key Numbers', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new Repeater();

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
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .key-numbers__heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .key-numbers__heading',
            ]
        );

        $this->add_control(
            'options',
            [
                'label' => esc_html__('Options', 'elementor-custom-widgets'),
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

        <div id="<?= esc_attr($section_id) ?>" class="section key-numbers">
            <div class="grid-cont">
                <div class="grid-row grid-row--jca">
                    <?php if ($settings['options']) : ?>
                        <?php $delay = 0; ?>
                        <?php foreach ($settings['options'] as $item) : ?>
                            <?php $current_item_class = 'elementor-repeater-item-' . $item['_id']; ?>

                            <div data-wow-delay="<?= $delay ?>s" class="grid-col grid-col--auto grid-col--sm-12 wow fadeInUp <?= $current_item_class ?>">
                                <div class="key-numbers__item">
                                    <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>

                                        <div class="key-numbers__image">
                                            <?= file_get_contents(wp_get_original_image_path($item['image']['id'])); ?>
                                        </div>

                                    <?php endif; ?>

                                    <?php if (isset($item['title'])) : ?>
                                        <div class="mt-100 h2 key-numbers__heading"><?= wp_kses_post($item['title']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php $delay += 0.25; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
    }
}


