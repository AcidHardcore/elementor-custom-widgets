<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Sport_Type extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-sport-type.css');
        wp_register_style('widget-sport-type', get_template_directory_uri() . '/css/widget-sport-type.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-sport-type'];
    }

    public function get_name()
    {
        return 'sport-type';
    }

    public function get_title()
    {
        return __('Sport Type', 'elementor-custom-widgets');
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
            'label' => __('Sport Type', 'elementor-custom-widgets'),
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
                    '{{WRAPPER}} .sport-type__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .sport-type__title',
            ]
        );

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
                    '{{WRAPPER}} .sport-type__text > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .sport-type__text > *',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_size',
            [
                'label' => esc_html__('Size', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'small' => [
                        'title' => esc_html__('Small', 'elementor-custom-widgets'),
                        'icon' => 'eicon-arrow-left',
                    ],
                    'large' => [
                        'title' => esc_html__('Large', 'elementor-custom-widgets'),
                        'icon' => 'eicon-arrow-right',
                    ],
                ],
                'default' => 'small',
                'toggle' => false,
            ]
        );

        $repeater->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
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
            'sport_link_type',
            [
                'label' => esc_html__('Sport Link type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'sport-type__item',
                'options' => $this->config::SPORT_LINK_TYPE
            ]
        );


        $this->add_control(
            'types',
            [
                'label' => esc_html__('Types', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Type #1', 'elementor-custom-widgets'),
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
        $title_class = ' sport-type__title';

        if ($settings['is_num'] === 'num') {
            $title_class .= ' num-title';
        }
        ?>


      <div id="<?= esc_attr($section_id) ?>" class="section sport-type">
        <div class="grid-cont">
          <div class="grid-row grid-row--aic">
            <div data-wow-delay="0.25s" class="grid-col grid-col--md-12 wow fadeInUp">

                <?php if (isset($settings['title'])) : ?>
                  <h2 class="tc-primary <?= esc_attr($title_class) ?>">

                      <?php if (isset($settings['image']) && !empty($settings['image']['id']) && $settings['is_num'] === 'num') : ?>
                    <div class="num-title__img num-title__img--left"><?= file_get_contents(wp_get_original_image_path($settings['image']['id'])); ?></div>
                      <?php endif; ?>

                      <?php if ($settings['is_num'] === 'num') : ?>
                        <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                      <?php else : ?>
                          <?= wp_kses_post($settings['title']) ?>
                      <?php endif; ?>

                  </h2>
                <?php endif; ?>

                <?php if (isset($settings['text'])) : ?>
                  <div class="mt-150 sport-type__text"><?= wp_kses_post($settings['text']) ?></div>
                <?php endif; ?>

            </div>
            <div class="grid-col grid-col--auto removed-md"></div>
            <div class="grid-col grid-col--auto removed-md"></div>
            <div class="grid-col grid-col--8 grid-col--md-12 wow fadeInUp">
              <div class="grid-row grid-row--smg sport-type__grid">
                  <?php foreach ($settings['types'] as $item) : ?>
                      <?php
                      if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                          $this->add_link_attributes('cta_link', $item['cta_link']);
                      }
                      $item_class = 'sport-type__item';
                      if ($item['item_size'] === 'large') {
                          $item_class .= ' sport-type__item--lg';
                      }
                      if($item['sport_link_type'] !== 'sport-type__item') {
                          $item_class .= ' ' . $item['sport_link_type'];
                      }
                      ?>
                    <div class="grid-col grid-col--4 grid-col--sm-6">

                        <?php if (isset($item['title']) && !empty($item['cta_link']['url'])) : ?>
                          <a href="<?= esc_url($item['cta_link']['url']) ?>"
                             class="<?= esc_attr($item_class)?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>

                              <?php if (isset($item['image']) && !empty($settings['image']['id'])) : ?>
                                  <?= wp_get_attachment_image($item['image']['id'], 'full') ?>
                              <?php endif; ?>

                            <span><?= esc_html($item['title']) ?></span>
                          </a>
                        <?php endif; ?>

                    </div>

                  <?php endforeach; ?>

              </div>
            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


