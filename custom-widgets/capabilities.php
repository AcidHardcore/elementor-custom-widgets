<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Capabilities extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-capabilities.css');
        wp_register_style('widget-capabilities', get_template_directory_uri() . '/css/widget-capabilities.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-capabilities.js');
        wp_register_script('widget-capabilities', get_template_directory_uri() . '/js/widget-capabilities.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-capabilities'];
    }

    public function get_script_depends()
    {
        return ['widget-capabilities'];
    }

    public function get_name()
    {
        return 'capabilities';
    }

    public function get_title()
    {
        return __('Capabilities', 'elementor-custom-widgets');
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
            'label' => __('Capabilities', 'elementor-custom-widgets'),
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
            'image',
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
                    '{{WRAPPER}} .capabilities__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .capabilities__title',
            ]
        );

        $this->add_control(
            'callout', [
                'label' => __('Callout', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'text', [
                'label' => __('Callout Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'raw' => 3,
            ]
        );

        $this->add_control(
            'callout_text_color',
            [
                'label' => __( 'Callout Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .capabilities-callout__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Callout Text Typography', 'elementor-custom-widgets' ),
                'name' => 'callout_text_typography',
                'selector' => '{{WRAPPER}} .capabilities-callout__text',
            ]
        );

        $this->add_control(
            'link_text', [
                'label' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Learn more', 'elementor-custom-widgets'),
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
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .capabilities__caption .tc-secondary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .capabilities__caption .tc-secondary',
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

        $repeater->add_control(
            'text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXTAREA,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $repeater->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .capabilities__caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .capabilities__caption',
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
            'list',
            [
                'label' => esc_html__('List', 'elementor-custom-widgets'),
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

        $this->add_control(
            'nop',
            [
                'label' => esc_html__('Reduce Spacing', 'elementor-custom-widgets'),
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
        $section_class = isset($settings['nop']) && $settings['nop'] === 'yes' ? 'section--nop' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        $title_class = ' capabilities__title';
        if ($settings['is_num'] === 'num') {
            $title_class .= ' num-title';
        }

        $button_class = 'button';
        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_link']);
            if($settings['button_type'] !== 'button') {
                $button_class .= ' ' . $settings['button_type'];

            }
        }

        $list = $settings['list'];
        is_array($list) ? $first_key = array_key_first($list) : $first_key = null;
        is_array($list) ? $last_key = array_key_last($list) : $last_key = null;
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section capabilities <?= esc_attr($section_class) ?>">
        <div class="grid-cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="ta-center tc-primary wow fadeInUp <?= esc_attr($title_class) ?>">

                  <?php if (isset($settings['image']) && !empty($settings['image']['id']) && $settings['is_num'] === 'num') : ?>
                    <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['image']['id'])); ?></div>
                  <?php endif; ?>

                  <?php if ($settings['is_num'] === 'num') : ?>
                    <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                  <?php else : ?>
                      <?= wp_kses_post($settings['title']) ?>
                  <?php endif; ?>

              </h2>
            <?php endif; ?>

          <div class="mt-250 capabilities__cont wow fadeInUp">

            <div class="capabilities__figure">
                <?php if (!empty($list)) : ?>
                    <?php foreach ($list as $index => $item) : ?>
                        <?php $class = 'elementor-repeater-item-' . $item['_id']; ?>
                        <?php $class .= $index === $first_key ? ' active ' : ''; ?>

                    <div class="capabilities__slide <?= esc_attr($class) ?> ">

                        <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                            <?= wp_get_attachment_image($item['image']['id'], 'full', false, array('class' => 'capabilities__cover')) ?>
                        <?php endif; ?>

                      <div class="capabilities__caption">

                          <?php if (isset($item['title'])) : ?>
                            <h3 class="tc-secondary"><?= wp_kses_post($item['title']) ?></h3>
                          <?php endif; ?>

                          <?php if (isset($item['text'])) : ?>
                            <p class="mt-100 p--md fw-700"><?= esc_html($item['text']) ?></p>
                          <?php endif; ?>
                      </div>

                    </div>

                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="capabilities__list">
                <?php if (!empty($list)) : ?>
                    <?php foreach ($list as $index => $item) : ?>
                        <?php
                        if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                            $this->add_link_attributes('cta_link', $item['cta_link']);
                        }
                        $class = $index === $first_key ? 'active ' : ''
                        ?>

                        <?php if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) : ?>
                      <a href="<?= esc_url($item['cta_link']['url']) ?>" data-index="<?= $index ?>" class="capabilities__item capabilities__item--link <?= esc_attr($class) ?>"><?= esc_html($item['title']) ?></a>
                        <?php else : ?>
                      <div data-index="<?= $index ?>" class="capabilities__item <?= esc_attr($class) ?>"><?= esc_html($item['title']) ?></div>
                        <?php endif; ?>

                        <?php if ($index !== $last_key) : ?>
                      <div class="capabilities__hr"></div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

          </div>
          <div class="mt-250 capabilities__callout wow fadeInUp">
            <div class="grid-row grid-row--aic">
              <div class="grid-col grid-col--sm-12">

                  <?php if (isset($settings['text'])) : ?>
                    <div class="h2 capabilities-callout__text"><?= esc_html($settings['text']) ?></div>
                  <?php endif; ?>

              </div>
              <div class="grid-col grid-col--auto grid-col--sm-12">

                  <?php if (isset($settings['link_text']) && !empty($settings['cta_link']['url'])) : ?>
                    <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                       class="<?= $button_class ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                        <?= esc_html($settings['link_text']) ?>
                    </a>
                  <?php endif; ?>

              </div>
            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


