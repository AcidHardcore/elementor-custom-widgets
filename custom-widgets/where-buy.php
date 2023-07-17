<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Where_Buy extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-where-buy.css');
        wp_register_style('widget-where-buy', get_template_directory_uri() . '/css/widget-where-buy.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-where-buy'];
    }

    public function get_name()
    {
        return 'where-buy';
    }

    public function get_title()
    {
        return __('Where to Buy', 'elementor-custom-widgets');
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
            'label' => __('Where to Buy', 'elementor-custom-widgets'),
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

        $this->add_control('text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
        ]);

        $this->add_control(
            'link_text', [
                'label' => esc_html__( 'Link Text', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Link Text' , 'elementor-custom-widgets' ),
                'label_block' => true,
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
        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_link']);
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="mt-400 section where-buy">
        <div class="grid-cont">
          <div class="grid-row grid-row--nog grid-row--jcc">
            <div class="grid-col grid-col--8 grid-col--md-12 wow fadeInUp">

                <?php if (isset($settings['title'])) : ?>
                  <h2 class="num-title">

                      <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                          <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['image']['id'])); ?></div>
                      <?php endif; ?>
                    <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                  </h2>
                <?php endif; ?>

                <?php if (isset($settings['text'])) : ?>
                  <div class="mt-150"><?= wp_kses_post($settings['text']) ?></div>
                <?php endif; ?>

                <?php if (isset($settings['link_text']) && !empty($settings['cta_link']['url'])) : ?>
                  <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                     class="mt-150 cta-link cta-link--alt" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                      <?= esc_html($settings['link_text']) ?>
                  </a>
                <?php endif; ?>

            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


