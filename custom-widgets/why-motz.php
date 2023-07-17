<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Why_Motz extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-why-motz.css');
        wp_register_style('widget-why-motz', get_template_directory_uri() . '/css/widget-why-motz.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-why-motz'];
    }

    public function get_name()
    {
        return 'why-motz';
    }

    public function get_title()
    {
        return __('Why Motz', 'elementor-custom-widgets');
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
            'label' => __('Why Motz', 'elementor-custom-widgets'),
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
                'label' => esc_html__('Choose NUmber', 'elementor-custom-widgets'),
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
                    '{{WRAPPER}} .why-motz__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .why-motz__title',
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
                    '{{WRAPPER}} .why-motz__text > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .why-motz__text > *',
            ]
        );

        $this->add_control(
            'link_text', [
                'label' => esc_html__( 'Link Text', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Link Text' , 'elementor-custom-widgets' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => esc_html__('Button type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'button--primary',
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

      <div id="<?= esc_attr($section_id) ?>" class="section why-motz">
        <div class="grid-cont grid-cont--sm">
          <div class="grid-row grid-row--aic grid-row--jcc">
            <div class="grid-col grid-col--sm-12">

                <?php if (isset($settings['title'])) : ?>
                  <h2 class="tc-primary num-title why-motz__title">

                      <?php if (isset($settings['number']) && !empty($settings['number']['id'])) : ?>
                        <div class="num-title__img num-title__img--left"><?= file_get_contents(wp_get_original_image_path($settings['number']['id'])); ?></div>
                      <?php endif; ?>
                    <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                  </h2>
                <?php endif; ?>

                <?php if (isset($settings['text'])) : ?>
                  <div class="mt-150 why-motz__text"><?= wp_kses_post($settings['text']) ?></div>
                <?php endif; ?>

                <?php if (isset($settings['link_text']) && !empty($settings['cta_link']['url'])) : ?>
                  <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                     class="mt-150 <?= esc_attr($button_class)?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                      <?= esc_html($settings['link_text']) ?>
                  </a>
                <?php endif; ?>

            </div>

            <div class="grid-col grid-col--5 grid-col--sm-8">

                <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                    <?= wp_get_attachment_image($settings['image']['id'], 'full', false, array('class' => 'why-motz__logo')) ?>
                <?php endif; ?>

            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


