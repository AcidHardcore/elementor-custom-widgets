<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Turf_Recommnder extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-turf-recommender.css');
        wp_register_style('widget-turf-recommender', get_template_directory_uri() . '/css/widget-turf-recommender.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-turf-recommender'];
    }

    public function get_name()
    {
        return 'turf-recommender';
    }

    public function get_title()
    {
        return __('Turf Recommender', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-columns';
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
            'label' => __('Turf Recommender', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

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
                'default' => 'white',
                'selectors' => [
                    '{{WRAPPER}} .synt-turf-recommender__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .synt-turf-recommender__title',
            ]
        );

        $this->add_control('text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
        ]);

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .synt-turf-recommender__text > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .synt-turf-recommender__text > *',
            ]
        );

        $this->add_control(
            'cta_text', [
                'label' => esc_html__( 'Learn More', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Link Text' , 'elementor-custom-widgets' ),
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

      <div id="<?= esc_attr($section_id) ?>" class="section synt-turf-recommender">
        <div class="grid-cont">
          <div class="grid-row grid-row--nog grid-row--jcc">
            <div class="grid-col grid-col--7 grid-col--lg-9 grid-col--md-12 wow fadeInUp">
                <?php if (isset($settings['title'])) : ?>
                  <h2 class="num-title synt-turf-recommender__title">

                      <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                          <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['image']['id'])); ?></div>
                      <?php endif; ?>
                    <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                  </h2>
                <?php endif; ?>

                <?php if (isset($settings['text'])) : ?>
                  <div class="mt-150 synt-turf-recommender__text"><?= wp_kses_post($settings['text']) ?></div>
                <?php endif; ?>

                <?php if (isset($settings['cta_text']) && !empty($settings['cta_link']['url'])) : ?>
                  <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                     class="mt-150 <?= esc_attr($link_class) ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                      <?= esc_html($settings['cta_text']) ?>
                  </a>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


