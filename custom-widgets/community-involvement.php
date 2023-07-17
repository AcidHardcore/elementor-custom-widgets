<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Community_Involvement extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-community-involvement.css');
        wp_register_style('widget-community-involvement', get_template_directory_uri() . '/css/widget-community-involvement.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-community-involvement'];
    }

    public function get_name()
    {
        return 'community-involvement';
    }

    public function get_title()
    {
        return __('Community Involvement', 'elementor-custom-widgets');
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
            'label' => __('Community Involvement', 'elementor-custom-widgets'),
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
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .community-involvement__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .community-involvement__title',
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
                    '{{WRAPPER}} {{CURRENT_ITEM}} .community-involvement__caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .community-involvement__caption',
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
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $repeater->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .community-involvement__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .community-involvement__text',
            ]
        );


        $this->add_control(
            'drivers',
            [
                'label' => esc_html__('Drivers', 'elementor-custom-widgets'),
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

      <div id="<?= esc_attr($section_id) ?>" class="section community-involvement">
        <div class="grid-cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="wow fadeInUp community-involvement__title"><?= wp_kses_post($settings['title']) ?></h2>
            <?php endif; ?>

          <div class="grid-row grid-row--jcc">

              <?php
              $delay = 0.25;
              foreach ($settings['drivers'] as $item) :
                  $current_item_class = 'elementor-repeater-item-' . $item['_id'];
                  ?>

                <div data-wow-delay="<?= $delay ?>s" class="mt-200 grid-col grid-col--md-6 grid-col--sm-12 wow fadeInUp <?= $current_item_class ?>">
                  <div class="community-involvement__value">

                      <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                          <?= wp_get_attachment_image($item['image']['id'], 'full') ?>
                      <?php endif; ?>

                      <?php if (isset($item['text'])) : ?>
                        <span class="community-involvement__text"><?= wp_kses_post($item['text']) ?></span>
                      <?php endif; ?>
                  </div>

                    <?php if (isset($item['title'])) : ?>
                      <div class="h3 community-involvement__caption"><?= wp_kses_post($item['title']) ?></div>
                    <?php endif; ?>

                </div>

                  <?php $delay += 0.25; ?>
              <?php endforeach; ?>
          </div>
        </div>
      </div>
        <?php
    }
}


