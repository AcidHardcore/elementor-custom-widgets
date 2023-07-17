<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Follow_Us_Alt extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

    }

    public function get_style_depends()
    {
        return ['widget-follow-us'];
    }

    public function get_name()
    {
        return 'follow-us-alt';
    }

    public function get_title()
    {
        return __('Follow Us Alt', 'elementor-custom-widgets');
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
            'label' => __('Follow Us Alt', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

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
                    '{{WRAPPER}} .follow-us__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .follow-us__title',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .follow-us__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .follow-us__text',
            ]
        );

        $this->add_control('text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
        ]);

        $repeater = new Repeater();

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

        $repeater->add_control(
            'title', [
            'label' => __('Title', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
        ]);

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
            'socials',
            [
                'label' => esc_html__('Socials', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'social_heading_color',
            [
                'label' => __( 'Social Heading Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .follow-us__social-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Social Heading Typography', 'elementor-custom-widgets' ),
                'name' => 'social_heading_typography',
                'selector' => '{{WRAPPER}} .follow-us__social-heading',
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

        $this->add_control(
            'stacked',
            [
                'label' => esc_html__('Stacked', 'elementor-custom-widgets'),
                'description' => 'Decreasing space to the bottom section with the same option enabled',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('No', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_class = isset($settings['stacked']) && $settings['stacked'] === 'yes' ? 'section--nobg' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        $last_key = array_key_last($settings['socials']);
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section follow-us follow-us--alt <?= esc_attr($section_class) ?>">
        <div class="grid-cont grid-cont--sm">
          <div class="grid-row grid-row--aic grid-row--jcb">
            <div class="grid-col grid-col--md-12 wow fadeInUp">

                <?php if (isset($settings['title'])) : ?>
                  <h2 class="tc-secondary follow-us__title">
                      <?= wp_kses_post($settings['title']) ?>
                  </h2>
                <?php endif; ?>

                <?php if (isset($settings['text'])) : ?>
                  <p class="mt-100 p p--lg follow-us__text"><?= wp_kses_post($settings['text']) ?></p>
                <?php endif; ?>

            </div>
            <div class="mt-300 grid-col grid-col--auto grid-col--md-12"></div>
            <div data-wow-delay="0.25s" class="grid-col grid-col--auto grid-col--sm-12 wow fadeInUp">
              <div class="grid-row grid-row--aic">
                  <?php foreach ($settings['socials'] as $index => $item) : ?>
                      <?php
                      if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                          $this->add_link_attributes('cta_link', $item['cta_link']);
                      }
                      ?>
                    <div class="grid-col grid-col--auto">

                        <?php if (isset($item['title'])) : ?>
                          <h4 class="follow-us__social-heading"> <?= wp_kses_post($item['title']) ?></h4>
                        <?php endif; ?>

                        <?php if (!empty($item['cta_link']['url'])) : ?>

                          <a href="<?php echo esc_url($item['cta_link']['url']) ?>"
                             class="mt-100 follow-us__link" <?= $this->get_render_attribute_string('cta_link'); ?>>

                              <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                                  <?= file_get_contents(wp_get_original_image_path($item['image']['id'])); ?>
                              <?php endif; ?>

                          </a>
                        <?php endif; ?>

                    </div>

                      <?php if ($index != $last_key) : ?>
                      <div class="grid-col grid-col--auto">
                        <div class="follow-us__vr"></div>
                      </div>
                      <?php endif; ?>

                  <?php endforeach; ?>

              </div>
            </div>
          </div>
        </div>
      </div>



        <?php
    }
}


