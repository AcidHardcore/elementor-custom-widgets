<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Infill_Envirofill extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-infill-envirofill.css');
        wp_register_style('widget-infill-envirofill', get_template_directory_uri() . '/css/widget-infill-envirofill.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-infill-envirofill'];
    }

    public function get_name()
    {
        return 'infill-envirofill';
    }

    public function get_title()
    {
        return __('Infill Envirofill', 'elementor-custom-widgets');
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
            'label' => __('Infill Envirofill', 'elementor-custom-widgets'),
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
            'image',
            [
                'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
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
                    '{{WRAPPER}} .infill-envirofill__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .infill-envirofill__text > *',
            ]
        );

        $this->add_control(
            'cta_text', [
                'label' => esc_html__('Learn More', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Learn More', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => esc_html__('Button type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'button',
                'options' => $this->config::BUTTON_TYPE
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
        $button_class = 'button';
        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_link']);
            if($settings['button_type'] !== 'button') {
                $button_class .= ' ' . $settings['button_type'];

            }
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section infill-envirofill wow fadeInUp">
        <div class="grid-cont">
          <div class="grid-row grid-row--nog grid-row--jcc">
            <div class="grid-col grid-col--8 grid-col--md-12">

                <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                    <?= wp_get_attachment_image($settings['image']['id'], 'full') ?>
                <?php endif; ?>

                <?php if (isset($settings['text'])) : ?>
                  <div class="mt-100 infill-envirofill__text"><?= wp_kses_post($settings['text']) ?></div>
                <?php endif; ?>

                <?php if (isset($settings['cta_text']) && !empty($settings['cta_link']['url'])) : ?>
                  <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                     class="mt-100 <?= $button_class ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                      <?= esc_html($settings['cta_text']) ?>
                  </a>
                <?php endif; ?>

            </div>
          </div>
        </div>

          <?php if (isset($settings['number']) && !empty($settings['number']['id'])) : ?>
            <div class="infill-envirofill__num"><?= file_get_contents(wp_get_original_image_path($settings['number']['id'])); ?></div>
          <?php endif; ?>

      </div>

        <?php
    }
}


