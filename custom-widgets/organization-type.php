<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Organization_Type extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-organization-type.css');
        wp_register_style('widget-organization-type', get_template_directory_uri() . '/css/widget-organization-type.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-organization-type.js');
        wp_register_script('widget-organization-type', get_template_directory_uri() . '/js/widget-organization-type.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['slick-styles', 'widget-organization-type'];
    }

    public function get_script_depends()
    {
        return ['slick-scripts', 'widget-organization-type'];
    }

    public function get_name()
    {
        return 'organization-type';
    }

    public function get_title()
    {
        return __('Organization Type', 'elementor-custom-widgets');
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
            'label' => __('Organization Type', 'elementor-custom-widgets'),
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
                    '{{WRAPPER}} .organization-type__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .organization-type__title',
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
            'view_type',
            [
                'label' => esc_html__('View Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'content' => [
                        'title' => esc_html__('Content', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'accordion' => [
                        'title' => esc_html__('Accordion', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'extra' => [
                        'title' => esc_html__('Extra', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'content',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'lead_color',
            [
                'label' => __( 'Lead Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__lead' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Lead Typography', 'elementor-custom-widgets' ),
                'name' => 'lead_typography',
                'selector' => '{{WRAPPER}} .organization-type__lead',
            ]
        );

        $this->add_control(
            'lead_color_active',
            [
                'label' => __( 'Lead Color Active', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__lead.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Lead Typography Active', 'elementor-custom-widgets' ),
                'name' => 'lead_typography_active',
                'selector' => '{{WRAPPER}} .organization-type__lead.active',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __( 'Heading Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__heading' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'view_type' => ['content', 'extra']
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Heading Typography', 'elementor-custom-widgets' ),
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .organization-type__heading',
                'condition' => [
                    'view_type' => ['content', 'extra']
                ]
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__text' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'view_type' => ['content', 'extra']
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .organization-type__text > *',
                'condition' => [
                    'view_type' => ['content', 'extra']
                ]
            ]
        );

        $this->add_control(
            'toggle_color',
            [
                'label' => __( 'Toggle Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__toggle' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'view_type' => 'accordion'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Toggle Typography', 'elementor-custom-widgets' ),
                'name' => 'toggle_typography',
                'selector' => '{{WRAPPER}} .organization-type__toggle',
                'condition' => [
                    'view_type' => 'accordion'
                ]
            ]
        );

        $this->add_control(
            'spoiler_color',
            [
                'label' => __( 'Spoiler Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__spoiler' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'view_type' => 'accordion'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Spoiler Typography', 'elementor-custom-widgets' ),
                'name' => 'spoiler_typography',
                'selector' => '{{WRAPPER}} .organization-type__spoiler',
                'condition' => [
                    'view_type' => 'accordion'
                ]
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __( 'Subtitle Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__subtitle' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'view_type' => 'extra'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Subtitle Typography', 'elementor-custom-widgets' ),
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .organization-type__subtitle',
                'condition' => [
                    'view_type' => 'extra'
                ]
            ]
        );

        $this->add_control(
            'cta_link_color',
            [
                'label' => __( 'CTA Link Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__cta-link' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'view_type' => 'extra'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'CTA Link Typography', 'elementor-custom-widgets' ),
                'name' => 'cta_link_typography',
                'selector' => '{{WRAPPER}} .organization-type__cta-link',
                'condition' => [
                    'view_type' => 'extra'
                ]
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => esc_html__('Button type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'button--primary',
                'options' => $this->config::BUTTON_TYPE,
                'condition' => [
                    'view_type' => 'content'
                ]

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
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $repeater->add_control(
            'link_text', [
                'label' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Learn more', 'elementor-custom-widgets'),
                'label_block' => true,
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
                'condition' => [
                    'view_type' => 'content'
                ]
            ]
        );

        $accordion = new Repeater();

        $accordion->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $accordion->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $accordion->add_control(
            'text', [
            'label' => __('Accordion', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);


        $this->add_control(
            'accordions',
            [
                'label' => esc_html__('Accordion', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $accordion->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Accordion #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ title }}}',
                'condition' => [
                    'view_type' => 'accordion'
                ]
            ]
        );

        $extra = new Repeater();

        $extra->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $extra->add_control(
            'right_title', [
                'label' => esc_html__('Right Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Right Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $extra->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $extra->add_control(
            'subtitle', [
                'label' => esc_html__('Subtitle', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Subtitle', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $extra->add_control(
            'text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $extra->add_control(
            'links', [
            'label' => __('Links', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('links', 'elementor-custom-widgets'),
        ]);

        $extra->add_control(
            'sub_image',
            [
                'label' => esc_html__('Choose Sub Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );


        $this->add_control(
            'extras',
            [
                'label' => esc_html__('Extras', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $extra->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Extra #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ title }}}',
                'condition' => [
                    'view_type' => 'extra'
                ]
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

        $this->add_control(
            'bg_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'smoke',
                'options' => [
                    'smoke'  => esc_html__( 'Smoke', 'elementor-custom-widgets' ),
                    'white' => esc_html__( 'White', 'elementor-custom-widgets' ),
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_class = isset($settings['nop']) && $settings['nop'] === 'yes' ? 'section--nop' : '';
        $section_class .= isset($settings['bg_color']) && $settings['bg_color'] === 'white' ? ' organization-type--white' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        $list = array();
        $svg_class = '';
        $title_class = 'organization-type__title';
        if ($settings['view_type'] === 'content') {
            $list = $settings['types'];
        }
        if ($settings['view_type'] === 'accordion') {
            $list = $settings['accordions'];
        }
        if ($settings['view_type'] === 'extra') {
            $list = $settings['extras'];
            $svg_class = 'organization-type__num--left';
            $title_class .= ' tc-secondary ';
        }

        if (!empty($list)) {
            $first_key = array_key_first($list);
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section organization-type <?= esc_attr($section_class) ?>">
        <div class="grid-cont grid-cont--sm">
          <div class="organization-type__cont">
            <div class="grid-row grid-row--aic">
              <div class="grid-col grid-col--sm-12 grid-col--ass">
                <div class="organization-type__nav">

                  <div class="organization-type__bg">
                      <?php if (!empty($list)) : ?>
                          <?php foreach ($list as $index => $item) : ?>
                              <?php $class = $index === $first_key ? ' active' : '' ?>

                              <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                                  <?= wp_get_attachment_image($item['image']['id'], 'full', false, array('class' => $class)) ?>
                              <?php endif; ?>

                          <?php endforeach; ?>
                      <?php endif; ?>
                  </div>

                    <?php if (isset($settings['title'])) : ?>
                      <h2 class="<?= esc_attr($title_class) ?> wow fadeInUp"><?= wp_kses_post($settings['title']) ?></h2>
                    <?php endif; ?>

                  <ul class="h3 wow fadeInUp">
                      <?php if (!empty($list)) : ?>
                          <?php foreach ($list as $index => $item) : ?>
                              <?php
                              $class = "organization-type__lead";
                              $class .= $index === $first_key ? ' active' : '' ?>

                              <?php if (isset($item['title'])) : ?>
                            <li class="<?= esc_attr($class) ?>"><?= wp_kses_post($item['title']) ?></li>
                              <?php endif; ?>

                          <?php endforeach; ?>
                      <?php endif; ?>
                  </ul>

                </div>
              </div>
              <div class="grid-col grid-col--auto removed-md"></div>
              <div class="grid-col grid-col--auto removed-sm"></div>
              <div data-wow-delay="0.25s" class="grid-col wow fadeInUp">
                  <?php if (!empty($list)) : ?>
                      <?php foreach ($list as $index => $item) : ?>
                          <?php
                          $button_class = 'button';
                          if ($settings['view_type'] === 'content' && isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                              $this->add_link_attributes('cta_link', $item['cta_link']);
                              if($settings['button_type'] !== 'button') {
                                  $button_class .= ' ' . $settings['button_type'];
                              }
                          }
                          $class = $index === $first_key ? 'active ' : ''
                          ?>

                      <div class="organization-type__slide <?= esc_attr($class) ?>">

                          <?php if ($settings['view_type'] === 'content') : ?>

                              <?php if (isset($item['title'])) : ?>
                              <h2 class="tc-primary organization-type__heading"><?= wp_kses_post($item['title']) ?></h2>
                              <?php endif; ?>

                              <?php if (isset($item['text'])) : ?>
                              <div class="mt-100 organization-type__text"><?= wp_kses_post($item['text']) ?></div>
                              <?php endif; ?>

                              <?php if (isset($item['link_text']) && !empty($item['cta_link']['url'])) : ?>
                              <a href="<?= esc_url($item['cta_link']['url']) ?>"
                                 class="mt-150 <?= $button_class ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                                  <?= esc_html($item['link_text']) ?>
                              </a>
                              <?php endif; ?>

                          <?php endif; ?>

                          <?php if ($settings['view_type'] === 'accordion') : ?>

                              <?php if (isset($item['text'])) : ?>
                                  <?= wp_kses_post($item['text']) ?>
                              <?php endif; ?>

                          <?php endif; ?>

                          <?php if ($settings['view_type'] === 'extra') : ?>

                              <?php if (isset($item['text']) && !empty($item['text'])) : ?>

                              <div class="grid-row grid-row--aic">
                                <div class="grid-col grid-col--8 grid-col--md-12">

                                    <?php if (isset($item['right_title'])) : ?>
                                      <h2 class="tc-primary organization-type__heading"><?= wp_kses_post($item['right_title']) ?></h2>
                                    <?php endif; ?>

                                    <?php if (isset($item['text'])) : ?>
                                      <div class="mt-100 organization-type__text"><?= wp_kses_post($item['text']) ?></div>
                                    <?php endif; ?>

                                    <?php if (isset($item['subtitle'])) : ?>
                                      <h3 class="mt-200 tc-primary organization-type__subtitle"><?= wp_kses_post($item['subtitle']) ?></h3>
                                    <?php endif; ?>

                                    <?php if (isset($item['links'])) : ?>
                                        <?= wp_kses_post($item['links']) ?>
                                    <?php endif; ?>

                                </div>
                                <div class="grid-col grid-col--4 removed-md">
                                  <div class="organization-type__figure">

                                      <?php if (isset($item['sub_image']) && !empty($settings['sub_image']['id'])) : ?>
                                          <?= wp_get_attachment_image($item['sub_image']['id'], 'full', false, array('class' => $class)) ?>
                                      <?php endif; ?>

                                  </div>
                                </div>
                              </div>

                              <?php else : ?>

                                  <?php if (isset($item['right_title'])) : ?>
                                      <h2 class="tc-primary organization-type__heading"><?= wp_kses_post($item['right_title']) ?></h2>
                                  <?php endif; ?>

                              <?php endif; ?>

                          <?php endif; ?>
                      </div>

                      <?php endforeach; ?>
                  <?php endif; ?>
              </div>
            </div>
            <div class="arrows organization-type__arrows">
              <button type="button" class="arrows__prev"></button>
              <button type="button" class="arrows__next"></button>
            </div>
          </div>
        </div>

          <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
            <div class="organization-type__num <?= esc_attr($svg_class) ?>"><?= file_get_contents(wp_get_original_image_path($settings['image']['id'])); ?></div>
          <?php endif; ?>

      </div>
        <?php
    }
}


