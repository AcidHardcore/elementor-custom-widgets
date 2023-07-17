<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Maintenance_Offerings extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-maintenance-offerings.css');
        wp_register_style('widget-maintenance-offerings', get_template_directory_uri() . '/css/widget-maintenance-offerings.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-maintenance-offerings'];
    }

    public function get_name()
    {
        return 'maintenance-offerings';
    }

    public function get_title()
    {
        return __('Maintenance Offerings', 'elementor-custom-widgets');
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
            'label' => __('Maintenance Offerings', 'elementor-custom-widgets'),
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
                    '{{WRAPPER}} .maintenance-offerings__caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .maintenance-offerings__caption',
            ]
        );

        $this->add_control(
            'is_alt',
            [
                'label' => esc_html__('Switch type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'default' => [
                        'title' => esc_html__('Default', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'alt' => [
                        'title' => esc_html__('Alt', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'alt',
                'toggle' => false,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_id', [
                'label' => __('ID', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'palceholder' => __('ID', 'elementor-custom-widgets'),
            ]
        );

        $repeater->add_control(
            'number',
            [
                'label' => esc_html__('Choose Number', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
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
            'title', [
                'label' => __('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title', 'elementor-custom-widgets'),
            ]
        );

        $repeater->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .maintenance-offerings__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .maintenance-offerings__title',
            ]
        );

        $repeater->add_control(
            'text', [
                'label' => esc_html__('Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__('Text', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .maintenance-offerings__text > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .maintenance-offerings__text > *',
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
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'callout',
            [
                'label' => esc_html__('Callout', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'callout_title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'callout_title_color',
            [
                'label' => __( 'Callout Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .maintenance-offerings-callout .h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Callout Title Typography', 'elementor-custom-widgets' ),
                'name' => 'callout_title_typography',
                'selector' => '{{WRAPPER}} .maintenance-offerings-callout .h3',
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
                'default' => 'button--white',
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
        $title_class = '';
        $title_class = isset($settings['is_alt']) && $settings['is_alt'] == 'alt' ? ' maintenance-offerings__title--alt': '';
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section maintenance-offerings">
        <div class="grid-cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-secondary num-title wow fadeInUp maintenance-offerings__caption">

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

            <?php if (isset($settings['list'])) : ?>
              <div class="mt-400 maintenance-offerings__list">
                  <?php foreach ($settings['list'] as $item) : ?>
                      <?php $current_item_class = 'elementor-repeater-item-' . $item['_id']; ?>

                      <?php if (isset($item['item_id'])) : ?>
                      <div id="<?= esc_attr($item['item_id']) ?>"></div>
                      <?php endif; ?>

                    <div class="maintenance-offerings__item <?= $current_item_class ?> ">
                      <div class="grid-row wow fadeInUp">
                        <div class="grid-col grid-col--5 grid-col--md-12">
                          <div class="maintenance-offerings__figure">

                              <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                                  <?= wp_get_attachment_image($item['image']['id'], 'full') ?>
                              <?php endif; ?>

                              <?php if (isset($item['number']) && !empty($item['number']['id'])) : ?>
                                <div class="maintenance-offerings__num"><?= file_get_contents(wp_get_original_image_path($item['number']['id'])); ?></div>
                              <?php endif; ?>

                              <?php if (isset($item['title'])) : ?>
                                <h2 class="h1 maintenance-offerings__title <?= esc_attr($title_class) ?>"><?= wp_kses_post($item['title']) ?></h2>
                              <?php endif; ?>

                          </div>
                        </div>
                        <div class="mt-100 grid-col grid-col--auto grid-col--md-12"></div>
                        <div class="grid-col grid-col--auto grid-col--md-12"></div>

                          <?php if (isset($item['text'])) : ?>
                            <div class="grid-col maintenance-offerings__text"><?= wp_kses_post($item['text']) ?> </div>
                          <?php endif; ?>

                      </div>
                    </div>

                  <?php endforeach; ?>
              </div>
            <?php endif; ?>

        </div>
      </div>

        <?php if (isset($settings['callout_title']) && !empty($settings['callout_title'])) : ?>
      <div class="maintenance-offerings-callout">
        <div class="grid-cont">
          <div class="grid-row grid-row--aic">
            <div class="grid-col">

              <div class="h3"><?= wp_kses_post($settings['callout_title']) ?></div>

            </div>
            <div class="mt-100 grid-col grid-col--auto grid-col--sm-12"></div>
            <div class="grid-col grid-col--auto">

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
    <?php endif; ?>
        <?php
    }
}


