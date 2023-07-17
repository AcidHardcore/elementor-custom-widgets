<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Meet_The_Team extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-meet-the-team.css');
        wp_register_style('widget-meet-the-team', get_template_directory_uri() . '/css/widget-meet-the-team.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-meet-the-team'];
    }

    public function get_name()
    {
        return 'meet-the-team';
    }

    public function get_title()
    {
        return __('Meet the Team', 'elementor-custom-widgets');
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
            'label' => __('Meet the Team', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'is_num',
            [
                'label' => esc_html__('Title Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Num', 'elementor-custom-widgets'),
                'label_off' => esc_html__('Default', 'elementor-custom-widgets'),
                'return_value' => 'num',
                'default' => 'default',
            ]
        );

        $this->add_control(
            'num_image',
            [
                'label' => esc_html__('Choose Number', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'is_num' => 'num'
                ]
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
                    '{{WRAPPER}} .meet-the-team__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .meet-the-team__title',
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Number', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'year', [
                'label' => __('Years', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Years', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'text', [
                'label' => __('Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Text', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meet-the-team__head-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .meet-the-team__head-subtitle',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __( 'Name Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meet-the-team__name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Name Typography', 'elementor-custom-widgets' ),
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .meet-the-team__name',
            ]
        );

        $this->add_control(
            'position_color',
            [
                'label' => __( 'Position Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meet-the-team__position' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Position Typography', 'elementor-custom-widgets' ),
                'name' => 'position_typography',
                'selector' => '{{WRAPPER}} .meet-the-team__position',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'photo',
            [
                'label' => esc_html__('Choose Photo', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'name', [
                'label' => __('Name', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Name', 'elementor-custom-widgets'),
            ]
        );

        $repeater->add_control(
            'position', [
                'label' => esc_html__('Position', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Position', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'team',
            [
                'label' => esc_html__('Team', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'name' => esc_html__('Name #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ name }}}',
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

        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section section--nobg meet-the-team">
        <div class="grid-cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-primary num-title wow fadeInUp meet-the-team__title">

                  <?php if (isset($settings['num_image']) && !empty($settings['num_image']['id']) && $settings['is_num'] === 'num') : ?>
                    <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['num_image']['id'])); ?></div>
                  <?php endif; ?>

                  <?php if ($settings['is_num'] === 'num') : ?>
                    <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                  <?php else : ?>
                      <?= wp_kses_post($settings['title']) ?>
                  <?php endif; ?>
              </h2>
            <?php endif; ?>

          <div class="mt-300 meet-the-team__head wow fadeInUp">
            <div class="grid-row grid-row--smg grid-row--aic grid-row--jcc">
              <div class="grid-col grid-col--auto">

                  <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                      <?= wp_get_attachment_image($settings['image']['id'], 'full') ?>
                  <?php endif; ?>

              </div>
              <div class="grid-col grid-col--auto">

                  <?php if (isset($settings['year'])) : ?>
                    <div class="meet-the-team__head-title"><?= wp_kses_post($settings['year']) ?></div>
                  <?php endif; ?>

              </div>
            </div>

              <?php if (isset($settings['text'])) : ?>
                <div class="meet-the-team__head-subtitle"><?= wp_kses_post($settings['text']) ?></div>
              <?php endif; ?>

          </div>
            <?php if (isset($settings['team'])) : ?>
              <div class="grid-row">
                  <?php foreach ($settings['team'] as $item) : ?>

                    <div class="mt-300 grid-col grid-col--4 grid-col--md-6 grid-col--xs-12 wow fadeInUp">
                      <div class="meet-the-team__item">

                          <?php if (isset($item['photo']) && !empty($item['photo']['id'])) : ?>
                              <?= wp_get_attachment_image($item['photo']['id'], 'full', false, array('class' => 'meet-the-team__photo')) ?>
                          <?php endif; ?>

                          <?php if (isset($item['name'])) : ?>
                            <div class="mt-100 h5 meet-the-team__name"><?= wp_kses_post($item['name']) ?></div>
                          <?php endif; ?>

                          <?php if (isset($item['position'])) : ?>
                            <div class="meet-the-team__position"><?= wp_kses_post($item['position']) ?></div>
                          <?php endif; ?>

                      </div>
                    </div>

                  <?php endforeach; ?>
              </div>
            <?php endif; ?>
        </div>
      </div>

        <?php
    }
}


