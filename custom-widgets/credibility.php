<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Credibility extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-credibility.css');
        wp_register_style('widget-credibility', get_template_directory_uri() . '/css/widget-credibility.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-credibility.js');
        wp_register_script('widget-credibility', get_template_directory_uri() . '/js/widget-credibility.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['slick-styles', 'widget-credibility'];
    }

    public function get_script_depends() {
        return ['slick-scripts', 'widget-credibility'];
    }

    public function get_name()
    {
        return 'credibility';
    }

    public function get_title()
    {
        return __('Credibility', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-pro-icon';
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
            'label' => __('Credibility', 'elementor-custom-widgets'),
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
                    '{{WRAPPER}} h2.credibility__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} h2.credibility__title',
            ]
        );

        $this->add_control(
            'text', [
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
                    '{{WRAPPER}} .credibility__desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .credibility__desc',
            ]
        );

        $repeater = new Repeater();

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
                    '{{WRAPPER}} {{CURRENT_ITEM}} .credibility__heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .credibility__heading',
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
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $repeater->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .credibility__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .credibility__text',
            ]
        );



        $this->add_control(
            'items',
            [
                'label' => esc_html__('Credibility', 'elementor-custom-widgets'),
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

      <div id="<?= esc_attr($section_id) ?>" class="section credibility">
        <div class="grid-cont">
          <div class="grid-row grid-row--nog grid-row--jcc">
            <div class="grid-col grid-col--9 grid-col--md-12 wow fadeInUp">
                <?php if (isset($settings['title'])) : ?>
                  <h2 class="tc-secondary credibility__title"><?= wp_kses_post($settings['title']) ?></h2>
                <?php endif; ?>

                <?php if (isset($settings['text'])) : ?>
                  <div class="mt-100 credibility__desc"><?= wp_kses_post($settings['text']) ?></div>
                <?php endif; ?>
            </div>
          </div>

          <div id="credibility__carousel" class="credibility__carousel mt-200 wow fadeInUp">
              <?php foreach ($settings['items'] as $index => $item) : ?>
                <?php $current_item_class = 'elementor-repeater-item-' . $item['_id'];?>
                <div class="credibility__slide <?= esc_attr($current_item_class) ?>">
                  <div class="credibility__logo">
                      <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                          <?= wp_get_attachment_image($item['image']['id'], 'full') ?>
                      <?php endif; ?>
                  </div>

                    <?php if (isset($item['title'])) : ?>
                      <div class="mt-100 p credibility__heading"><?= wp_kses_post($item['title']) ?></div>
                    <?php endif; ?>

                    <?php if (isset($item['text'])) : ?>
                      <div class="mt-050 credibility__text"><?= wp_kses_post($item['text']) ?></div>
                    <?php endif; ?>

                </div>

              <?php endforeach; ?>
          </div>

        </div>
      </div>

        <?php
    }
}


