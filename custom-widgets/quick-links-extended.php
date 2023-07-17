<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Quick_Links_Extended extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-quick-links'];
    }

    public function get_name()
    {
        return 'quick-links-extended';
    }

    public function get_title()
    {
        return __('Quick Links Extended', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-editor-external-link';
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
            'label' => __('Quick Links Extended', 'elementor-custom-widgets'),
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
                    '{{WRAPPER}} .quick-links__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .quick-links__title',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .quick-links__heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'item_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .quick-links__heading',
            ]
        );

        $repeater->add_control(
            'text', [
                'label' => esc_html__('Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::WYSIWYG,
                'row' => 3,
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
                    '{{WRAPPER}} {{CURRENT_ITEM}} .quick-links__desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .quick-links__desc > *',
            ]
        );

        $repeater->add_control(
            'link_text', [
                'label' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Learn More', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'button_type',
            [
                'label' => esc_html__('Button type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'button',
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

        $this->add_control(
            'list',
            [
                'label' => esc_html__('Links', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Title', 'elementor-custom-widgets'),
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
        $title_class = 'quick-links__title';
        if ($settings['is_num'] === 'num') {
            $title_class .= ' num-title';
        }
        ?>


      <div id="<?= esc_attr($section_id) ?>" class="section section--nobg ta-center">
        <div class="grid-cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="<?= esc_attr($title_class) ?>">

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

        </div>
        <div class="mt-300 quick-links">
          <div class="grid-row grid-row--nog">
              <?php if ($settings['list']) : ?>
              <?php $delay = 0; ?>
              <?php foreach ($settings['list'] as $item) :

                      $button_class = 'button';
              if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                  $this->add_link_attributes('cta_link', $item['cta_link']);
                  if($item['button_type'] !== 'button') {
                      $button_class .= ' ' . $item['button_type'];

                  }
              }
                      $current_item_class = 'elementor-repeater-item-' . $item['_id'];
              ?>
            <div class="grid-col grid-col--6 grid-col--sm-12">
              <div class="quick-links__item quick-links__item--nohover">

                  <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                      <?= wp_get_attachment_image($item['image']['id'], 'full', false, array('class' => 'quick-links__image')) ?>
                  <?php endif; ?>

                <div data-wow-delay="<?php esc_attr($delay) ?>s" class="quick-links__text wow fadeInUp <?= esc_attr($current_item_class) ?>">

                    <?php if (isset($item['title'])) : ?>
                      <div class="h2 quick-links__heading"><?= esc_html($item['title']) ?></div>
                    <?php endif; ?>

                    <?php if (isset($item['text'])) : ?>
                      <div class="mt-100 p p--lg quick-links__desc"><?= wp_kses_post($item['text']) ?></div>
                    <?php endif; ?>

                    <?php if (isset($item['link_text']) && !empty($item['cta_link']['url'])) : ?>
                      <a href="<?= esc_url($item['cta_link']['url']) ?>" class="mt-100 <?= esc_attr($button_class) ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>><?= wp_kses_post($item['link_text']) ?></a>
                    <?php endif; ?>
                </div>
              </div>
            </div>
                <?php $delay += 0.25; ?>
                <?php endforeach; ?>
                <?php endif; ?>
          </div>
        </div>
      </div>

        <?php
    }
}


