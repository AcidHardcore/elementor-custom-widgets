<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class What_Is_Infill extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-what-is-infill.css');
        wp_register_style('widget-what-is-infill', get_template_directory_uri() . '/css/widget-what-is-infill.css', array('main-styles'), $css_version);
    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-what-is-infill'];
    }

    public function get_name()
    {
        return 'what-is-infill';
    }

    public function get_title()
    {
        return __('What is Infill', 'elementor-custom-widgets');
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
            'label' => __('What is Infill', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'is_num',
            [
                'label' => esc_html__('Title Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Num', 'elementor-custom-widgets'),
                'label_off' => esc_html__('Default', 'elementor-custom-widgets'),
                'return_value' => 'num',
                'default' => 'default',
            ]
        );

        $this->add_control(
            'num_image',
            [
                'label' => esc_html__('Choose Number', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'is_num' => 'num'
                ]
            ]
        );

        $this->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .what-is-infill__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .what-is-infill__title',
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
                    '{{WRAPPER}} .what-is-infill__text > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .what-is-infill__text > *',
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
        $title_class = 'tc-primary what-is-infill__title';
        if ($settings['is_num'] === 'num') {
            $title_class .= ' num-title';
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section section--nobg what-is-infill">
        <div class="grid-cont">
          <div class="grid-row grid-row--aic">
            <div class="grid-col grid-col--6 grid-col--sm-12 wow fadeInUp">
              <div class="what-is-infill__figure">
                  <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                      <?= wp_get_attachment_image($settings['image']['id'], 'full') ?>
                  <?php endif; ?>
              </div>
            </div>
            <div data-wow-delay="0.25s" class="grid-col grid-col--6 grid-col--sm-12 wow fadeInUp">

                <?php if (isset($settings['title'])) : ?>
                  <h2 class="<?= esc_attr($title_class) ?>">

                      <?php if (isset($settings['num_image']) && !empty($settings['num_image']['id']) && $settings['is_num'] === 'num') : ?>
                         <div class="num-title__img num-title__img--left"><?= file_get_contents(wp_get_original_image_path($settings['num_image']['id'])); ?></div>
                      <?php endif; ?>

                      <?php if ($settings['is_num'] === 'num') : ?>
                        <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                      <?php else : ?>
                          <?= wp_kses_post($settings['title']) ?>
                      <?php endif; ?>

                  </h2>
                <?php endif; ?>

                <?php if (isset($settings['text']) && !empty($settings['text'])) : ?>
                   <div class="mt-150 p p--md what-is-infill__text"><?= wp_kses_post($settings['text']) ?></div>
                <?php endif; ?>

            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


