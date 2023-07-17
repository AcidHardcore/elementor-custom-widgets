<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Hero_Serve extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

    }

    public function get_style_depends()
    {
        return ['widget-hero'];
    }

    public function get_name()
    {
        return 'hero-serve';
    }

    public function get_title()
    {
        return __('Hero Serve', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-countdown';
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
            'label' => __('Hero Contact', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'size',
            [
                'label' => esc_html__('Switch sizes', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'narrow' => [
                        'title' => esc_html__('Narrow', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'wide' => [
                        'title' => esc_html__('Wide', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'narrow',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'title', [
                'label' => __('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .hero__title',
            ]
        );

        $this->add_control(
            'description', [
                'label' => __('Description', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Description Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Description Typography', 'elementor-custom-widgets' ),
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .hero__subtitle',
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_settings', [
            'label' => __('Settings', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'section_id',
            [
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
        $col_class = 'grid-col';
        $container_class = 'grid-cont hero__cont';
        if (isset($settings['size']) && $settings['size'] === 'narrow') {
            $col_class .= ' grid-col--6 grid-col--md-9 grid-col--sm-12';
        } else {
            $col_class .= ' grid-col--10 grid-col--md-12';
            $container_class .= ' grid-cont--sm';
        }

        ?>

      <div id="<?= esc_attr($section_id) ?>" class="hero hero--auto">

          <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
              <?= wp_get_attachment_image($settings['image']['id'], 'full', false, array('class' => 'hero__bg')) ?>
          <?php endif; ?>

        <div class="<?= esc_attr($container_class)?>">
          <div class="grid-row grid-row--aic">

            <div class="<?= esc_attr($col_class) ?>">

                <?php if (isset($settings['title'])) : ?>
                  <h1 class="hero__title"><?= wp_kses_post($settings['title']) ?></h1>
                <?php endif; ?>

                <?php if (isset($settings['description'])) : ?>
                  <p class="mt-150 p p--md hero__subtitle"><?= wp_kses_post($settings['description']) ?></p>
                <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
        <?php
    }
}


