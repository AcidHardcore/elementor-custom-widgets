<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Accolades extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-accolades.css');
        wp_register_style('widget-accolades', get_template_directory_uri() . '/css/widget-accolades.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-accolades.js');
        wp_register_script('widget-accolades', get_template_directory_uri() . '/js/widget-accolades.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['slick-styles', 'widget-content', 'widget-accolades'];
    }

    public function get_script_depends() {
        return ['slick-scripts', 'widget-accolades'];
    }

    public function get_name()
    {
        return 'accolades';
    }

    public function get_title()
    {
        return __('Accolades', 'elementor-custom-widgets');
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
            'label' => __('Accolades', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'number',
            [
                'label' => esc_html__('Choose Number', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
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
                'default' => 'white',
                'selectors' => [
                    '{{WRAPPER}} .accolades__title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Core\Schemes\Color::get_type(),
                    'value' => Core\Schemes\Color::COLOR_1,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .accolades__title',
                'scheme' => Core\Schemes\Typography::TYPOGRAPHY_1,
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
                'default' => 'secondary',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .accolades__heading .tc-secondary' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Core\Schemes\Color::get_type(),
                    'value' => Core\Schemes\Color::COLOR_1,
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .accolades__heading .tc-secondary',
                'scheme' => Core\Schemes\Typography::TYPOGRAPHY_1,
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
                'default' => 'white',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .accolades__text > *' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Core\Schemes\Color::get_type(),
                    'value' => Core\Schemes\Color::COLOR_1,
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .accolades__text > *',
                'scheme' => Core\Schemes\Typography::TYPOGRAPHY_1,
            ]
        );


        $this->add_control(
            'values',
            [
                'label' => esc_html__('Values', 'elementor-custom-widgets'),
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

      <div id="<?= esc_attr($section_id) ?>" class="section accolades">
        <div class="grid-cont accolades__cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="num-title wow fadeInUp accolades__title">

                  <?php if (isset($settings['number']) && !empty($settings['number']['id'])) : ?>
                    <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['number']['id'])); ?></div>
                  <?php endif; ?>
                <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
              </h2>
            <?php endif; ?>

          <div id="accolades__carousel" class="mt-400 accolades__carousel wow fadeInUp">

              <?php foreach ($settings['values'] as $item) : ?>
              <?php $current_item_class = 'elementor-repeater-item-' . $item['_id'];?>

                <div class="accolades__slide <?= $current_item_class ?>">

                  <div class="accolades__image">
                      <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                          <?= wp_get_attachment_image($item['image']['id'], 'full') ?>
                      <?php endif; ?>
                  </div>

                    <?php if (isset($item['title'])) : ?>
                      <div class="mt-150 accolades__heading"><strong class="tc-secondary"><?= wp_kses_post($item['title']) ?></strong></div>
                    <?php endif; ?>

                    <?php if (isset($item['text'])) : ?>
                      <div class="accolades__text"><?= wp_kses_post($item['text']) ?></div>
                    <?php endif; ?>

                </div>
              <?php endforeach; ?>

          </div>
        </div>
      </div>
        <?php
    }
}


