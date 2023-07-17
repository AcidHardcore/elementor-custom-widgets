<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Natural_Gas_Services extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-natural-gas-services.css');
        wp_register_style('widget-natural-gas-services', get_template_directory_uri() . '/css/widget-natural-gas-services.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-natural-gas-services'];
    }

    public function get_name()
    {
        return 'natural-gas-services';
    }

    public function get_title()
    {
        return __('Natural Gas Services', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
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
            'label' => __('Natural Gas Services', 'elementor-custom-widgets'),
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
                    '{{WRAPPER}} .natural-grass-services__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .natural-grass-services__title',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .natural-grass-services__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .natural-grass-services__text',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'text', [
                'label' => esc_html__('Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'row' => 3,
                'placeholder' => esc_html__('Text', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => esc_html__('List', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Item #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->add_control(
            'cta_text', [
                'label' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_type',
            [
                'label' => esc_html__('Link type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cta-link',
                'options' => $this->config::LINK_TYPE
            ]
        );

        $this->add_control(
            'cta_link',
            [
                'label' => esc_html__('CTA Link', 'elementor-custom-widgets'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'elementor-custom-widgets'),
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                    'custom_attributes' => '',
                ],
                'label_block' => true,
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
        $link_class = 'cta-link';
        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_link']);
            if($settings['link_type'] !== 'cta-link') {
                $link_class .= ' ' . $settings['link_type'];
            }
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section natural-grass-services">

          <?php if (isset($settings['number']) && !empty($settings['number']['id'])) : ?>
            <div class="natural-grass-services__num"><?= file_get_contents(wp_get_original_image_path($settings['number']['id'])); ?></div>
          <?php endif; ?>

        <div class="grid-cont">
          <div class="natural-grass-services__cont wow fadeInUp">

              <?php if (isset($settings['title'])) : ?>
                <h2 class="tc-secondary natural-grass-services__title"><?= wp_kses_post($settings['title']) ?></h2>
              <?php endif; ?>

              <?php if (isset($settings['list'])) : ?>
                  <?php foreach ($settings['list'] as $item) : ?>

                      <?php if (isset($item['text'])) : ?>
                    <p class="mt-125 p p--lg natural-grass-services__text"><?= wp_kses_post($item['text']) ?></p>
                      <?php endif; ?>

                  <?php endforeach; ?>
              <?php endif; ?>

              <?php if (isset($settings['cta_text']) && !empty($settings['cta_link']['url'])) : ?>
                <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                   class="mt-150 <?= $link_class ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                    <?= esc_html($settings['cta_text']) ?>
                </a>
              <?php endif; ?>

          </div>
        </div>
      </div>



        <?php
    }
}


