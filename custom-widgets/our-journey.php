<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Our_Journey extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-our-journey.css');
        wp_register_style('widget-our-journey', get_template_directory_uri() . '/css/widget-our-journey.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-our-journey.js');
        wp_register_script('widget-our-journey', get_template_directory_uri() . '/js/widget-our-journey.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['slick-styles', 'widget-our-journey'];
    }

    public function get_script_depends() {
        return ['slick-scripts', 'widget-our-journey'];
    }

    public function get_name()
    {
        return 'our-journey';
    }

    public function get_title()
    {
        return __('Our Journey', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-banner';
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
            'label' => __('Our Journey', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

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
                    '{{WRAPPER}} .our-journey__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .our-journey__title',
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
                    '{{WRAPPER}} .our-journey__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .our-journey__text',
            ]
        );

        $this->add_control(
            'year_color',
            [
                'label' => __( 'Year Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .our-journey__year' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Year Typography', 'elementor-custom-widgets' ),
                'name' => 'year_typography',
                'selector' => '{{WRAPPER}} .our-journey__year',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __( 'Heading Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .our-journey__heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Heading Typography', 'elementor-custom-widgets' ),
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .our-journey__heading',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => __( 'Description Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .our-journey__desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Description Typography', 'elementor-custom-widgets' ),
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .our-journey__desc',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'year', [
                'label' => esc_html__('Year', 'elementor-custom-widgets'),
                'type' => Controls_Manager::NUMBER,
                'label_block' => true,
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
            'image',
            [
                'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control('text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 3,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);


        $this->add_control(
            'slides',
            [
                'label' => esc_html__('Slides', 'elementor-custom-widgets'),
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

      <div id="<?= esc_attr($section_id) ?>" class="section section--nobg our-journey <?= esc_attr($section_class) ?>">
        <div class="grid-cont">
          <div class="grid-row grid-row--nog grid-row--jcc">
            <div class="grid-col grid-col--9 grid-col--md-12 wow fadeInUp">

                <?php if (isset($settings['title'])) : ?>
                  <h2 class="tc-primary our-journey__title"><?= wp_kses_post($settings['title']) ?></h2>
                <?php endif; ?>


                <?php if (isset($settings['text'])) : ?>
                  <div class="mt-100 our-journey__text"><?= wp_kses_post($settings['text']) ?></div>
                <?php endif; ?>

            </div>
          </div>
        </div>
        <div class="mt-300 our-journey__cont">
          <div class="grid-cont">
            <div class="our-journey__track">

                <?php if (isset($settings['slides']) && is_array($settings['slides'])) : ?>
                    <?php foreach ($settings['slides'] as $index => $item) : ?>

                    <div class="our-journey__slide <?= $index === 0 ? 'active' : '' ?>">
                      <div class="grid-row grid-row--nog grid-row--aic">
                        <div class="grid-col grid-col--3 grid-col--md-4 grid-col--sm-12">
                          <div class="our-journey__figure">

                              <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                                  <?= wp_get_attachment_image($item['image']['id'], 'full') ?>
                              <?php endif; ?>

                          </div>
                        </div>
                        <div class="grid-col grid-col--1 grid-col--sm-12"></div>
                        <div class="grid-col grid-col--8 grid-col--md-7 grid-col--sm-12 <?= $index === 0 ? 'wow fadeInUp' : '' ?>">

                            <?php if (isset($item['year'])) : ?>
                              <h3 class="our-journey__year"><?= wp_kses_post($item['year']) ?></h3>
                            <?php endif; ?>

                            <?php if (isset($item['title'])) : ?>
                              <h3 class="mt-050 tc-primary our-journey__heading"><?= wp_kses_post($item['title']) ?></h3>
                            <?php endif; ?>

                            <?php if (isset($item['text'])) : ?>
                              <p class="mt-050 p p--lg our-journey__desc"><?= wp_kses_post($item['text']) ?></p>
                            <?php endif; ?>

                        </div>
                      </div>
                    </div>

                    <?php endforeach; ?>

                  <div class="our-journey__dots">

                      <?php foreach ($settings['slides'] as $index => $item) : ?>
                        <button type="button" class="<?= $index === 0 ? 'active' : '' ?>"></button>
                      <?php endforeach; ?>

                  </div>
                  <div class="arrows our-journey__arrows">
                    <button type="button" class="arrows__prev"></button>
                    <button type="button" class="arrows__next"></button>
                  </div>

                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


