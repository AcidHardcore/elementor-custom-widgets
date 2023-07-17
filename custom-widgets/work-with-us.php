<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Work_With_Us extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-work-with-us.css');
        wp_register_style('widget-work-with-us', get_template_directory_uri() . '/css/widget-work-with-us.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-work-with-us'];
    }

    public function get_name()
    {
        return 'work-with-us';
    }

    public function get_title()
    {
        return __('Work With Us', 'elementor-custom-widgets');
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
            'label' => __('Work With Us', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

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
            'item_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .work-with-us__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'item_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .work-with-us__title',
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
                    '{{WRAPPER}} {{CURRENT_ITEM}} .work-with-us__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .work-with-us__text > *',
            ]
        );

        $repeater->add_control(
            'link_text', [
                'label' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('List Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'button_type',
            [
                'label' => esc_html__('Button type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'button--alt',
                'options' => $this->config::BUTTON_TYPE
            ]
        );

        $repeater->add_control(
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

        $this->add_control(
            'options',
            [
                'label' => esc_html__('Options', 'elementor-custom-widgets'),
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

      <div id="<?= esc_attr($section_id) ?>" class="section work-with-us">
        <div class="grid-cont">
          <div class="work-with-us__row wow fadeInUp">
              <?php
              $delay = 0;
              foreach ($settings['options'] as $index => $item) :
                  $class = 'elementor-repeater-item-' . $item['_id'];
                  $class .= $index > 0 ? ' wow fadeInUp ' : '';

                  $button_class = 'button';
                  if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                      $this->add_link_attributes('cta_link', $item['cta_link']);
                      if($item['button_type'] !== 'button') {
                          $button_class .= ' ' . $item['button_type'];
                      }
                  }

                  ?>
                <div data-wow-delay="<?= $delay ?>s" class="work-with-us__col <?= esc_attr($class) ?>">

                    <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                        <?= wp_get_attachment_image($item['image']['id'], 'full', false, array('class' => 'work-with-us__image')) ?>
                    <?php endif; ?>

                  <div class="work-with-us__item">

                      <?php if (isset($item['title'])) : ?>
                        <h2 class="work-with-us__title"><?= wp_kses_post($item['title']) ?></h2>
                      <?php endif; ?>

                      <?php if (isset($item['text'])) : ?>
                        <div class="mt-100 work-with-us__text"><?= wp_kses_post($item['text']) ?></div>
                      <?php endif; ?>

                      <?php if (isset($item['link_text']) && !empty($item['cta_link']['url'])) : ?>
                        <a href="<?= esc_url($item['cta_link']['url']) ?>"
                           class="mt-150 <?= esc_attr($button_class) ?> button--enlarged" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                            <?= esc_html($item['link_text']) ?>
                        </a>
                      <?php endif; ?>

                  </div>
                </div>

                  <?php $delay += 0.25; ?>
              <?php endforeach; ?>

          </div>
        </div>
      </div>

        <?php
    }
}


