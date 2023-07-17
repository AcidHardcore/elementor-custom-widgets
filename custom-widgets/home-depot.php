<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Home_Depot extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-home-depot.css');
        wp_register_style('widget-home-depot', get_template_directory_uri() . '/css/widget-home-depot.css', array('main-styles'), $css_version);
    }

    public function get_style_depends()
    {
        return ['widget-home-depot'];
    }

    public function get_name()
    {
        return 'home-depot';
    }

    public function get_title()
    {
        return __('Home Depot', 'elementor-custom-widgets');
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
            'label' => __('Home Depot', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control('text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $this->add_control(
            'logo',
            [
                'label' => esc_html__('Choose Logo', 'elementor-custom-widgets'),
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

        $this->add_control(
            'cta_text', [
                'label' => esc_html__('Learn More', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Buy Now', 'elementor-custom-widgets'),
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
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section home-depot">
        <div class="grid-cont">
          <div class="grid-row grid-row--aic">
            <div class="grid-col grid-col--auto wow fadeInUp">
                <?php if (isset($settings['logo']) && !empty($settings['logo']['id'])) : ?>
                    <?= wp_get_attachment_image($settings['logo']['id'], 'full') ?>
                <?php endif; ?>
            </div>
            <div class="mt-200 grid-col grid-col--auto grid-col--md-12"></div>
            <div data-wow-delay="0.25s" class="grid-col grid-col--sm-12 wow fadeInUp">

                <?php if (isset($settings['title'])) : ?>
                  <h2 class="tc-primary"><?= wp_kses_post($settings['title']) ?></h2>
                <?php endif; ?>

              <div class="grid-row grid-row--smg">
                <div class="grid-col">
                    <?php if (isset($settings['text']) && !empty($settings['text'])) : ?>
                      <div class="mt-100"><?= wp_kses_post($settings['text']) ?></div>
                    <?php endif; ?>

                    <?php if (isset($settings['cta_text']) && !empty($settings['cta_link']['url'])) : ?>
                      <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                         class="mt-150 button button--primary" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                          <?= esc_html($settings['cta_text']) ?>
                      </a>
                    <?php endif; ?>

                </div>
                <div class="grid-col grid-col--auto removed-sm"></div>
                <div class="grid-col grid-col--auto removed-sm">

                    <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                        <?= wp_get_attachment_image($settings['image']['id'], 'full', false, array('class' => 'mt-100')) ?>
                    <?php endif; ?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        <?php
    }
}


